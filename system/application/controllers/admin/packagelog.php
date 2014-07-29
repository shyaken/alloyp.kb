<?php
class Packagelog extends Controller {
    private $_store = array(
        'http://appstore.vn/' => '0',
        'http://appstore.vn/a/' => 'a',
        'http://appstore.vn/b/' => 'b',
        'http://appstore.vn/e/' => 'e',
        'http://appstore.vn/f/' => 'f',
        'http://appstore.vn/i/' => 'i'
        
    );
    function Packagelog() {
        parent::__construct();
        $this->load->model('user_model');
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
    
    function index() {
        $this->viewAll();
    }
    
    function viewAll($sort = 'id', $order = 'DESC', $store = '0', $user_id = '0', $package_type = '0', $starttime = '0', $endtime = '0', $limit = '20', $start = '0') 
	{
		if($store == '0') {
            $store = $this->_store[base_url()];
        }
		$start_store = $starttime;
		$end_store = $endtime;
		
		if($starttime != '0') {
			$starttime = strtotime(str_replace('_', '/', $starttime) . '00:00:00');
			$endtime = strtotime(str_replace('_', '/', $endtime) . '23:59:59');
		}
		
		$filter = array(
			'sort' => $sort,
			'order' => $order,
            'store' => $store,
			'user_id' => $user_id,
			'package_type' => $package_type,
			'starttime'	=> $starttime,
			'endtime' => $endtime,
			'limit' => $limit,
			'start' => $start
		);
				
		$totalLogs = $this->user_model->totalPackageLog($filter);
		$packages = $this->user_model->allPackageLog($filter);

		if($starttime == '0') {
			$data['info'] = 'Thống kê tất cả logs đăng kí gói cho đến hiện tại';
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $starttime) . ' đến ' . date('d/m/Y', $endtime) . '(<font color="green">dd/mm/yyyy</font>)';
		}
		$data['totalPackageUser'] = $this->user_model->totalPackageUser($filter);
		$data['totalPackageTym'] = $this->user_model->totalPackageTym($filter);
		$data['packages'] = $packages;
		$data['totalLogs'] = $totalLogs;
		$data['user_id'] = $user_id;
        $data['package_type'] = $package_type;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['store'] = $store;
        $data['stores'] = $this->_store;
		
		// phân trang cho danh sách download
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/packagelog/viewAll/$sort/$order/$store/$user_id/$package_type/$start_store/$end_store/$limit/");
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

        $this->load->view('admin/header');
        $this->load->view('admin/packagelog', $data);
        $this->load->view('admin/footer');
	}    
}
?>
