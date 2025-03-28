<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class   projet extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('projet', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Service');
        $this->session->set_userdata('sub_menu', 'Service/projet');
        $data['title'] = 'Add subject';
        $subject_result = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $data['subject_types'] = $this->customlib->subjectType();
        $this->form_validation->set_rules('name', $this->lang->line('subject_name'), 'trim|required|xss_clean|callback__check_name_exists');
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->input->post('code')) {
            $this->form_validation->set_rules('code', $this->lang->line('code'), 'trim|required|callback__check_code_exists');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/projet/projetList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'startdate' => $this->input->post('startdate'),
                'enddate' => $this->input->post('enddate'),
                'description' => $this->input->post('description'),
                'type' => $this->input->post('type'),
            );
            $this->subject_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/projet/index');
        }
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('projet', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Subject List';
        $subject = $this->subject_model->get($id);
        $data['subject'] = $subject;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/projet/subjectShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('projet', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Subject List';
        $this->subject_model->remove($id);
        redirect('admin/projet/index');
    }

    function _check_name_exists() {
        $data['name'] = $this->security->xss_clean($this->input->post('name'));
        if ($this->subject_model->check_data_exists($data)) {
            $this->form_validation->set_message('_check_name_exists', $this->lang->line('name_already_exists'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function _check_code_exists() {
        $data['code'] = $this->security->xss_clean($this->input->post('code'));
        if ($this->subject_model->check_code_exists($data)) {
            $this->form_validation->set_message('_check_code_exists', $this->lang->line('code_already_exists'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('projet', 'can_edit')) {
            access_denied();
        }
        $subject_result = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $data['title'] = 'Edit Subject';
        $data['id'] = $id;
        $subject = $this->subject_model->get($id);
        $data['subject'] = $subject;
        $data['subject_types'] = $this->customlib->subjectType();
        $this->form_validation->set_rules('name', $this->lang->line('subject'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/projet/projetEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'startdate' => $this->input->post('startdate'),
                'enddate' => $this->input->post('enddate'),
                'description' => $this->input->post('description'),
                'type' => $this->input->post('type'),
            );
            $this->subject_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/projet/index');
        }
    }

    function getSubjctByClassandSection() {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
        echo json_encode($data);
    }

}

?>