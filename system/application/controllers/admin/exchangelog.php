<?php
class Exchangelog extends Controller
{
	function Exchangelog()
	{
		parent::__construct();

        $this->load->model('user_model', 'user');
        $this->load->model('exchange_model');

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
        
        $this->load->model('useraction_model', 'action');
	}
	
	function index() 
    {
        $this->viewAll();
    }
        
    function viewAll($sortby = 'id', $order = 'DESC', $startdate = '0', $enddate = '0', $userid = '-1', $username = '-1', $tx_type = '-1', $limit = '50', $start = '0') 
	{
		$startdate_ = $startdate;
		$enddate_ = $enddate;
		if($startdate != '0') {
			$startdate = strtotime(str_replace('_', '/', $startdate) . '00:00:00');
			$enddate = strtotime(str_replace('_', '/', $enddate) . '23:59:59');
		}
		
		if($startdate == '0') {
			$data['info'] = 'Thống kê tất cả  cho đến hiện tại';
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $startdate) . ' đến ' . date('d/m/Y', $enddate) . '(<font color="green">dd/mm/yyyy</font>)';
		}
		
		$filter = array(
			'startdate' => $startdate,
			'enddate' => $enddate,
			'user_id' => $userid,
			'username' => $username,
            'tx_type' => $tx_type,
			'start'	=> $start,
			'limit' => $limit,
			'sortby' => $sortby,
			'order' => $order
		);
		
		if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$totalLogs = $this->exchange_model->totalFilterLog($filter);
		$logs = $this->exchange_model->allFilterLog($filter);
		
		$data['totalLogs'] = $totalLogs;
		$data['startdate'] = $startdate_;
		$data['enddate'] = $enddate_;
		$data['logs'] = $logs;
        $data['sort'] = $sortby;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['userid'] = $userid;
        $data['username'] = $username;
        $data['tx_type'] = $tx_type;
        $data['limit'] = $limit;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/exchangelog/viewall/$sortby/$order/$startdate_/$enddate_/$userid/$username/$tx_type/$limit");
        $config['total_rows'] = $totalLogs;
        $config['uri_segment'] = 12;
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
        
        
        $data['url_1'] = site_url("admin/exchangelog/viewall/$sortby/$order/$startdate_/$enddate_/$userid/$username/$tx_type/");
        $data['url_2'] = $limit;
        $data['url_3'] = $start;
        
		$this->load->view('admin/header');
		$this->load->view('admin/exchangelog', $data);
		$this->load->view('admin/footer');
	}
    
    function detail($id) {
        if(!is_numeric($id)) $id = 1;
        $data['log'] = $this->exchange_model->logDetail($id);
        $this->load->view('admin/header');
        $this->load->view('admin/exchangelogdetail', $data);
        $this->load->view('admin/footer');
    }
}