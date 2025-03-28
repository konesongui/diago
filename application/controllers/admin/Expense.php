<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expense extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->config->load('app-config');
        $this->load->library("datatables");
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('depense', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Caisse');
        $this->session->set_userdata('sub_menu', 'expense/index');
        $data['title']      = 'Add Expense';
        $data['title_list'] = 'Recent Expenses';
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {

        } else {

            $oldData = $this->income_model->read('*', ['id' => $this->input->post('inc_head_id')]);
            $incomeAmountAvaible = (float)$oldData->amount_re??0 ;
            $incomeId = (int)$this->input->post('inc_head_id')??0 ;

            $amount = $this->input->post('amount') ? floatval($this->input->post('amount')) : 0;

            if($incomeAmountAvaible >= $amount){
                $newIncomeAmountAvaible = $incomeAmountAvaible - $amount;

                // var_dump($this->input->post('inc_head_id'));
                // var_dump($old_data);
                // var_dump($incomeAmountAvaible);
                // var_dump($amount);
                // exit;

                $data = array(
                    'exp_head_id' => $this->input->post('exp_head_id'),
                    'inc_head_id' => $this->input->post('inc_head_id'),
                    'user'  => $this->input->post('user'),
                    'name'        => $this->input->post('name'),
                    'date'        => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'amount'      => $this->input->post('amount'),
                    'invoice_no'  => $this->input->post('invoice_no'),
                    'note'        => $this->input->post('description'),
                );

                $insert_id = $this->expense_model->add($data);

                // Mise à jour de la ligne dans la base de données
                $this->income_model->updateP(['id' => $incomeId], [
                    'amount_re' => $newIncomeAmountAvaible
                ]);

                // Mise à jour de la ligne dans la base de données
                $this->Income_processing_model->createP([
                    'income_id' => $incomeId,
                    'amount'    => -$amount,
                    'reason'    => "Expense"
                ]);

                if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
                    $fileInfo = pathinfo($_FILES["documents"]["name"]);
                    $img_name = $insert_id . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["documents"]["tmp_name"], "./uploads/school_expense/" . $img_name);
                    $data_img = array('id' => $insert_id, 'documents' => 'uploads/school_expense/' . $img_name);

                    $this->expense_model->add($data_img);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/expense/index');
            }else{

                $this->session->set_flashdata('msg', '<div class="alert alert-warning text-left">Le solde de la caisse sélectionnée est insuffisant</div>');
                redirect('admin/expense/index');
            }
        }

        // var_dump($this->input->post('amount'));
        // exit;


        $expense_result      = $this->expense_model->get();
        $data['expenseTotal'] = $this->expense_model->getTotalExpense();
        $data['expenselist'] = $expense_result;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $income_result = $this->income_model->get();
        $data['incomelist'] = $income_result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($documents)
    {
        $this->load->helper('download');
        $filepath = "./uploads/school_expense/" . $this->uri->segment(6);
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(6);
        force_download($name, $data);
    }

    public function handle_upload()
    {

        $image_validate = $this->config->item('file_validate');
        $result = $this->filetype_model->get();
        if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
            $file_type         = $_FILES["documents"]['type'];
            $file_size         = $_FILES["documents"]["size"];
            $file_name         = $_FILES["documents"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['documents']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('depense', 'can_view')) {
            access_denied();
        }
        $data['title']   = 'Fees Master List';
        $expense         = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $this->load->view('layout/header', $data);
        $this->load->view('expense/expenseShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getByFeecategory()
    {
        $feecategory_id = $this->input->get('feecategory_id');
        $data           = $this->feetype_model->getTypeByFeecategory($feecategory_id);
        echo json_encode($data);
    }

    public function getStudentCategoryFee()
    {
        $type     = $this->input->post('type');
        $class_id = $this->input->post('class_id');
        $data     = $this->expense_model->getTypeByFeecategory($type, $class_id);
        if (empty($data)) {
            $status = 'fail';
        } else {
            $status = 'success';
        }
        $array = array('status' => $status, 'data' => $data);
        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('depense', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->expense_model->remove($id);
        redirect('admin/expense/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('depense', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Fees Master';
        $this->form_validation->set_rules('expense', $this->lang->line('fees_master'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('expense/expenseCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'expense' => $this->input->post('expense'),
            );
            $this->expense_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('expense/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('depense', 'can_edit')) {
            access_denied();
        }
        $data['title']       = 'Edit Fees Master';
        $data['id']          = $id;
        $expense             = $this->expense_model->get($id);
        $data['expense']     = $expense;
        $data['title_list']  = 'Fees Master List';
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/expense/expenseEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'exp_head_id' => $this->input->post('exp_head_id'),
                'name'        => $this->input->post('name'),
                'invoice_no'  => $this->input->post('invoice_no'),
                'date'        => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount'      => $this->input->post('amount'),
                'note'        => $this->input->post('description'),
            );
            $insert_id = $this->expense_model->add($data);
            if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
                $fileInfo = pathinfo($_FILES["documents"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["documents"]["tmp_name"], "./uploads/school_expense/" . $img_name);
                $data_img = array('id' => $id, 'documents' => 'uploads/school_expense/' . $img_name);
                $this->expense_model->add($data_img);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/expense/index');
        }
    }

    public function expenseSearch()
    {
        if (!$this->rbac->hasPrivilege('categorie_depense', 'can_view')) {
            access_denied();
        }
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['search_type'] = '';
        $this->session->set_userdata('top_menu', 'Depense');
        $this->session->set_userdata('sub_menu', 'expense/expensesearch');
        $data['title'] = 'Search Expense';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseSearch', $data);
        $this->load->view('layout/footer', $data);

    }
    public function getexpenselist()
    {
        $m       = $this->expense_model->getexpenselist();
        $m       = json_decode($m);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();

        // var_dump($m);
        // exit;

        $dt_data = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $documents = '' ;

                if ($this->rbac->hasPrivilege('depense', 'can_edit')) {
                    $editbtn = "<a href='".base_url()."admin/expense/edit/".$value->id."'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('depense', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . $this->lang->line('delete_confirm') . ")' href='".base_url()."admin/expense/delete/".$value->id."' class='btn btn-default btn-xs' data-placement='left' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }


                if($value->documents){
                    $documents="<a data-placement='left' href='".base_url()."admin/expense/download/".$value->documents."' class='btn btn-default btn-xs'  data-toggle='tooltip' title='".$this->lang->line('download')."'>
                         <i class='fa fa-download'></i> </a>" ;
                }
                $row       = array();
                $row[]     = $value->name;

               /* if ($value->note == "") {
                     $row[]     = $this->lang->line('no_description');
                 }else{
                     $row[]     = $value->note;
                }*/

               /* $row[]     = $value->invoice_no; */
                $row[]     = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]     = $value->exp_category;
                $row[]     = $value->income_name;
                $row[]     = $currency_symbol . $value->amount;
                /*$row[]     = $value->income_user;*/
                $row[]     = $documents.' ' .$editbtn . ' ' . $deletebtn;
                $dt_data[] = $row;

                //Ajouter sous total
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }
    /*-----------------function to check search validation for admission report ---*/

    public function search()
    {
        $button_type = $this->input->post('button_type');

        if ($button_type == "search_filter") {

            $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'required|trim|xss_clean');
        } elseif ($button_type == "search_full") {

            $this->form_validation->set_rules('search_text', $this->lang->line('keyword'), 'required|trim|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $error = array();
            if ($button_type == "search_filter") {
                $error['search_type'] = form_error('search_type');
            } elseif ($button_type == "search_full") {
                $error['search_text'] = form_error('search_text');
            }

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $button_type = $this->input->post('button_type');
            $search_text = $this->input->post('search_text');
            $date_from="" ;
            $date_to="" ;

            $search_type = $this->input->post('search_type');
            if($search_type=='period'){
            $date_from= $this->input->post('date_from');
            $date_to= $this->input->post('date_to');
            }

            $params      = array('button_type' => $button_type, 'search_type'=>$search_type, 'search_text' => $search_text,'date_from' => $date_from, 'date_to' => $date_to);
            $array       = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchexpenselist()
    {
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $search_text = $this->input->post('search_text');

        if($button_type=='search_filter'){
            if ($search_type!="") {

                if ($search_type == 'all') {

                    $dates = $this->customlib->get_betweendate('this_year');
                } else {
                    $dates = $this->customlib->get_betweendate($search_type);
                }
            } else {
                $dates               = $this->customlib->get_betweendate('this_year');
                $search_type = '';
            }

            $dateformat = $this->customlib->getSchoolDateFormat();
            $date_from         = date('Y-m-d', strtotime($dates['from_date']));
            $date_to           = date('Y-m-d', strtotime($dates['to_date']));
            $data['exp_title'] = 'Expense Result From ' . date($dateformat, strtotime($date_from)) . " To " . date($dateformat, strtotime($date_to));
            $date_from         = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_from));
            $date_to           = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_to));
            $resultList         = $this->expense_model->search("", $date_from, $date_to);

        }else{

            $search_text        = $this->input->post('search_text');
            $resultList         = $this->expense_model->search($search_text, "", "");
            $resultList= $resultList;
        }


        $m       = json_decode($resultList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data = array();$grand_total=0;
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $grand_total+= $value->amount ;
                $row       = array();
                $row[]     = $value->name;
                $row[]     = $value->invoice_no;
                $row[]     = $value->exp_category;
                $row[]     = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]     = $currency_symbol . $value->amount;

                $dt_data[] = $row;
            }

            $footer_row[]="" ; $footer_row[]="" ; $footer_row[]="" ;
            $footer_row[]= "" ;
            $footer_row[]=  "<b style='font-weight:normal'>".$this->lang->line('grand_total')." :  ".($currency_symbol . number_format($grand_total, 2, '.', ''))."</b>";;
            $dt_data[] = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }
}
