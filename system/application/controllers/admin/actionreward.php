<?php
class Actionreward extends Controller
{
	function Actionreward()
	{
		parent::__construct();

        $this->load->model('user_model', 'user');

        $base_url = $this->session->userdata('base_url');
        $is_admin = $this->session->userdata('is_admin');
        $is_root = $this->session->userdata('is_root');
        
        if($base_url != base_url() && $is_root != 'yes') {
            redirect('admin/login/logout');
        }
        if($is_admin != 'yes') {
            redirect('admin/login');
        }
	    $controller = $this->router->fetch_class();
        $permission = $this->session->userdata('permission');
        $permissions = explode('|', $permission);
            if(!in_array($controller, $permissions) && $is_root != 'yes') {
            	show_error("You haven't permission to view this page!");
            }   
        if($this->session->userdata('is_root') != 'yes') {
            redirect('admin/dashboard');
        }            

        $this->load->model('useraction_model', 'action');
	}
	
	function index()
	{
		$data['actionrewards'] = $this->action->listAction();
		
		$this->load->view('admin/header');
		$this->load->view('admin/actionreward', $data);
		$this->load->view('admin/footer');
		
	}
	
	/*
	 * edit
	 */
	function edit($id)
	{
		if(isset($_POST['edit'])) {
			$params = array(
				't1' => $this->input->post('t1'),
				't2' => $this->input->post('t2'),
				't3' => $this->input->post('t3'),
				't4' => $this->input->post('t4'),
				'enable' => $this->input->post('enable')
			);
			
			$this->action->updateAction($id, $params);
			$data['success'] = 'Đã cập nhật thành công';
		}
		
		$data['actionreward'] = $this->action->getAction($id);
		$this->load->view('admin/header');
		$this->load->view('admin/actionrewardedit', $data);
		$this->load->view('admin/footer');
	}
}