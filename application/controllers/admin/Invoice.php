<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Invoice extends Admin_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('invoice_model');
        $this->load->model('clients_model');
        $this->load->model('itemcategory_model');
        $this->load->model('item_model');
    }

    /**
     * Affiche la liste des factures
     */
    public function index() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_view')) {
            access_denied();
        }

        // Définition des menus actifs
        $this->session->set_userdata('top_menu', 'Finance');
        $this->session->set_userdata('sub_menu', 'Invoice/index');
        
        // Initialisation des données de la page
        $data = [
            'title' => 'Liste des factures',
            'title_list' => 'Dernières factures'
        ];

        // Chargement des vues
        $this->load->view('layout/header', $data);
        $this->load->view('admin/invoice/list', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Récupère les données des factures au format JSON
     */
    public function data() {
        echo $this->invoice_model->getListData();
    }

    /**
     * Affiche le formulaire d'ajout de facture
     */
    public function form() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_add')) {
            access_denied();
        }

        // Définition des menus actifs
        $this->session->set_userdata('top_menu', 'Finance');
        $this->session->set_userdata('sub_menu', 'Invoice/index');
        
        // Préparation des données pour la vue
        $data = [
            'title' => 'Nouvelle facture',
            'title_list' => 'Dernières factures',
            'clients' => $this->clients_model->get(),
            'itemcatlist' => $this->itemcategory_model->get(),
            'itemList' => $this->item_model->get()
        ];

        // Chargement des vues
        $this->load->view('layout/header', $data);
        $this->load->view('admin/invoice/form', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Ajoute une nouvelle facture
     */
    public function add() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_add')) {
            access_denied();
        }

        $response = ['status' => 'fail', 'message' => '', 'error' => []];

        try {
            // Validation des champs obligatoires
            $this->form_validation->set_rules('customer', 'Client', 'required|trim');
            $this->form_validation->set_rules('invoice_date', 'Date de facture', 'required');
            $this->form_validation->set_rules('due_date', 'Date d\'échéance', 'required');
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
                'customer_id' => $this->input->post('customer'),
                'invoice_number' => $this->invoice_model->generateInvoiceNumber(),
                'invoice_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('invoice_date')))),
                'due_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('due_date')))),
                'payment_method' => $this->input->post('payment_method'),
                'apply_tva' => $this->input->post('apply_tva') ? 1 : 0,
                'tva_rate' => $this->input->post('tva_rate'),
                'notes' => $this->input->post('notes'),
                'status' => 0, // 0 = Non payée
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('admin_id'),
                'items' => []
            ];

            // Calcul des totaux
            $total_ht = 0;
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
                $total_ht += $line_total;

                $data['items'][] = [
                    'category_id' => $category_id,
                    'item_id' => $items[$index],
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'unit' => $units[$index] ?? '',
                    'line_total' => $line_total
                ];
            }

            // Calcul des montants TVA et TTC
            $data['total_ht'] = $total_ht;
            $data['tva_amount'] = $data['apply_tva'] ? ($total_ht * $data['tva_rate'] / 100) : 0;
            $data['total_ttc'] = $total_ht + $data['tva_amount'];
            $data['remaining_amount'] = $data['total_ttc'];
            $data['amount_paid'] = 0;

            // Enregistrement des données
            $insert_id = $this->invoice_model->add($data);
            
            if (!$insert_id) {
                throw new Exception('Erreur lors de l\'enregistrement');
            }

            $response['status'] = 'success';
            $response['message'] = 'La facture a été créée avec succès';
            $response['invoice_id'] = $insert_id;

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Invoice Add Error: ' . $e->getMessage());
        }

        echo json_encode($response);
    }

    /**
     * Affiche les détails d'une facture
     * 
     * @param int $id ID de la facture
     */
    public function view($id) {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_view')) {
            access_denied();
        }

        // Récupération des données de la facture
        $data['invoice'] = $this->invoice_model->getInvoiceWithItems($id);
        
        if (!$data['invoice']) {
            $this->session->set_flashdata('error', 'Facture non trouvée');
            redirect('admin/invoice');
        }

        // Récupération des paiements
        $data['payments'] = $this->invoice_model->getPayments($id);

        // Préparation des données pour la vue
        $data['title'] = 'Détails de la facture';
        $data['page_title'] = 'Facture ' . $data['invoice']['invoice_number'];

        // Chargement des vues
        $this->load->view('layout/header', $data);
        $this->load->view('admin/invoice/view', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Affiche le formulaire d'édition d'une facture
     * 
     * @param int $id ID de la facture
     */
    public function edit($id) {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_edit')) {
            access_denied();
        }

        try {
            // Vérifier le statut de la facture
            $invoice = $this->invoice_model->getInvoiceWithItems($id);
            if (!$invoice) {
                $this->session->set_flashdata('error', 'Facture introuvable');
                redirect('admin/invoice');
            }

            if ($this->invoice_model->isPaid($id)) {
                $this->session->set_flashdata('error', 'Cette facture ne peut plus être modifiée car elle est déjà payée');
                redirect('admin/invoice');
            }

            // Préparation des données pour la vue
            $data = [
                'title' => 'Modifier la facture',
                'invoice' => $invoice,
                'clients' => $this->clients_model->get(),
                'itemcatlist' => $this->itemcategory_model->get(),
                'itemList' => $this->item_model->get()
            ];

            // Chargement des vues
            $this->load->view('layout/header', $data);
            $this->load->view('admin/invoice/edit', $data);
            $this->load->view('layout/footer');

        } catch (Exception $e) {
            log_message('error', 'Invoice Edit Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Une erreur est survenue lors de l\'édition de la facture');
            redirect('admin/invoice');
        }
    }

    /**
     * Met à jour une facture existante
     */
    public function update() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_edit')) {
            access_denied();
        }

        $response = ['status' => 'fail', 'message' => '', 'error' => []];

        try {
            // Récupération de l'ID
            $id = $this->input->post('id');
            
            // Vérifier le statut de la facture
            if ($this->invoice_model->isPaid($id)) {
                throw new Exception('Cette facture ne peut plus être modifiée car elle est déjà payée');
            }

            // Validation des champs obligatoires
            $this->form_validation->set_rules('customer', 'Client', 'required|trim');
            $this->form_validation->set_rules('invoice_date', 'Date de facture', 'required');
            $this->form_validation->set_rules('due_date', 'Date d\'échéance', 'required');
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
                'id' => $id,
                'customer_id' => $this->input->post('customer'),
                'invoice_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('invoice_date')))),
                'due_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('due_date')))),
                'payment_method' => $this->input->post('payment_method'),
                'apply_tva' => $this->input->post('apply_tva') ? 1 : 0,
                'tva_rate' => $this->input->post('tva_rate'),
                'notes' => $this->input->post('notes'),
                'items' => []
            ];

            // Calcul des totaux
            $total_ht = 0;
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
                $total_ht += $line_total;

                $data['items'][] = [
                    'category_id' => $category_id,
                    'item_id' => $items[$index],
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'unit' => $units[$index] ?? '',
                    'line_total' => $line_total
                ];
            }

            // Calcul des montants TVA et TTC
            $data['total_ht'] = $total_ht;
            $data['tva_amount'] = $data['apply_tva'] ? ($total_ht * $data['tva_rate'] / 100) : 0;
            $data['total_ttc'] = $total_ht + $data['tva_amount'];

            // Mise à jour des données
            if (!$this->invoice_model->update($data)) {
                throw new Exception('Erreur lors de la mise à jour');
            }

            $response['status'] = 'success';
            $response['message'] = 'La facture a été mise à jour avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Invoice Update Error: ' . $e->getMessage());
        }

        echo json_encode($response);
    }

    /**
     * Supprime une facture
     */
    public function delete() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_delete')) {
            access_denied();
        }

        $response = ['status' => 'fail', 'message' => ''];

        try {
            $id = $this->input->post('id');

            // Vérifier le statut de la facture
            if ($this->invoice_model->isPaid($id)) {
                throw new Exception('Cette facture ne peut pas être supprimée car elle est déjà payée');
            }

            // Suppression de la facture
            if (!$this->invoice_model->delete($id)) {
                throw new Exception('Erreur lors de la suppression');
            }

            $response['status'] = 'success';
            $response['message'] = 'La facture a été supprimée avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Invoice Delete Error: ' . $e->getMessage());
        }

        echo json_encode($response);
    }

    /**
     * Ajoute un paiement à une facture
     */
    public function addPayment() {
        // Vérification des permissions
        if (!$this->rbac->hasPrivilege('invoice', 'can_edit')) {
            access_denied();
        }

        $response = ['status' => 'fail', 'message' => ''];

        try {
            // Validation des champs obligatoires
            $this->form_validation->set_rules('invoice_id', 'Facture', 'required|numeric');
            $this->form_validation->set_rules('amount', 'Montant', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('payment_date', 'Date de paiement', 'required');
            $this->form_validation->set_rules('method', 'Méthode de paiement', 'required');

            if ($this->form_validation->run() == false) {
                $response['error'] = $this->form_validation->error_array();
                echo json_encode($response);
                return;
            }

            // Récupération des données
            $data = [
                'invoice_id' => $this->input->post('invoice_id'),
                'amount' => $this->input->post('amount'),
                'payment_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('payment_date')))),
                'method' => $this->input->post('method'),
                'reference' => $this->input->post('reference'),
                'notes' => $this->input->post('notes'),
                'recorded_by' => $this->session->userdata('admin_id')
            ];

            // Vérification du montant
            $invoice = $this->invoice_model->getInvoiceWithItems($data['invoice_id']);
            if (!$invoice) {
                throw new Exception('Facture introuvable');
            }

            if ($data['amount'] > $invoice['remaining_amount']) {
                throw new Exception('Le montant du paiement ne peut pas être supérieur au montant restant à payer');
            }

            // Ajout du paiement
            if (!$this->invoice_model->addPayment($data)) {
                throw new Exception('Erreur lors de l\'enregistrement du paiement');
            }

            $response['status'] = 'success';
            $response['message'] = 'Le paiement a été enregistré avec succès';

        } catch (Exception $e) {
            $response['message'] = 'Erreur: ' . $e->getMessage();
            log_message('error', 'Payment Add Error: ' . $e->getMessage());
        }

        echo json_encode($response);
    }

    /**
     * Affiche la page d'impression d'une facture
     * 
     * @param int $id ID de la facture
     */
    public function print($id) {
        // Récupération des données de la facture
        $data['invoice'] = $this->invoice_model->getInvoiceWithItems($id);
        
        if (!$data['invoice']) {
            show_404();
            return;
        }

        // Récupération des paiements
        $data['payments'] = $this->invoice_model->getPayments($id);

        // Chargement de la vue d'impression
        $this->load->view('admin/invoice/print', $data);
    }
} 