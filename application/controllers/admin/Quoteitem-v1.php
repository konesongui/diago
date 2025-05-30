<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Quoteitem extends Admin_Controller {

    /**
     * Constructor - Loads necessary helpers and performs initialization
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('stockentry_model');
        $this->load->model('itemcategory_model');
        $this->load->model('stock_model');
        $this->load->model('clients_model');
        $this->load->model('quote_model');
        $this->load->library('form_validation');
        $this->load->library('rbac');
        $this->load->library('session');
    }


    /**
     * Main index method - Handles item listing and creation
     */
    function index() {
        // Check view permission
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_view')) {
            access_denied();
        }

        // Set menu active states
        $this->session->set_userdata('top_menu', 'Inventory');
        $this->session->set_userdata('sub_menu', 'Quoteitem/index');
        
        // Initialize page data
        $data = [
            'title' => 'Add Item',
            'title_list' => 'Recent Items',
        ];


        // Load views
        $this->load->view('layout/header', $data);
        $this->load->view('admin/quote/list', $data);
        $this->load->view('layout/footer', $data);
    }


    /**
     * GET STOCK ENTRY DATA
     * IN JSON FORMAT
     * 
     * @return  JSON   $response
     */
    public function data()
    {   
        // Récupère les données du modèle
        $result = $this->quote_model->getListData();
        
        // Les données sont déjà au format JSON, on les renvoie directement
        echo $result;
    }

    public function getItemByCategory()
    {
        $item_category_id = $this->input->get('item_category_id');
        $data             = $this->stock_model->getItemByCategory($item_category_id);
        echo json_encode($data);
    }


    /**
     * STOCK ENTRY TOOL FORM
     */
    public function form() {
        // Définition des menus actifs
        $this->session->set_userdata('top_menu', 'Inventory');
        $this->session->set_userdata('sub_menu', 'Quoteitem/index');
        
        // Préparation des données pour la vue
        $data = [
            'title' => 'Ajouter un article au devis',
            'title_list' => 'Derniers articles ajoutés au devis',
            'itemcatlist' => $this->itemcategory_model->get(),
            'clients' => $this->clients_model->get()
        ];

        // Chargement des vues
        $this->load->view('layout/header', $data);
        $this->load->view('admin/quote/form', $data);
        $this->load->view('layout/footer', $data);
    }

    
    
    /**
     * STOCK ENTRY TOOL FORM
     */

     public function add() {
        // Initialisation de la réponse
        $response = ['status' => 'fail', 'message' => '', 'error' => []];

        try {
            // Vérification des données POST
            if (!$this->input->post()) {
                throw new Exception('Aucune donnée reçue');
            }

            // Validation des champs obligatoires
            $this->form_validation->set_rules('designation', 'Désignation', 'required|trim');
            $this->form_validation->set_rules('customer', 'Client', 'required|trim');
            $this->form_validation->set_rules('issue_date', 'Date', 'required');
            $this->form_validation->set_rules('item_category_id[]', 'Catégorie', 'required');
            $this->form_validation->set_rules('item_id[]', 'Article', 'required');
            $this->form_validation->set_rules('quantity[]', 'Quantité', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('price[]', 'Prix unitaire', 'required|numeric|greater_than[0]');

            if ($this->form_validation->run() == false) {
                $response['error'] = $this->form_validation->error_array();
                echo json_encode($response);
                return;
            }

            // Récupération et validation des données
            $data = [
                'designation'   => $this->input->post('designation'),
                'customer'      => $this->input->post('customer'),
                'apply_tva'     => $this->input->post('apply_tva'),
                'tva_amount'    => $this->input->post('tva_amount'),
                'total_ht'      => $this->input->post('total_ht'),
                'total_ttc'     => $this->input->post('total_ttc'),
                'tva_rate'      => $this->input->post('tva_rate'),
                'issue_date'    => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('issue_date')))),
                'items' => []
            ];

            // Validation des articles
            $categories = $this->input->post('item_category_id');
            $items = $this->input->post('item_id');
            $quantities = $this->input->post('quantity');
            $prices = $this->input->post('price');
            $units = $this->input->post('unit');

            if (!is_array($categories) || !is_array($items) || !is_array($quantities) || !is_array($prices)) {
                throw new Exception('Format de données invalide');
            }

            // Construction du tableau d'articles
            foreach ($categories as $index => $category_id) {
                if (empty($items[$index]) || empty($quantities[$index]) || empty($prices[$index])) {
                    throw new Exception('Données d\'article manquantes');
                }

                $quantity = floatval($quantities[$index]);
                $price = floatval($prices[$index]);
                $line_total = $quantity * $price;

                $data['items'][] = [
                    'category_id' => $category_id,
                    'item_id' => $items[$index],
                    'quantity' => $quantity,
                    'price' => $price,
                    'unit' => $units[$index] ?? '',
                    'line_total' => $line_total
                ];
            }

            // var_dump($data);
            // exit;

            // Enregistrement des données
            $insert_id = $this->quote_model->add($data);
            
            if (!$insert_id) {
                throw new Exception('Erreur lors de l\'enregistrement');
            }

            $response['status'] = 'success';
            $response['message'] = 'Le devis a été enregistré avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Quote Add Error: ' . $e->getMessage());
        }

        // Retourner la réponse en JSON
        echo json_encode($response);
    }

    


    /**
     * Affiche les détails d'une entrée de stock
     * 
     * @param int $id ID de l'entrée de stock
     * @return void
     */
    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_view')) {
            access_denied();
        }

        $data['quote'] = $this->quote_model->getQuoteWithItems($id);

        // var_dump($data);
        // exit;
        
        if (!$data['quote']) {
            show_error('Devis non trouvé', 404);
        }

        $data['title'] = 'Détails du devis';
        $data['page_title'] = 'Détails du devis ' . $data['quote']['quote_number'];

        $this->load->view('layout/header');
        $this->load->view('admin/quote/view', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Génère le PDF d'un devis
     * 
     * @param int $quote_id ID du devis
     * @return void
     */
    public function generatePdf($quote_id) {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_view')) {
            access_denied();
        }

        // Récupération des données du devis
        $quote_data = $this->quote_model->getQuoteWithItems($quote_id);
        if (!$quote_data) {
            show_error('Devis introuvable');
        }

        // Génération du PDF
        $this->pdf_quote->generateQuote($quote_data);

        // Création du dossier de stockage si nécessaire
        $upload_dir = FCPATH . 'uploads/quotes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Sauvegarde du PDF
        $pdf_filename = 'devis_' . $quote_data['quote_number'] . '.pdf';
        $pdf_path = $upload_dir . $pdf_filename;
        $this->pdf_quote->Output($pdf_path, 'F');

        // Téléchargement du PDF
        $this->pdf_quote->Output($pdf_filename, 'D');
    }

    
    /**
     * Envoie le devis par email au client
     * 
     * @param int $quote_id ID du devis
     * @return void
     */
    public function sendEmail() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_view')) {
            access_denied();
        }

        var_dump($this->input->post());
        exit;

        // Initialisation de la réponse
        $response = ['status' => 'fail', 'message' => ''];

        // try {
        //     // Récupération des données du devis
        //     $quote_data = $this->quote_model->getQuoteWithItems($quote_id);
        //     if (!$quote_data) {
        //         throw new Exception('Devis introuvable');
        //     }

        //     // Vérification de l'email du client
        //     if (empty($quote_data['customer_email'])) {
        //         throw new Exception('Le client n\'a pas d\'adresse email');
        //     }

        //     // Génération du PDF
        //     $this->pdf_quote->generateQuote($quote_data);

        //     // Création du dossier de stockage si nécessaire
        //     $upload_dir = FCPATH . 'uploads/quotes/';
        //     if (!is_dir($upload_dir)) {
        //         mkdir($upload_dir, 0777, true);
        //     }

        //     // Sauvegarde du PDF
        //     $pdf_filename = 'devis_' . $quote_data['quote_number'] . '.pdf';
        //     $pdf_path = $upload_dir . $pdf_filename;
        //     $this->pdf_quote->Output($pdf_path, 'F');

        //     // Configuration de l'email
        //     $this->load->library('email');
            
        //     $config = [
        //         'protocol' => 'smtp',
        //         'smtp_host' => $this->config->item('smtp_host'),
        //         'smtp_port' => $this->config->item('smtp_port'),
        //         'smtp_user' => $this->config->item('smtp_user'),
        //         'smtp_pass' => $this->config->item('smtp_pass'),
        //         'mailtype' => 'html',
        //         'charset' => 'utf-8',
        //         'wordwrap' => TRUE
        //     ];

        //     $this->email->initialize($config);

        //     // Préparation de l'email
        //     $this->email->from($this->config->item('smtp_user'), $this->config->item('company_name'));
        //     $this->email->to($quote_data['customer_email']);
        //     $this->email->subject('Devis ' . $quote_data['quote_number'] . ' - ' . $quote_data['designation']);

        //     // Préparation du message
        //     $message = $this->load->view('admin/quote/email_template', [
        //         'quote' => $quote_data,
        //         'company' => [
        //             'name' => $this->config->item('company_name'),
        //             'address' => $this->config->item('company_address'),
        //             'phone' => $this->config->item('company_phone'),
        //             'email' => $this->config->item('company_email')
        //         ]
        //     ], TRUE);

        //     $this->email->message($message);
        //     $this->email->attach($pdf_path);

        //     // Envoi de l'email
        //     if ($this->email->send()) {
        //         // Mise à jour du statut du devis
        //         $this->quote_model->updateStatus($quote_id, 1); // 1 = Envoyé
        //         $response['status'] = 'success';
        //         $response['message'] = 'Le devis a été envoyé avec succès';
        //     } else {
        //         throw new Exception('Erreur lors de l\'envoi de l\'email: ' . $this->email->print_debugger(['headers', 'subject', 'body']));
        //     }

        // } catch (Exception $e) {
        //     $response['message'] = 'Erreur: ' . $e->getMessage();
        //     log_message('error', 'Quote Email Error: ' . $e->getMessage());
        // }

        // // Suppression du fichier PDF temporaire
        // if (isset($pdf_path) && file_exists($pdf_path)) {
        //     unlink($pdf_path);
        // }

        // // Retourner la réponse en JSON
        // echo json_encode($response);
    }

    /**
     * Affiche le formulaire d'édition d'un devis
     * 
     * @param int $id ID du devis
     * @return void
     */
    public function edit($id) {
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_edit')) {
            access_denied();
        }

       
        // Vérifier le statut du devis
        $quote = $this->quote_model->getQuoteWithItems($id);
        if (!$quote) {
            $this->session->set_flashdata('error', 'Devis introuvable');
            redirect('admin/quoteitem');
        }

        if ((int)$quote['status'] !== 1) {
            $this->session->set_flashdata('error', 'Ce devis ne peut plus être modifié');
            redirect('admin/quoteitem');
        }

        // Préparer les données pour la vue
        $data = [
            'title' => 'Modifier le devis',
            'quote' => $quote,
            'itemcatlist' => $this->itemcategory_model->get(),
            'itemList' => $this->item_model->get(),
            'clients' => $this->clients_model->get()
        ];

        // Charger les vues
        $this->load->view('layout/header', $data);
        $this->load->view('admin/quote/edit', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Met à jour un devis existant
     * 
     * @param int $id ID du devis
     * @return void
     */
    public function update() {
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_edit')) {
            access_denied();
        }

        // var_dump($this->input->post());
        // exit;  
        // Récupérer l'ID du devis
        $id = $this->input->post('id');
        
        // Initialisation de la réponse
        $response = ['status' => 'fail', 'message' => '', 'error' => []];

        try {
            // Vérifier le statut du devis
            $quote = $this->quote_model->getQuoteWithItems($id);
            if (!$quote) {
                throw new Exception('Devis introuvable');
            }

            if ((int)$quote['status'] !== 1) {
                throw new Exception('Ce devis ne peut plus être modifié');
            }

            // Validation des champs obligatoires
            $this->form_validation->set_rules('designation', 'Désignation', 'required|trim');
            $this->form_validation->set_rules('customer', 'Client', 'required|trim');
            $this->form_validation->set_rules('issue_date', 'Date', 'required');
            $this->form_validation->set_rules('item_category_id[]', 'Catégorie', 'required');
            $this->form_validation->set_rules('item_id[]', 'Article', 'required');
            $this->form_validation->set_rules('quantity[]', 'Quantité', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('price[]', 'Prix unitaire', 'required|numeric|greater_than[0]');

            if ($this->form_validation->run() == false) {
                $response['error'] = $this->form_validation->error_array();
                echo json_encode($response);
                return;
            }

            // Récupération et validation des données
            $data = [
                'id' => $this->input->post('id'),
                'designation' => $this->input->post('designation'),
                'customer' => $this->input->post('customer'),
                'apply_tva' => $this->input->post('apply_tva'),
                'tva_amount' => $this->input->post('tva_amount'),
                'total_ht' => $this->input->post('total_ht'),
                'total_ttc' => $this->input->post('total_ttc'),
                'tva_rate' => $this->input->post('tva_rate'),
                'issue_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('issue_date')))),
                'items' => []
            ];

            // Validation des articles
            $categories = $this->input->post('item_category_id');
            $items = $this->input->post('item_id');
            $quantities = $this->input->post('quantity');
            $prices = $this->input->post('price');
            $units = $this->input->post('unit');

            if (!is_array($categories) || !is_array($items) || !is_array($quantities) || !is_array($prices)) {
                throw new Exception('Format de données invalide');
            }

            // Construction du tableau d'articles
            foreach ($categories as $index => $category_id) {
                if (empty($items[$index]) || empty($quantities[$index]) || empty($prices[$index])) {
                    throw new Exception('Données d\'article manquantes');
                }

                $quantity = floatval($quantities[$index]);
                $price = floatval($prices[$index]);
                $line_total = $quantity * $price;

                $data['items'][] = [
                    'category_id' => $category_id,
                    'item_id' => $items[$index],
                    'quantity' => $quantity,
                    'price' => $price,
                    'unit' => $units[$index] ?? '',
                    'line_total' => $line_total
                ];
            }

            // var_dump($data);
            // exit;   
            // Mise à jour des données
            $update_success = $this->quote_model->update($data);
            
            if (!$update_success) {
                throw new Exception('Erreur lors de la mise à jour');
            }

            $response['status'] = 'success';
            $response['message'] = 'Le devis a été mis à jour avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Quote Update Error: ' . $e->getMessage());
        }

        // Retourner la réponse en JSON
        echo json_encode($response);
    }

    /**
     * Crée un bon de commande à partir d'un devis validé
     * 
     * @param int $quote_id ID du devis
     * @return bool
     */
    private function createOrderFromQuote($quote_id) {
        // Récupération des données du devis
        $quote = $this->quote_model->getQuoteWithItems($quote_id);
        if (!$quote) {
            return false;
        }

        // Génération du numéro de commande
        $order_number = $this->generateOrderNumber();

        // Préparation des données de la commande
        $order_data = [
            'order_number'  => $order_number,
            'quote_id'      => $quote_id,
            'customer_id'   => $quote['customer_id'],
            'order_date'    => date('Y-m-d'),
            'valid_until'   => $quote['valid_until'],
            'apply_tva'     => $quote['apply_tva'],
            'designation'   => $quote['designation'],
            'tva_rate'      => $quote['tva_rate'],
            'tva_amount'    => $quote['tva_amount'],
            'total_ht'      => $quote['total_ht'],
            'total_ttc'     => $quote['total_ttc'],
            'notes'         => $quote['notes'],
            'status'        => 1, // 1 = En attente
            'created_at'    => date('Y-m-d H:i:s'),
            // 'created_by'    => $this->session->userdata('admin')['id']
        ];

        // Début de la transaction
        $this->db->trans_start();

        try {
            // Insertion de la commande
            $this->db->insert('orders', $order_data);
            $order_id = $this->db->insert_id();

            if (!$order_id) {
                throw new Exception('Erreur lors de la création de la commande');
            }

            // Insertion des articles de la commande
            foreach ($quote['items'] as $item) {
                $order_item_data = [
                    'order_id'      => $order_id,
                    'category_id'   => $item['category_id'],
                    'item_id'       => $item['item_id'],
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['price'],
                    'line_total'    => $item['line_total'],
                    'position'      => $item['position']
                ];

                if (!$this->db->insert('order_items', $order_item_data)) {
                    throw new Exception('Erreur lors de l\'ajout d\'un article à la commande');
                }
            }

            $this->db->trans_complete();
            return $this->db->trans_status();

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Order Creation Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Génère un numéro unique pour une commande
     * Format: CO-YYYYMM-XXXX où XXXX est un numéro séquentiel
     * 
     * @return string
     */
    private function generateOrderNumber() {
        $prefix = 'CO';  // CO pour Commande
        $date = date('Ym');  // Format YYYYMM
        
        // Recherche le dernier numéro pour ce mois
        $this->db->like('order_number', $prefix . '-' . $date, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('orders');
        
        if ($query->num_rows() > 0) {
            // Extrait le numéro séquentiel de la dernière commande
            $last_ref = $query->row()->order_number;
            $sequence = intval(substr($last_ref, -4)) + 1;
        } else {
            // Première commande du mois
            $sequence = 1;
        }
        
        // Formate le numéro séquentiel sur 4 chiffres
        $sequence_padded = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        return $prefix . '-' . $date . '-' . $sequence_padded;
    }

    /**
     * Valide un devis en changeant son statut à 2 et crée un bon de commande
     * 
     * @param int $id ID du devis
     * @return void
     */
    public function validate($id) {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_edit')) {
            access_denied();
        }

        // Initialisation de la réponse
        $response = ['status' => 'fail', 'message' => ''];

        try {
            // Vérifier si le devis existe
            $quote = $this->quote_model->getQuoteWithItems($id);
            if (!$quote) {
                throw new Exception('Devis introuvable');
            }

            // Vérifier si le devis peut être validé
            if ((int)$quote['status'] !== 1) {
                throw new Exception('Ce devis ne peut plus être validé');
            }

            // Mise à jour du statut
            $update_success = $this->quote_model->updateStatus($id, 2); // 2 = Validé
            
            if (!$update_success) {
                throw new Exception('Erreur lors de la validation du devis');
            }

            // Création du bon de commande
            $order_created = $this->createOrderFromQuote($id);
            if (!$order_created) {
                throw new Exception('Erreur lors de la création du bon de commande');
            }

            $response['status'] = 'success';
            $response['message'] = 'Le devis a été validé et le bon de commande a été créé avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Quote Validate Error: ' . $e->getMessage());
        }

        // Retourner la réponse en JSON
        echo json_encode($response);
    }

    /**
     * Rejette un devis en changeant son statut à 3
     * 
     * @param int $id ID du devis
     * @return void
     */
    public function reject() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('Quoteitem', 'can_edit')) {
            access_denied();
        }

        // var_dump($this->input->post());
        // exit;  
        
        $id = $this->input->post('id');

        // Initialisation de la réponse
        $response = ['status' => 'fail', 'message' => ''];

        try {
            // Vérifier si le devis existe
            $quote = $this->quote_model->getQuoteWithItems($id);
            if (!$quote) {
                throw new Exception('Devis introuvable');
            }

            // Vérifier si le devis peut être rejeté
            if ((int)$quote['status'] !== 1) {
                throw new Exception('Ce devis ne peut plus être rejeté');
            }

            // Récupérer la raison du rejet
            $reject_note = $this->input->post('reason');
            if (empty($reject_note)) {
                throw new Exception('La raison du rejet est obligatoire');
            }

            // Mise à jour du statut et de la note
            $update_data = [
                'status' => 3, // 3 = Rejeté
                'reason' => $reject_note
            ];

            // var_dump($update_data);
            // exit;
            
            $update_success = $this->quote_model->updateRejectStatus($id, $update_data);
            
            if (!$update_success) {
                throw new Exception('Erreur lors du rejet du devis');
            }

            $response['status'] = 'success';
            $response['message'] = 'Le devis a été rejeté avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Quote Reject Error: ' . $e->getMessage());
        }

        // Retourner la réponse en JSON
        echo json_encode($response);
    }


    /**
     * Affiche la page d'impression d'un devis
     * 
     * @param int $id ID du devis
     * @return void
     */
    public function print($id)
    {
        // Récupérer les données du devis
        $quote = $this->quote_model->getQuoteForPrint($id);
        
        if (!$quote) {
            show_404();
            return;
        }

        // Charger la vue d'impression
        $this->load->view('admin/quote/print', ['quote' => $quote]);
    }


    
}