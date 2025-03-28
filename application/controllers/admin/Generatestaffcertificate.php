<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generatestaffcertificate extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();

    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('generate_staff_id_card', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatestaffcertificate');
        $idcardlist            = $this->Generatestaffidcard_model->getstaffidcard();
        $data['idcardlist']    = $idcardlist;
        $staffRole             = $this->staff_model->getStaffRole();
        $data['staffRolelist'] = $staffRole;
        $this->load->view('layout/header');
        $this->load->view('admin/generatestaffcertificate/generatestaffcertificateview', $data);
        $this->load->view('layout/footer');
    }

    public function search()
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatestaffcertificate');
        $staffRole               = $this->staff_model->getStaffRole();
        $data['staffRolelist']   = $staffRole;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $idcardlist              = $this->Generatestaffidcard_model->getstaffidcard();
        $data['idcardlist']      = $idcardlist;
        $this->form_validation->set_rules('id_card', $this->lang->line('id_card_template'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $role                 = $this->input->post('role_id');
            $data['role_id']      = $this->input->post('role_id');
            $id_card              = $this->input->post('id_card');
            $idcardResult         = $this->Generatestaffidcard_model->getidcardbyid($id_card);
            $data['idcardResult'] = $idcardResult;
            $resultlist           = $this->staff_model->getEmployee($role, 1);
            $data['resultlist']   = $resultlist;
        }

        $this->load->view('layout/header');
        $this->load->view('admin/generatestaffcertificate/generatestaffcertificateview', $data);
        $this->load->view('layout/footer');
    }

    public function generatemultiple()
    {
        $staffid             = $this->input->post('data');
        $staff_array         = json_decode($staffid);
        $idcard              = $this->input->post('id_card');
        $staffid_arr         = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['id_card']     = $this->Generatestaffidcard_model->getidcardbyid($idcard);
        foreach ($staff_array as $key => $value) {
            $staffid_arr[] = $value->staff_id;
        }
        $data['staffs'] = $this->Generatestaffidcard_model->getEmployee($staffid_arr, 1);
        $id_cards       = $this->load->view('admin/generatestaffcertificate/generatemultiplestaffcertificate', $data, true);
        echo $id_cards;
    }
}
