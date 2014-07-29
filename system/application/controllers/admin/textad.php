<?php
class Textad extends Controller
{
	function Textad()
	{
		parent::__construct();
		$this->load->model('textad_model', 'textad');

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
		$data['texts'] = $this->textad->listAll();
		
		$this->load->view('admin/header');
		$this->load->view('admin/textad', $data);
		$this->load->view('admin/footer');
	}
	
	function add()
	{
		$data = array();
		if(isset($_POST['insert'])) {
			$google = array(
				'name' => 'Google Adsense',
				'type' => 'googlead',
				'code' => $this->input->post('googlead') 
			);
			$headertext = array(
				'name' => 'Header Text',
				'type' => 'headertext',
				'code' => $this->input->post('headertext') 
			);
			$footertext = array(
				'name' => 'Footer Text',
				'type' => 'footertext',
				'code' => $this->input->post('footertext') 
			);
			
			$this->textad->add($google);
			$this->textad->add($headertext);
			$this->textad->add($footertext);
			$data['success'] = 'Thêm mới thành công!';
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/textadadd', $data);
		$this->load->view('admin/footer');
	}
	
	function edit()
	{
		$data = array();
		
		if(isset($_POST['update'])) {
			$google = array(
				'name' => 'Google Adsense',
				'type' => 'googlead',
				'code' => $this->input->post('googlead') 
			);
			$headertext = array(
				'name' => 'Header Text',
				'type' => 'headertext',
				'code' => $this->input->post('headertext') 
			);
			$footertext = array(
				'name' => 'Footer Text',
				'type' => 'footertext',
				'code' => $this->input->post('footertext') 
			);
			
			$this->textad->edit($_POST['id'][0], $google);
			$this->textad->edit($_POST['id'][1], $headertext);
			$this->textad->edit($_POST['id'][2], $footertext);
			$data['success'] = 'Chỉnh sửa thành công!';
            //xoa cache
            $this->load->library('Cache', 'cache');
            $this->cache->flush();
		}
		
		$data['texts'] = $this->textad->listAll();		
		$this->load->view('admin/header');
		$this->load->view('admin/textadedit', $data);
		$this->load->view('admin/footer');
	}
	
	function delete()
	{
		$this->textad->delete();
		redirect('admin/textad');
	}
}