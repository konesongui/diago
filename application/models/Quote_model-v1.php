<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Quote_model extends MY_Model
{
    protected $table = 'quotes';
    protected $items_table = 'quote_items';
    protected $stock_table = 'stock';
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * Ajoute un nouveau devis avec ses articles
     * 
     * @param array $data Les données du devis
     * @return int|bool L'ID du devis créé ou false en cas d'erreur
     */
    public function add($data)
    {   
        $this->db->trans_start();

        try {
            // Préparation des données du devis
            $quote_data = [
                'quote_number' => $this->generateQuoteNumber(),
                'customer_id' => $data['customer'],
                'designation' => $data['designation'],
                'apply_tva' => isset($data['apply_tva']) ? $data['apply_tva'] : 1,
                'tva_rate' => isset($data['tva_rate']) ? $data['tva_rate'] : 20.00,
                'total_ht' => $data['total_ht'],
                'tva_amount' => $data['tva_amount'],
                'total_ttc' => $data['total_ttc'],
                'issue_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['issue_date']))),
                'valid_until' => isset($data['valid_until']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['valid_until']))) : null,
                'notes' => isset($data['notes']) ? $data['notes'] : null,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ];

            // Insertion du devis
            $this->db->insert($this->table, $quote_data);
            $quote_id = $this->db->insert_id();

            if (!$quote_id) {
                throw new Exception('Erreur lors de la création du devis');
            }

            // Insertion des articles
            foreach ($data['items'] as $position => $item) {
                $item_data = [
                    'quote_id' => $quote_id,
                    'category_id' => $item['category_id'],
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'unit' => $item['unit'],
                    'line_total' => $item['line_total'],
                    'position' => $position + 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if (!$this->db->insert($this->items_table, $item_data)) {
                    throw new Exception('Erreur lors de l\'ajout d\'un article au devis');
                }
            }

            $this->db->trans_complete();
            return $quote_id;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Quote Model Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Génère un numéro unique pour un devis
     * Format: DEV-YYYYMM-XXXX où XXXX est un numéro séquentiel
     * 
     * @return string
     */
    private function generateQuoteNumber()
    {
        $prefix = 'DEV';  // DEV pour Devis
        $date = date('Ym');  // Format YYYYMM
        
        // Recherche le dernier numéro pour ce mois
        $this->db->like('quote_number', $prefix . '-' . $date, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);
        
        if ($query->num_rows() > 0) {
            // Extrait le numéro séquentiel du dernier devis
            $last_ref = $query->row()->quote_number;
            $sequence = intval(substr($last_ref, -4)) + 1;
        } else {
            // Premier devis du mois
            $sequence = 1;
        }
        
        // Formate le numéro séquentiel sur 4 chiffres
        $sequence_padded = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        return $prefix . '-' . $date . '-' . $sequence_padded;
    }

    /**
     * Récupère un devis avec ses articles
     * 
     * @param int $quote_id ID du devis
     * @return array|null Les données du devis et ses articles
     */
    public function getQuoteWithItems($quote_id)
    {
        // Récupération du devis
        $this->db->select('quotes.*, clients.item_supplier as customer_name,
            clients.lastname as customer_last_name, clients.email as customer_email');
        $this->db->from($this->table);
        $this->db->join('clients', 'clients.id = quotes.customer_id');
        $this->db->where('quotes.id', $quote_id);
        $quote = $this->db->get()->row_array();

        if (!$quote) {
            return null;
        }

        // Récupération des articles
        $this->db->select('
            quote_items.*,
            item_category.item_category as category_name,
            item.name as item_name
        ');
        $this->db->from($this->items_table);
        $this->db->join('item_category', 'item_category.id = quote_items.category_id');
        $this->db->join('item', 'item.id = quote_items.item_id');
        $this->db->where('quote_id', $quote_id);
        $this->db->order_by('position', 'ASC');
        $items = $this->db->get()->result_array();

        $quote['items'] = $items;
        return $quote;
    }

    /**
     * Met à jour le statut d'un devis
     * 
     * @param int $quote_id ID du devis
     * @param int $status Nouveau statut
     * @return bool
     */
    public function updateStatus($quote_id, $status)
    {
        $this->db->where('id', $quote_id);
        return $this->db->update($this->table, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            // 'updated_by' => $this->session->userdata('admin')['id']
        ]);
    }

    /**
     * Met à jour le statut d'un devis en rejeté avec le motif
     * 
     * @param int $quote_id ID du devis
     * @param string $reason Motif du rejet
     * @return bool
     */
    public function updateRejectStatus($quote_id, $data)
    {   
        // var_dump($data);
        // exit;

        $this->db->where('id', $quote_id);
        return $this->db->update($this->table, [
            'status'     => $data['status'], // 3 = Rejeté
            'notes'      => $data['reason'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Récupère l'état du stock pour un article
     * 
     * @param int $item_id ID de l'article
     * @return object|null
     */
    public function getStockStatus($item_id)
    {
        $this->db->where('item_id', $item_id);
        $query = $this->db->get($this->stock_table);
        return $query->row();
    }


    public function getListData()
    {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'];

        $total_records = $this->db->count_all($this->table);

        $this->db->start_cache();
        
        $this->db->select('
            quotes.id,
            quotes.quote_number,
            quotes.designation,
            quotes.issue_date,
            quotes.valid_until,
            quotes.total_ht,
            quotes.tva_amount,
            quotes.total_ttc,
            quotes.status,
            quotes.created_at,
            quotes.notes,
            quotes.apply_tva,
            quotes.tva_rate,
            clients.item_supplier as customer_name,
            clients.lastname as customer_last_name,
            clients.email as customer_email,
            clients.phone as customer_phone,
            clients.address as customer_address
        ');
        $this->db->from($this->table);
        $this->db->join('clients', 'clients.id = quotes.customer_id', 'left');
        
        if($search) {
            $this->db->group_start();
            $this->db->like('quotes.designation', $search);
            $this->db->or_like('quotes.quote_number', $search);
            $this->db->or_like('clients.item_supplier', $search);
            $this->db->or_like('clients.email', $search);
            $this->db->group_end();
        }
        
        $this->db->stop_cache();

        $filtered_records = $this->db->get()->num_rows();
        
        $this->db->order_by('quotes.created_at', 'DESC');
        if($length != -1) {
            $this->db->limit($length, $start);
        }

        $query = $this->db->get();
        
        $this->db->flush_cache();
        
        $status_labels = [
            1 => ['label' => 'En attente', 'class' => 'label-warning'],
            2 => ['label' => 'Accepté', 'class' => 'label-success'],
            3 => ['label' => 'Refusé', 'class' => 'label-danger'],
            4 => ['label' => 'Expiré', 'class' => 'label-default']
        ];

        $data = [];
        foreach($query->result() as $row) {
            $status_info = isset($status_labels[$row->status]) ? $status_labels[$row->status] : ['label' => 'Inconnu', 'class' => 'label-default'];
            
            $data[] = [
                'id' => $row->id,
                'quote_number' => $row->quote_number,
                'designation' => $row->designation,
                'customer' => [
                    'name' => $row->customer_name.' '.$row->customer_last_name,
                    'email' => $row->customer_email,
                    'phone' => $row->customer_phone,
                    'address' => $row->customer_address
                ],
                'dates' => [
                    'creation' => date('d/m/Y', strtotime($row->created_at)),
                    'emission' => date('d/m/Y', strtotime($row->issue_date)),
                    'validite' => $row->valid_until ? date('d/m/Y', strtotime($row->valid_until)) : 'Non définie'
                ],
                'amount' => [
                    'ht' => number_format($row->total_ht, 2, ',', ' '),
                    'tva' => number_format($row->tva_amount, 2, ',', ' '),
                    'ttc' => number_format($row->total_ttc, 2, ',', ' ')
                ],
                'tva_info' => [
                    'appliquee' => $row->apply_tva ? 'Oui' : 'Non',
                    'taux' => $row->tva_rate . '%'
                ],
                'status' => [
                    'code' => $row->status,
                    'label' => $status_info['label'],
                    'class' => $status_info['class']
                ],
                'notes' => $row->notes
            ];
        }

        return json_encode([
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filtered_records,
            'data' => $data
        ]);
    }

    /**
     * Met à jour un devis existant et ses articles
     * 
     * @param array $data Les données du devis à mettre à jour
     * @return bool
     */
    public function update($data) {
        $this->db->trans_start();

        try {
            // Mise à jour des informations principales du devis
            $quote_data = [
                'designation' => $data['designation'],
                'customer_id' => $data['customer'],
                'issue_date' => $data['issue_date'],
                'apply_tva' => $data['apply_tva'] ? 1 : 0,
                'tva_amount' => $data['tva_amount'],
                'total_ht' => $data['total_ht'],
                'total_ttc' => $data['total_ttc'],
                'tva_rate' => $data['tva_rate'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id', $data['id']);
            $this->db->update('quotes', $quote_data);

            // Suppression des anciens articles
            $this->db->where('quote_id', $data['id']);
            $this->db->delete('quote_items');

            // Insertion des nouveaux articles
            foreach ($data['items'] as $item) {
                $item_data = [
                    'quote_id'      => $data['id'],
                    'category_id'   => $item['category_id'],
                    'item_id'       => $item['item_id'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price'],
                    'unit'          => $item['unit'],
                    'line_total'    => $item['line_total']
                ];
                $this->db->insert('quote_items', $item_data);
            }

            $this->db->trans_complete();
            return $this->db->trans_status();

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Quote Update Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les données du devis formatées pour l'impression
     * 
     * @param int $quote_id ID du devis
     * @return array|null Les données formatées du devis
     */
    public function getQuoteForPrint($quote_id)
    {
        $quote = $this->getQuoteWithItems($quote_id);
        
        if (!$quote) {
            return null;
        }

        // Formatage des données pour l'impression
        $print_data = [
            'quote_number' => $quote['quote_number'],
            'issue_date' => date('d/m/Y', strtotime($quote['issue_date'])),
            'valid_until' => $quote['valid_until'] ? date('d/m/Y', strtotime($quote['valid_until'])) : 'Non définie',
            'customer' => [
                'name' => $quote['customer_name'] . ' ' . $quote['customer_last_name'],
                'email' => $quote['customer_email'],
                'address' => $quote['customer_address'] ?? ''
            ],
            'items' => [],
            'totals' => [
                'ht' => number_format($quote['total_ht'], 2, ',', ' '),
                'tva' => number_format($quote['tva_amount'], 2, ',', ' '),
                'ttc' => number_format($quote['total_ttc'], 2, ',', ' ')
            ],
            'tva_info' => [
                'applied' => $quote['apply_tva'] ? 'Oui' : 'Non',
                'rate' => $quote['tva_rate'] . '%'
            ],
            'notes' => $quote['notes']
        ];

        // Formatage des articles
        foreach ($quote['items'] as $item) {
            $print_data['items'][] = [
                'category' => $item['category_name'],
                'name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price' => number_format($item['price'], 2, ',', ' '),
                'total' => number_format($item['line_total'], 2, ',', ' ')
            ];
        }

        return $print_data;
    }
}
