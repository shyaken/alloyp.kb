<?php
class Statistic extends Controller
{
	function Statistic()
	{
		parent::__construct();
		$this->load->model('statistic_model', 'statistic');

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
	
	/*
	 * gọi từ hàm listDownload() trong view admin/statistic
	 */
    function viewAll($sort = 'id', $order = 'DESC', $user_id = '0', $app_id = '0', $vendor = '0', $tym_type = '0', $price = '0', $starttime = '0', $endtime = '0', $limit = '20', $start = '0') 
	{
		
		$start_store = $starttime;
		$end_store = $endtime;
		
		if($starttime != '0') {
			$starttime = strtotime(str_replace('_', '/', $starttime) . '00:00:00');
			$endtime = strtotime(str_replace('_', '/', $endtime) . '23:59:59');
		}
		
		$filter = array(
			'sort' => $sort,
			'order' => $order,
			'user_id' => $user_id,
			'app_id' => $app_id,
            'vendor' => $vendor,
            'tym_type' => $tym_type,
            'price' => $price,
			'starttime'	=> $starttime,
			'endtime' => $endtime,
			'limit' => $limit,
			'start' => $start
		);
				
		$totalMoney = $this->statistic->totalMoney($filter);
		$totalDownload = $this->statistic->totalDownload($filter);
		$downloads = $this->statistic->getAllDownload($filter);

		if($starttime == '0') {
			$data['info'] = 'Thống kê tất cả download cho đến hiện tại';
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $starttime) . ' đến ' . date('d/m/Y', $endtime) . '(<font color="green">dd/mm/yyyy</font>)';
		}
		$data['downloads'] = $downloads;
		$data['totalDownload'] = $totalDownload;
		$data['totalMoney'] = $totalMoney;
		$data['app_id'] = $app_id;
		$data['user_id'] = $user_id;
        $data['vendor'] = $vendor;
        $data['order'] = $order;
        $data['sort'] = $sort;
        $data['tym_type'] = $tym_type;
        $data['price'] = $price;
		
		// phân trang cho danh sách download
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/statistic/viewAll/$sort/$order/$user_id/$app_id/$vendor/$tym_type/$price/$start_store/$end_store/$limit/");
        $config['total_rows'] = $totalDownload;
        $config['uri_segment'] = 14;
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
        $this->load->view('admin/statistic', $data);
        $this->load->view('admin/footer');
	}
	
	function export($app_id = '0', $user_id = '0', $starttime = '0', $endtime = '0', $type = '0')
	{
		if($starttime != '0') {
			$starttime = strtotime(str_replace('_', '/', $starttime) . '00:00:00');
			$endtime = strtotime(str_replace('_', '/', $endtime) . '23:59:59');
		}
		
		$filter = array(
			'app_id' => $app_id,
			'user_id' => $user_id,
			'start' => $starttime,
			'end' => $endtime
		);	

		$totalMoney = $this->statistic->totalMoney($filter);
		$totalDownload = $this->statistic->totalDownload($filter);
		$downloads = $this->statistic->getAllDownload($filter);
		
		if($starttime == '0') {
			$data['info'] = 'Thống kê tất cả download cho đến hiện tại';
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $starttime) . ' đến ' . date('d/m/Y', $endtime) . '(<font color="green">dd/mm/yyyy</font>)';
		}
		
		$data['totalMoney'] = $totalMoney;
		$data['totalDownload'] = $totalDownload;
		$data['downloads'] = $downloads; 
		
		if($type == 'excel') {
			$this->load->view('admin/statisticexportexcel', $data);
		} else {
			$data = $this->statistic->exportCSV($filter);
			echo $data;
		}
		
	}
}