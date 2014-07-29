<?php
class Survey extends Controller
{
	var $msg = array();
	function Survey()
	{
		parent::__construct();
		$this->load->model('survey_model', 'survey');
 
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
	
	function viewAll()
	{
		$limit = 10;
		$data['surveys'] = $this->survey->listAll($limit, $this->uri->segment(4));
		$totalSurvey = $this->survey->totalSurvey();
		
		// phân trang
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/survey/viewall");
        $config['total_rows'] = $totalSurvey;
        $config['uri_segment'] = 4;
        $config['per_page'] =$limit;
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
        
        if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$this->load->view('admin/header');
		$this->load->view('admin/survey', $data);
		$this->load->view('admin/footer');	
	}
	
	/*
	 * thêm mới thăm dò
	 */
	function add()
	{
		$data = array();
		if(isset($_POST['insert'])) {
			unset($_POST['insert']);
			foreach($_POST as $key=>$value)
				if($value == '') unset($_POST[$key]);
			$_POST['create_date'] = microtime(true);
			$this->survey->add($_POST);
			$data['success'] = 'Thêm mới thăm dò thành công';	
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/surveyadd', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * chỉnh sửa thăm dò
	 */
	function edit($id)
	{
		if(isset($_POST['edit'])) {
			unset($_POST['edit']);
			foreach($_POST as $key=>$value)
				if($value == '') unset($_POST[$key]);
			$this->survey->update($id, $_POST);
			$data['success'] ='Cập nhật thăm dò thành công';				
		}
		
		$survey = $this->survey->getInfo($id);
		$data['survey'] = $survey;
		$this->load->view('admin/header');
		$this->load->view('admin/surveyedit', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * xóa thăm dò
	 */
	function delete($id)
	{
		$this->survey->delete($id);
		$this->msg['success'] = 'Xóa thăm dò thành công';
		redirect('admin/survey');
	}
}