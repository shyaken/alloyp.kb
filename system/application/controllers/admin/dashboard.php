<?php
class Dashboard extends Controller {
	function Dashboard() {
		parent::Controller();
        $base_url = $this->session->userdata('base_url');
        $is_admin = $this->session->userdata('is_admin');
        $is_root = $this->session->userdata('is_root');
        
        if($base_url != base_url() && $is_root != 'yes') {
            redirect('admin/login/logout');
        }
        if($is_admin != 'yes') {
            redirect('admin/login');
        }
	}

	function index() {
        $this->load->view('admin/header');
		$this->load->view('admin/dashboard');
        $this->load->view('admin/footer');
	}
    
    function changePassword() {
        $curPass = $this->input->post('curPass');
        $newPass = $this->input->post('newPass');
        $renewPass = $this->input->post('renewPass');
        $adminId = $this->session->userdata('admin_id');
        $this->load->model('admin_model');
        $result = $this->admin_model->changePassword($adminId, $curPass, $newPass);
        echo $result;
    }
	
    function flushCache() {
        $this->load->library('Cache', 'cache');
        $this->cache->flush();
        redirect('admin/managerapp');
    }
}
