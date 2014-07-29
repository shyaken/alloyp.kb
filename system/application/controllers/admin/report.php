<?php
class Report extends Controller {
	function Report() {
		parent::__construct();
		$this->load->model('app_model', 'app');
		
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
	
	/*
	 * view all reports
	 */
	function viewAll($sort = 'report_id', $order = 'DESC', $code = '-1', $user_id = '0', $app_id = '0', $fixed = '-1', $limit = '25', $start = '0') {
		$this->load->view('admin/header');
		
		$filter = array(
			'sort' => $sort,
			'order' => $order,
			'code' => $code,
			'user_id' => $user_id,
			'app_id' => $app_id,
			'fixed' => $fixed,
			'limit' => $limit,
			'start' => $start
		);		
		$totalReport = $this->app->totalFilterReport($filter);
		$data['totalReport'] = $totalReport;
		$data['reports'] = $this->app->allFilterReport($filter);
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;
		
		$reasons = array(
    		'Dung lượng tải về không đủ',
    		'Chưa có Version/tập mới nhất',
    		'Tải về ok, cài đặt bị lỗi',
    		'Lỗi không tìm thấy file',
    		'Sai mô tả nội dung',
    		'Nội dung nhạy cảm, cần kiểm duyệt lại',
    		'Phần mềm chất lượng kém, lừa đảo'
    	);
    	$data['reasons'] = $reasons;
		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/report/viewAll/$sort/$order/$code/$user_id/$app_id/$fixed/$limit");
        $config['total_rows'] = $totalReport;
        $config['uri_segment'] = 11;
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
		
		$this->load->view('admin/report', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * cập nhật đã sửa lỗi chưa
	 * gọi từ hàm updateFixed() từ view report.php 
	 */
	function updateFixed($report_id, $status) {
		$data = array('fixed' => $status);
		$this->app->updateReport($report_id, $data);
		$txt = 'Chưa';
		if($status) $txt = 'Rồi';
		echo '<a href="javascript:void(0);" onclick="updateFixed(' . $report_id . ', ' . $status . ')">' . $txt . '</a>';
	}
}