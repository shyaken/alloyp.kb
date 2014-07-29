<?php
class Managerapp extends Controller
{
	private $msg = array();
	function Managerapp()
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
		$this->viewAll();
	}
	
	function flushCache() {
		$this->load->library('Cache', 'cache');
		$this->cache->flush();
		redirect('admin/managerapp');
	}
    
    function vnnplus() {
        $this->load->library('nusoap');
        $soap = new nusoap_client('http://183.91.14.101:8008/WebService/GSM/ServiceData.asmx?wsdl', TRUE);
        $soap->soap_defencoding = 'UTF-8';
        $soap->decode_utf8 = false;
        $soap->version = '1.1';
        $a = $soap->call('GetAllEbook');
        $b = $a['GetAllEbookResult'];
        $c = $b['root'];
        $d = $c['item'];
        $i = 0;
        $cur = date('mY', microtime(true));
		$path = '/' . UPLOADFOLDER . "/thumbnails/$cur";
		$cur = "." . $path;
		if(!is_dir($cur)) mkdir($cur);
        foreach($d as $book) {
            $id = $book['id'];
            $img1 = $book['av200x200'];
            $title = $book['title'] . ' vnnplus';
            $desc = $book['description'];
            
            $x = explode("/", $book['av200x200']);
                $filename = $x[count($x)-1];
                if(!file_exists($cur . "/" . $filename)) {
                    file_put_contents($cur ."/" . $filename,file_get_contents(urldecode($book['av200x200'])));
                    //$this->download($img->src, "$cur/$filename");
                    $image = $path . "/" . $filename;
                } else {
                    $image = $path . "/" . $filename;
                }
            
            $data = array(
                'app_name' => $title,
                'vendor' => 'vnnplus',
                'vendor_site' => '#',
                'description' => $desc,
                'image' => $image,
                'category' => 45,
                'publish' => 0,
                'promo_enable' => 0,
                'upload_time' => time(),
                'last_update' => time(),
                'uploader' => 'vnnplus'
            );
            $appId = $this->app->add($data);
            $verData = array(
                'app_id' => $appId,
                'version' => 'T 1',
                'link' => 'vnnplus||'.$id,
                'price' => 1
            );
            $this->app->addAppVersion($verData);
            echo ($i+1).": ".$book['id']." ok<br />";
            $i++;
        }
    }
        
	function viewAll($sortby = 'app_id', $order = 'DESC', $startdate = '0', $enddate = '0', $app_name = '0', $vendor = '0', $category = '0', $limit = '10', $start = '0') 
	{
		$startdate_ = $startdate;
		$enddate_ = $enddate;
		if($startdate != '0') {
			$startdate = strtotime(str_replace('_', '/', $startdate) . '00:00:00');
			$enddate = strtotime(str_replace('_', '/', $enddate) . '23:59:59');
		}
		
		if($startdate == '0') {
			$data['info'] = 'Thống kê tất cả apps cho đến hiện tại';
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $startdate) . ' đến ' . date('d/m/Y', $enddate) . '(<font color="green">dd/mm/yyyy</font>)';
		}
		
		$filter = array(
			'startdate' => $startdate,
			'enddate' => $enddate,
			'app_name' => $app_name,
			'vendor' => $vendor,
			'category' => $category,
			'start'	=> $start,
			'limit' => $limit,
			'sortby' => $sortby,
			'order' => $order
		);
		
		if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$totalApps = $this->app->totalFilterApp($filter);
		$apps = $this->app->allFilterApp($filter);
		
		$data['totalApps'] = $totalApps;
		$data['startdate'] = $startdate_;
		$data['enddate'] = $enddate_;
		$data['apps'] = $apps;
        $data['sort'] = $sortby;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['app_name'] = $app_name;
        $data['vendor'] = $vendor;
        $data['category_filter'] = $category;
        $data['limit'] = $limit;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/managerapp/viewall/$sortby/$order/$startdate_/$enddate_/$app_name/$vendor/$category/$limit");
        $config['total_rows'] = $totalApps;
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
        
        
        $data['url_1'] = site_url("admin/managerapp/viewall/$sortby/$order/$startdate_/$enddate_/$app_name/$vendor/$category/");
        $data['url_2'] = $limit;
        $data['url_3'] = $start;
        
        $data['categories'] = $this->app->getListCat();
		
		$this->load->view('admin/header');
		$this->load->view('admin/app', $data);
		$this->load->view('admin/footer');
	}
	
	function detail($id)
	{
		if(!$this->app->isExists($id))
			show_error('application does not exists');
		
		$app = $this->app->getInfo($id);
		$data['app'] = $app;	
		$this->load->view('admin/header');
		$this->load->view('admin/appdetail', $data);
		$this->load->view('admin/footer');		
	}
	
	function edit($id)
	{
		if($this->app->isExists($id)) {
			
			$status = array();
			if(isset($_POST['update'])) {
                $path = '/' . UPLOADFOLDER .'/app/';
                $cur = date('mY', microtime(true));
                $original = '/' . UPLOADFOLDER . '/app/original/';
                if(!is_dir('.' . $original)) mkdir('.' . $original);
				$config['upload_path'] = '.' . $path;
				$config['allowed_types'] = 'jpg|png|gif|jpeg';
				$this->load->library('upload', $config);
				
				$count = $_POST['currentApp'];
				
				$image = $this->input->post('thumbnail_crawler');
				// thêm mới ứng dụng
				if($this->upload->do_upload('thumbnail')) {
					$image_data = $this->upload->data();
					$image = $path . $image_data['file_name'];
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
					if(file_exists($this->input->post('thumbnail_crawler'))) {
						unlink(file_exists($this->input->post('thumbnail_crawler')));
					}
                    $categoryUpdate = array('image' => $image);
                    $this->load->model('category_model');
                    $this->category_model->update($this->input->post('category'), $categoryUpdate);
				}
				
				
				// lấy trạng thái publish hiện tại
				$curApp = $this->app->getInfo($id);
				$curPublish = $curApp->publish;	
				$publish = $this->input->post('publish');
				
				$params = array(
					'applelink' => $this->input->post('crawler_link'),
					'app_name' => $this->input->post('app_name'),
					'vendor' => $this->input->post('vendor'),
					'vendor_site' => $this->input->post('vendor_site'),
					'description' => $this->input->post('description'),
					'size' => $this->input->post('size'),
					'requirement' => $this->input->post('requirement'),
					'category' => $this->input->post('category'),
					'is_sticky' => $this->input->post('is_sticky'),
					'publish' => $publish
				);
                
                // them moi tag
                $tag = $this->input->post('tags');
                $tag = nl2br($tag);
                if($tag) {
                    $tags = explode('<br />', $tag);
                    foreach($tags as $tag) {
                        $tag = trim($tag);
                        if($tag) {
                            $checkTag = $this->app->checkTag($tag);
                            if($checkTag) {
                                $tag_id = $checkTag->tag_id;
                                $checkTagMap = $this->app->checkTagMap($tag_id, $id);
                                if(!$checkTagMap) {
                                    $this->app->addTagMap($tag_id, $id);
                                }
                            } else {
                                $tag_id = $this->app->addTag($tag);
                                $this->app->addTagMap($tag_id, $id);
                            }
                        }
                    }
                }
				
				// nếu publish từ 0->1 thì cập nhật upload_time
				if($curPublish == 0 && $publish == 1) $params['upload_time'] = microtime(true);

				// cap nhat image cho category
				if(isset($image)) $params['image'] = $image;
					
				/*
			 * screenshot
			 */
			
			$screenshots = array();
			$i = 0;
			foreach($_POST['crawler_sc_link'] as $link) {
				if(isset($_POST['crawler_sc' . $i])) {
					$screenshots[] = $link;
				} else {
					if($link != "")
						if(file_exists('.' . $link))
							unlink('.' . $link);
				}
				$i++;
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
			$params['screenshot'] = $screenshot;
				
				$data['success'] = 'Chỉnh sửa ứng dụng thành công'; 
				$this->app->edit($id, $params);

				// chỉnh sửa phiên bản
				for($i=0; $i<=$count; $i++) {
					$app_version_id = "";
					if(isset($_POST['app_version_id_' . $i]))
						$app_version_id = $_POST['app_version_id_' . $i];	

					if($_POST['chose' . $i] == 'delete') {
						$this->app->deleteVersion($app_version_id);
						//$status[] = 'Xóa phiên bản ' . $_POST['version' . $i] . ' thành công';
					} else if($_POST['chose' . $i] == 'edit'){
						foreach($_POST['link' . $i] as $key=>$value) 
						if($value == "") unset($_POST['link' . $i][$key]);
					
						$link = implode('@@', $_POST['link' . $i]);
                        if($this->input->post('price'.$i) == 'on') $price = 1;
                        else $price = 0;
						$params = array(
							'version' => $this->input->post('version' . $i),
							'link' => $link,
                            'price' => $price
						);
						$this->app->editVersion($app_version_id, $params);
						//$status[] = 'Chỉnh sửa phiên bản ' . $_POST['version' . $i] . ' thành công';
					} else {
						if($this->input->post('version' . $i) != "") {
							foreach($_POST['link' . $i] as $key=>$value) 
							if($value == "") unset($_POST['link' . $i][$key]);
					
							$link = implode('@@', $_POST['link' . $i]);
                            if($this->input->post('price'.$i) == 'on') $price = 1;
                            else $price = 0;
							$params = array(
								'app_id' => $id,
								'version' => $this->input->post('version' . $i),
								'link' => $link,
                                'price' => $price
							);
							$this->app->addAppVersion($params);
														
							$last_update = array('last_update' => microtime(true));
							$this->app->update($id, $last_update);
						}
					}
				}
			}
			
			$data['versions'] = $this->app->getListVersion($id);
			$data['totalVersion'] = $this->app->totalVersionByAppId($id)-1;
			
			$app = $this->app->getInfo($id);
			$data['app'] = $app;
			$data['categories'] = $this->app->getListCat();
			$data['status'] = $status;
			
            $data['tags'] = $this->app->getTagByApp($id);
			$this->load->library('ckeditor');
			$data['editor'] = new CKEditor(base_url() . 'js/ckeditor/');
			
			$this->load->view('admin/header');
			$this->load->view('admin/appedit', $data);
			$this->load->view('admin/footer');
		} else {
			$this->msg['error'] = 'Ứng dụng không tồn tại';
			$this->viewAll();
		}
	}
	
	// xóa list ứng dụng
	function delete()
	{
		if(isset($_POST['selected'])) {
			$delete = false;
			if($this->session->userdata('is_root') == 'yes') $delete = true;
			if($this->session->userdata('is_root') != 'yes' && count($_POST['selected']) == 1) $delete = true;
			if($delete) {
				foreach($_POST['selected'] as $id)
					$this->app->delete($id);
				$this->msg['success'] = 'Đã xóa ứng dụng (s) thành công';
			} else {
				$this->msg['error'] = 'Bạn không đủ quyền để xóa hàng loạt apps (s)';
			}
		}
		$this->viewAll();
	}
	
	// nhận request từ publishID() ở view admin/app
	function publishID() 
	{
		$app_id = $this->input->post('app_id');
		$value = $this->input->post('value');
		
		if($value == 1) {
			$this->app->update($app_id, array('upload_time' => microtime(true))); 
			$this->app->publish($app_id);
			echo '<a href="javascript:;" onclick="publishID(' . $app_id . ',0);">Tắt đi</a>';			
		} else  {
			$this->app->unpublish($app_id);
			echo '<a href="javascript:;" onclick="publishID(' . $app_id . ',1);">Bật lên</a>';
		}
	}	
	
	// bật ứng dụng id[]
	function publish()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id) {
				$curApp = $this->app->getInfo($id);
				$curPublish = $curApp->publish;
				if($curPublish == 0) $this->app->update($id, array('upload_time' => microtime(true)));
				$this->app->publish($id);
			}
			$this->msg['success'] = 'Đã bật ứng dụng (s) thành công';
		}
		$this->viewAll();
	}
	
	// tắt ứng dụng id[]
	function unPublish()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id)
				$this->app->unPublish($id);
			$this->msg['success'] = 'Đã tắt ứng dụng (s) thành công';
		}
		$this->viewAll();
	}	
	
	// nhận request từ stickyID() ở view admin/app
	function stickyID() 
	{
		$app_id = $this->input->post('app_id');
		$value = $this->input->post('value');
		
		if($value == 1) { 
			$this->app->sticky($app_id);
			echo '<a href="javascript:;" onclick="stickyID(' . $app_id . ',0);">Tắt đi</a>';			
		} else  {
			$this->app->unsticky($app_id);
			echo '<a href="javascript:;" onclick="stickyID(' . $app_id . ',1);">Bật lên</a>';
		}
	}
	
	// sticky app id[]
	function sticky()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id)
				$this->app->sticky($id);
			$this->msg['success'] = 'Đã đưa ứng dụng (s) lên HOT thành công';
		}
		$this->viewAll();
	}
	
	// unsticky app id[]
	function unsticky()
	{
		if(isset($_POST['selected'])) {
			foreach($_POST['selected'] as $id)
				$this->app->unsticky($id);
			$this->msg['success'] = 'Đã đưa ứng dụng (s) trở về BÌNH THƯỜNG thành công';
		}
		$this->viewAll();
	}
	
	
	/*
	 *************************************************************************************************
	 ***********************************	comments	********************************************** 
	 *************************************************************************************************
	 */
	function listComment($appid)
	{
		$limit = 20;
		$data['comments'] = $this->app->getCommentByApp($appid, $this->uri->segment(5), $limit);
		$data['app_id'] = $appid;
		$data['app'] = $this->app->getInfo($appid);
		$totalComments = $this->app->totalCommentByAppId($appid);	
	
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/managerapp/listComment/$appid");
        $config['total_rows'] = $totalComments;
        $config['uri_segment'] = 5;
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
        		
		$this->load->view('admin/header');
		$this->load->view('admin/applistcomment', $data);
		$this->load->view('admin/footer');		
	}
	
	/*
	 * xóa comment
	 */
	function deleteComment() 
	{
                $appid = $this->input->post("appid");
                $id = $this->input->post("id");
		$this->app->deleteComment($id);
		$this->app->downCommentCount($appid, 1);
		//redirect('admin/managerapp/listComment/' . $appid);
	}		
	
	/*
	 * xóa list comment
	 */
	function deleteCommentList($appid)
	{
		if(isset($_POST['selected'])) {
			$i = 0;
			foreach($_POST['selected'] as $id) { 
				$this->app->deleteComment($id);
				$i++;
			}
			$this->app->downCommentCount($appid, $i);
			$this->msg['success'] = 'Xóa bình luận(s) thành công';	
		}
		redirect('admin/managerapp/listComment/' . $appid);		
	}
	
	/*
	 * danh sách từ bị cấm trong bình luận
	 */
	function commentFilter() {
		$this->load->view('admin/header');
		
		if(isset($_POST['find'])) {
			$data = array(
				'find' => $this->input->post('find'),
				'replace' => $this->input->post('replace')
			);
			$this->app->addBadWord($data);
			$data['success'] = 'Thêm mới thành công';
		}
		
		$data['words'] = $this->app->listBadWord();
		$this->load->view('admin/appcommentfilter', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * lưu lại từ bị cấm
	 * 
	 */
	function editBadWord() {
		$id = $this->input->post('id');
		$data = array(
			'find' => $this->input->post('find'),
			'replace' => $this->input->post('replace')
		);
		$this->app->updateBadWord($id, $data);
		echo "1";
	}
	
	/*
	 * xóa từ xấu
	 */
	function deleteBadWord($id) {
		$this->app->deleteBadWord($id);
		echo "1";		
	}
    
    function deleteTag() {
        $tag_id = $this->input->post('tag_id');
        $app_id = $this->input->post('app_id');
        $this->app->deleteTagMap($tag_id, $app_id);
        echo "1";
    }    
}
