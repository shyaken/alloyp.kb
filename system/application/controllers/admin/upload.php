<?php
class Upload extends Controller
{
	function Upload()
	{
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
	
	function index()
	{
		$data = array();

		$status = array();
		
		// upload application & insert db
		if(isset($_POST['insert'])) {
            $oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/thumbnails/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            $original = '/' . UPLOADFOLDER . '/thumbnails/' . $cur . '/original/';
            if(!is_dir('.' . $original)) mkdir('.' . $original);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif|jpeg';
			$this->load->library('upload', $config);
			
			$image = $this->input->post('thumbnail_crawler');
			
			if($this->upload->do_upload('thumbnail')) {
				$image_data = $this->upload->data();
				$image = $path. $image_data['file_name'];
                //copy anh goc vao thu muc orginal
                copy('.' . $image, '.' . $original . $image_data['file_name']);
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
            
            $categoryUpdate = array('image' => $image);
            $this->load->model('category_model');
            $this->category_model->update($this->input->post('category'), $categoryUpdate);
			
			/*
			 * screenshot
			 */
			
			$screenshots = array();
			if($this->input->post('optiontype') == 'app') {
				$i = 0;
				if(isset($_POST['crawler_sc_link'])) {
					foreach($_POST['crawler_sc_link'] as $link) {
						if(isset($_POST['crawler_sc' . $i])) {
							$screenshots[] = $link;
						} 
						$i++;
					}
				}
			}
			
			$num_morescreenshot = $this->input->post('num_morescreenshot');
            $oldUmask = umask();
            umask(0);
			for($i=0; $i<$num_morescreenshot; $i++) {
				$cur = date('mY', microtime(true));
				$path = '/' . UPLOADFOLDER . '/thumbnails/' . $cur . '/';
				if(!is_dir('.' . $path)) mkdir('.' . $path);
				$this->upload->set_upload_path('.' . $path);

				// thêm mới ứng dụng
				if(!$this->upload->do_upload('upload_sc' . $i)) {
					
				} else {
					$image_data = $this->upload->data();
					$screenshots[] = $path. $image_data['file_name'];
				}
			}
            umask($oldUmask);
			
			$screenshot = '';
			if(count($screenshots) == 1) $screenshot = $screenshots[0];
			else $screenshot = implode('@@', $screenshots);
		
			/*
			 * end screenshot
			 */
			
			$count = $_POST['currentApp'];	

			$applelink = '';
			if($this->input->post('optiontype') == 'app')	$applelink = $this->input->post('crawler_link');

            $uploaders = array( 'sh1n',
                        'motonggia',
                        'GOLDKiST',
                        'Nexxor',
                        'XiBit',
                        'bang_coi',
                        'never_cry',
                        'cuongeuro001',
                        'kienphampro',
                        'SK1',
                        'leducprovip',
                        'maiyeu1',
                        'fcvodka',
                        'LoiMICK',
                        'tranvinhhn',
                        'SHEland1',
                        'leminhleon',
                        'MiniACC',
                        'saobang388',
                        'v0danh',
                        'mavuong95',
                        'hungr1977',
                        'hao_le',
                        'alizwong',
                        'phongthvn',
                        'nnvak',
                        'u072002',
                        'mathuatden',
                        'FlowerCity',
                        'andang_hehe_0807');
            //select one of uploaders above
            $ranNum = rand(0, count($uploaders) - 1);

			$params = array(
				'applelink' => $applelink,
				'app_name' => $this->input->post('app_name'),
				'vendor' => $this->input->post('vendor'),
				'vendor_site' => $this->input->post('vendor_site'),
				'description' => $this->input->post('description'),
                'size' => $this->input->post('size'),
				'version' => $this->input->post('version'),
				'price' => $this->input->post('price'),				
                'requirement' => $this->input->post('requirement'),
				'category' => $this->input->post('category'),
				'image' => $image,
				'screenshot' => $screenshot,
				'is_sticky' => $this->input->post('is_sticky'),
				'publish' => $this->input->post('publish'),
				'upload_time' => microtime(true),
				'last_update' => microtime(true),
                'uploader' => $uploaders[$ranNum]
			);			
			
			$app_id = $this->app->add($params);
			
			// cập nhật ảnh cho category
			if($this->input->post('publish') == 1) {
                        
                        $cat_param = array('image' => $image);
                        $this->load->model('category_model', 'category');
                        $this->category->update($this->input->post('category'), $cat_param);
			}
                        
			$status[] = 'Thêm mới ứng dụng thành công';
			
			for($i=0; $i<=$count; $i++) {
				$data['crawler'] = false;

				$upload = $this->upload->data();
				
				foreach($_POST['link' . $i] as $key=>$value) 
					if($value == "") unset($_POST['link' . $i][$key]);
				
				$link = implode('@@', $_POST['link' . $i]);
                if($this->input->post('price' . $i) == 'on') $price = 1;
                else $price = 0;
				$version = array(
					'app_id' => $app_id,
					'version' => $this->input->post('version' . $i),
					'link' => $link,
                    'price' => $price
				);
				$this->app->addAppVersion($version);
			}
    	}

		$data['status'] = $status;
		
		$this->load->view('admin/header');
		$this->load->view('admin/appadd', $data);
        $this->load->view('admin/footer');
	}
	
	// crawler
	function crawler() {
		$this->load->library('crawler');
		$this->load->model('category_model', 'category');
				
		if(isset($_POST['step'])) {
			$url = $_POST['crawler_link'];
			if($this->crawler->validUrl($url)) {
				if($this->crawler->getDomain($url) == "itunes.apple.com" || strpos($url, "itunes.apple.com") !== false) {
					$app = $this->crawler->iOS($url, $type = 'name');
					} else if($this->crawler->getDomain($url) == "market.android.com") {
						$app = $this->crawler->android($url, $type = 'name');
					} else if($this->crawler->getDomain($url) == "store.ovi.com") {
						$app = $this->crawler->java($url, $type = 'name');
					} else if($this->crawler->getDomain($url) == "appworld.blackberry.com") {
						$app = $this->crawler->blackberry($url, $type = 'name');
					} else {
						echo '<font color="red">Lỗi url crawler</font>';exit;
					}

					$check = $this->app->check_app($app, $url);
					//return var_dump($check);
					if($check) {echo $check;exit;}
					else {echo "0";exit;}
						
			} else {
				echo '<font color="red">Lỗi url crawler</font>';exit;
			}
		}
		
		$data = array();
		$data['categories'] = $this->app->getListCat();
		
		$url = $_POST['crawler_link'];
		
		$data['crawler'] = false;
		
		if($this->crawler->validUrl($url)) {
			if($this->crawler->getDomain($url) == "itunes.apple.com" || strpos($url, "itunes.apple.com") !== false) {
				$app = $this->crawler->iOS($url);
				} else if($this->crawler->getDomain($url) == "market.android.com") {
					$app = $this->crawler->android($url);
				} else if($this->crawler->getDomain($url) == "store.ovi.com") {
					$app = $this->crawler->java($url);
				} else if($this->crawler->getDomain($url) == "appworld.blackberry.com") {
						$app = $this->crawler->blackberry($url);
				} else {
					return '<font color="red">Lỗi url crawler</font>';exit;
				}
				
				$data['crawler'] = true;
				$app->translated = strip_tags($this->crawler->translate($app->description), '<a> <p>');
				$data['app'] = $app;
				$data['trungcat'] = $this->category->checkCategoryName($app->category);
		} else {
			echo '<font color="red">Lỗi url crawler</font>';exit;
		}
		
		$this->load->library('ckeditor');
		$data['editor'] = new CKEditor(base_url() . 'js/ckeditor/');		
		
		$this->load->view('admin/appaddcrawler', $data);
		$this->load->view('admin/appaddmorescreenshot');
	}
	
function crawleredit() {
		$this->load->library('crawler');
		$this->load->model('category_model', 'category');
		
		$data = array();
		$data['categories'] = $this->app->getListCat();
		
		$url = $_POST['crawler_link'];
		
		$data['crawler'] = false;
		
		if($this->crawler->validUrl($url)) {
			if($this->crawler->getDomain($url) == "itunes.apple.com" || strpos($url, "itunes.apple.com") !== false) {
				$app = $this->crawler->iOS($url);
				} else if($this->crawler->getDomain($url) == "market.android.com") {
					$app = $this->crawler->android($url);
				} else if($this->crawler->getDomain($url) == "store.ovi.com") {
					$app = $this->crawler->java($url);
				} else if($this->crawler->getDomain($url) == "appworld.blackberry.com") {
					$app = $this->crawler->blackberry($url);
				} else {
					return '<font color="red">Lỗi url crawler</font>';exit;
				}

				$data['crawler'] = true;
				$data['app'] = $app;
				$data['trungcat'] = $this->category->checkCategoryName($app->category);
		} else {
			echo '<font color="red">Lỗi url crawler</font>';exit;
		}
		
		$this->load->library('ckeditor');
		$data['editor'] = new CKEditor(base_url() . 'js/ckeditor/');		
		
		$this->load->view('admin/appeditcrawler', $data);
	}
	
	function ebookfilm()
	{
		$data = array();
		$data['categories'] = $this->app->getListCat();
		
		$this->load->library('ckeditor');
		$data['editor'] = new CKEditor(base_url() . 'js/ckeditor/');
		$this->load->view('admin/appaddebookfilm', $data);
		$this->load->view('admin/appaddmorescreenshot');
	}
	
}
