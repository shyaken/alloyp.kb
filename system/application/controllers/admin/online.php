<?php
class Online extends Controller
{
	function Online()
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
	}
	
	function index() 
	{
		$this->viewAll();
	}
	
	function f5() {
		$this->user->refreshOnlineUser();
		redirect('admin/online');
	}
	
	function viewAll($agent = '0', $limit = '100', $start = '0')
	{
		$filter = array(
			'agent' => $agent,
			'start'	=> $start,
			'limit' => $limit
		);
		
		if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$totalOnline = $this->user->totalFilterOnline($filter);
		$onlines = $this->user->allFilterOnline($filter);
		
		$data['totalOnline'] = $totalOnline;
		$data['onlines'] = $onlines;
		$data['agent'] = $agent;
		$data['limit'] = $limit;
		$data['start'] = $start;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/online/viewall/$agent/$limit/");
        $config['total_rows'] = $totalOnline;
        $config['uri_segment'] = 6;
        $config['per_page'] = $limit;
        $config['full_tag_open'] = '<div class="pagination"><div class="links">';
    	$config['full_tag_close'] = '</div></div>';
		$config['cur_tag_open'] = '<span class="active curved">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';
        $this->pagination->initialize($config);		
        
		$this->load->view('admin/header');
		$this->load->view('admin/online', $data);
		$this->load->view('admin/footer');
	}
}