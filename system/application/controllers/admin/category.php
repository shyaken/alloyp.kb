<?php
class Category extends Controller
{
	private $msg = array();
	
	function Category()
	{
		parent::__construct();
		$this->load->model('category_model', 'category');

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
	 * view all
	 */
	function viewAll()
	{
		$data['cats'] = $this->category->listAll();
		$data['totalCat'] = $this->category->totalCat();
		
		if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$this->load->view('admin/header');
		$this->load->view('admin/category', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * add
	 */
	function add()
	{
		$data = array();
		if(isset($_POST['insert'])) {
			$oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/category/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            $original = '/' . UPLOADFOLDER . '/category/' . $cur . '/original/';
            if(!is_dir('.' . $original)) mkdir('.' . $original);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('thumbnail')) {
				$image = '/' . UPLOADFOLDER . '/category/default.jpg';
			} else {
				$upload = $this->upload->data();
				$image = $path . $upload['file_name'];
                //copy anh goc vao thu muc orginal
                copy('.' . $image, '.' . $original . $upload['file_name']);
                $imagesize = getimagesize('.'.$image);
                //Load thư viện xử lý ảnh để tạo thumbnail
                $this->load->library('image_lib');
                $config['source_image']	= '.'.$image;
                $config['width']    = 80;
                $config['height']   = 80;
                $config['quality']  = '100%';
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
			}
			
			unset($_POST['insert']);
			$_POST['image'] = $image;
			$this->category->add($_POST);
			$data['success'] = 'Thêm mới category thành công';
		}
		$this->load->view('admin/header');
		$this->load->view('admin/categoryadd', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * delete
	 */
	function delete()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id)
				$this->category->delete($id);
			$this->msg['success'] = 'Đã xóa category (s) thành công';	
		}
		$this->viewAll();
	}
	
	/*
	 * publish
	 */
	function publish()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id)
				$this->category->publish($id);
			$this->msg['success'] = 'Đã bật category (s) thành công';	
		}
		$this->viewAll();
	}	
	
	/*
	 * unpublish
	 */
	function unPublish()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id)
				$this->category->unPublish($id);
			$this->msg['success'] = 'Đã tắt category (s) thành công';	
		}
		$this->viewAll();
	}	
	
	// nhận request từ publishID() ở view admin/category
	function publishID() 
	{
		$category_id = $this->input->post('category_id');
		$value = $this->input->post('value');
		
		if($value == 1) { 
			$this->category->publish($category_id);
			echo '<a href="javascript:;" onclick="publishID(' . $category_id . ',0);">Bật</a>';			
		} else  {
			$this->category->unpublish($category_id);
			echo '<a href="javascript:;" onclick="publishID(' . $category_id . ',1);">Tắt</a>';
		}
	}		
	
	/*
	 * edit
	 */
	function edit($id)
	{
		if(isset($_POST['update'])) {
            $oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/category/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            $original = '/' . UPLOADFOLDER . '/category/' . $cur . '/original/';
            if(!is_dir('.' . $original)) mkdir('.' . $original);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('thumbnail')) {
				$data['error'] = 'Ảnh đại diện upload không thành công';
			} else {
				$upload = $this->upload->data();
				$image = $path . $upload['file_name'];
				$_POST['image'] = $image;
                //copy anh goc vao thu muc orginal
                copy('.' . $image, '.' . $original . $upload['file_name']);
                $imagesize = getimagesize('.'.$image);
                //Load thư viện xử lý ảnh để tạo thumbnail
                $this->load->library('image_lib');
                $config['source_image']	= '.'.$image;
                $config['width']    = 80;
                $config['height']   = 80;
                $config['quality']  = '100%';
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
			}	
			
			unset($_POST['update']);
			$this->category->update($id, $_POST);
			$data['success'] = 'Cập nhật thành công';
		}
		
		$data['id'] = $id;
		$data['category'] = $this->category->getInfo($id);
		$this->load->view('admin/header');
		$this->load->view('admin/categoryedit', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * lưu order
	 */
	function saveOrder()
	{
		if(isset($_POST['id'])) {
			for($i=0; $i<count($_POST['id']); $i++) {
				$this->category->saveOrder($_POST['id'][$i], $_POST['order'][$i]);
			}
			$this->msg['success'] = 'Đã thứ tự category (s) thành công';
		}
		$this->viewAll();
	}
        
        /*
         * cập nhật link ảnh của category = link ảnh của application cuối cùng trong category đó
         */
        function updateImage()
        {
            $this->category->updateImage();
            $this->msg['success'] = 'Đã cập nhật ảnh cho category';
            $this->viewAll();
        }
}
