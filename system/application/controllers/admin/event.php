<?php
class Event extends Controller {
    function Event() {
        parent::__construct();
        $this->load->model('event_model');
        //$this->output->enable_profiler(TRUE);
        
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
    
    function viewAll($sortby = 'event_id', $order = 'DESC', $event_id = '0', $type = '-1', $name = '0', $sponsor = '0', $active = '-1', $limit = '10', $start = '0') 
	{
		$filter = array(
			'event_id' => $event_id,
			'type' => $type,
			'name' => $name,
			'sponsor' => $sponsor,
            'active' => $active,
			'start'	=> $start,
			'limit' => $limit,
			'sortby' => $sortby,
			'order' => $order
		);
		
		//if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		//if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$totalEvent = $this->event_model->totalFilterEvent($filter);
		$events = $this->event_model->allFilterEvent($filter);
        $types = $this->event_model->allEventType();
		
        $data['types'] = $types;
		$data['totalEvent'] = $totalEvent;
		$data['events'] = $events;
        $data['sort'] = $sortby;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['event_id'] = $event_id;
        $data['type'] = $type;
        $data['name'] = $name;
        $data['sponsor'] = $sponsor;
        $data['active'] = $active;
        $data['limit'] = $limit;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/event/viewall/$sortby/$order/$event_id/$type/$name/$sponsor/$active/$limit");
        $config['total_rows'] = $totalEvent;
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
		$this->load->view('admin/event', $data);
		$this->load->view('admin/footer');
	}
    
    function add()
	{
		$data = array();
		if(isset($_POST['insert'])) {
			$oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/event/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('thumbnail')) {
				$image = '/images/button/gift.png';
			} else {
				$upload = $this->upload->data();
				$image = base_url(). $path . $upload['file_name'];
			}
            unset($_POST['thumbnail']);
			unset($_POST['insert']);
            $_POST['expired_time'] = strtotime($this->input->post('expired_time').' 23:59:59');
			$_POST['image'] = $image;
			$this->event_model->addEvent($_POST);
			$data['success'] = 'Thêm mới sự kiện thành công';
		}
		$this->load->view('admin/header');
		$this->load->view('admin/eventadd', $data);
		$this->load->view('admin/footer');
	}
    
    function edit($eventId)
	{
		$data = array();
		if(isset($_POST['update'])) {
			$oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/event/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('thumbnail')) {
				//$image = '/' . UPLOADFOLDER . '/category/default.jpg';
			} else {
				$upload = $this->upload->data();
				$image = base_url() . $path . $upload['file_name'];
			}
            unset($_POST['thumbnail']);
			unset($_POST['update']);
            $_POST['expired_time'] = strtotime($this->input->post('expired_time').' 23:59:59');
			if(isset($image)) $_POST['image'] = $image;
			$this->event_model->updateEvent($eventId, $_POST);
			$data['success'] = 'Chỉnh sửa sự kiện thành công';
		}
        $data['event'] = $this->event_model->getEvent($eventId, false);
		$this->load->view('admin/header');
		$this->load->view('admin/eventedit', $data);
		$this->load->view('admin/footer');
	}
    
    /*
     * hộp quà
     */
    function addgiftbox($eventId) {
        if(isset($_POST['insert'])) {
            /*
             *thêm mới hộp quà
             */
            $name = $this->input->post('name');
            $tymType = $this->input->post('tym_type');
            $inputTym = $this->input->post('input_tym');
            $random = $this->input->post('random');
            $returnText = $this->input->post('return_text');
            // upload anh
            $oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/event/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('image')) {
				$image = '/images/button/gift.png';
			} else {
                $upload = $this->upload->data();
                $image = base_url() . $path . $upload['file_name'];
            }
            $giftboxData = array(
                'name' => $name,
                'tym_type' => $tymType,
                'input_tym' => $inputTym,
                'event_id' => $eventId,
                'image' => $image,
                'random' => $random,
                'return_text' => $returnText
            );
            $giftboxId = $this->event_model->addGiftbox($giftboxData);
            
            /*
             * thêm mới quà cho hộp quà
             */
            $curGift = $this->input->post('currentGift');
            for($i=0; $i<=$curGift; $i++) {
                //upload anh cho qua`
                if($this->upload->do_upload('anh'.$i)) {
                    $upload = $this->upload->data();
                    $image = base_url() . $path . $upload['file_name'];
                } else {
                    $image = '';
                }
                $giftData = array(
                    'name' => $this->input->post('name'.$i),
                    'type' => $this->input->post('type'.$i),
                    'value' => $this->input->post('value'.$i),
                    'more_text' => $this->input->post('more_text'.$i),
                    'quantity' => $this->input->post('quantity'.$i),
                    'xacsuat' => $this->input->post('xacsuat'.$i),
                    'image' => $image,
                    'giftbox_id' => $giftboxId
                );
                $this->event_model->addGift($giftData);
            }
            $data['success'] = 'Thêm mới hộp quà thành công';
            $data['giftboxId'] = $giftboxId;
        }
        $data['eventId'] = $eventId;
        $this->load->view('admin/header');
        $this->load->view('admin/eventgiftadd', $data);
        $this->load->view('admin/footer');
    }
    
    function editgiftbox($giftboxId) {
        if(isset($_POST['update'])) {
            /*
             *thêm mới hộp quà
             */
            $name = $this->input->post('name');
            $tymType = $this->input->post('tym_type');
            $inputTym = $this->input->post('input_tym');
            $random = $this->input->post('random');
            $returnText = $this->input->post('return_text');
            // upload anh
            $oldUmask = umask();
            umask(0);
			$cur = date('mY', microtime(true));
			$path = '/' . UPLOADFOLDER . '/event/' . $cur . '/';
            if(!is_dir('.' . $path)) mkdir('.' . $path);
            umask($oldUmask);
			$config['upload_path'] = '.' . $path;
			$config['allowed_types'] = 'jpg|png|gif';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('image')) {
				//$image = '/' . UPLOADFOLDER . '/event/default.jpg';
			} else {
                $upload = $this->upload->data();
                $image = base_url(). $path . $upload['file_name'];
            }
            $giftboxData = array(
                'tym_type' => $tymType,
                'name' => $name,
                'input_tym' => $inputTym,
                'random' => $random,
                'return_text' => $returnText
            );
            if(isset($image)) $giftboxData['image'] = $image;
            $this->event_model->editGiftbox($giftboxId, $giftboxData);
            
            /*
             * thêm mới/chỉnh sửa quà cho hộp quà
             */
            $curGift = $this->input->post('currentGift');
            for($i=0; $i<=$curGift; $i++) {
                if(isset($_POST['gift_id'.$i])) {
                    $gift_id = $this->input->post('gift_id'.$i);
                }
                $chose = $this->input->post('chose'.$i);
                //upload anh cho qua`
                if($this->upload->do_upload('anh'.$i)) {
                    $upload = $this->upload->data();
                    $image = base_url(). $path . $upload['file_name'];
                } else {
                    $image = '';
                }
                $giftData = array(
                    'name' => $this->input->post('name'.$i),
                    'type' => $this->input->post('type'.$i),
                    'value' => $this->input->post('value'.$i),
                    'more_text' => $this->input->post('more_text'.$i),
                    'quantity' => $this->input->post('quantity'.$i),
                    'xacsuat' => $this->input->post('xacsuat'.$i),
                );
                if($image) $giftData['image'] = $image;
                if($chose == 'delete') {
                    $this->event_model->deleteGift($gift_id);
                } else if($chose == 'edit') {
                    $this->event_model->editGift($gift_id, $giftData);
                } else {
                    $giftData['giftbox_id'] = $giftboxId;
                    $this->event_model->addGift($giftData);
                }
            }
            $data['success'] = 'Chỉnh sửa hộp quà thành công';
        }
        $data['giftboxId'] = $giftboxId;
        $data['giftbox'] = $this->event_model->getGiftbox($giftboxId, false);
        $data['gifts'] = $this->event_model->getGift($giftboxId);
        $this->load->view('admin/header');
        $this->load->view('admin/eventgiftedit', $data);
        $this->load->view('admin/footer');
    }
    
    function detail($eventId) {
        $this->load->view('admin/header');
        if(isset($_POST['saveorder'])) {
            unset($_POST['saveorder']);
            foreach($_POST as $key => $value) {
                $type = substr($key, 0, 5);
                $datax = array();
                if($type == 'order') {
                    $giftboxId = substr($key, 5);
                    $datax['order'] = $value;
                }
                if($type == 'publi') {
                    $datax['publish'] = $value;
                }
                $this->event_model->editGiftbox($giftboxId, $datax);
                $data['success'] = 'Lưu hộp quà thành công';
            }
        }
        $data['giftboxs'] = $this->event_model->getGiftboxByEventId($eventId, true);
        $data['event'] = $this->event_model->getInfo($eventId);
        $this->load->view('admin/eventdetail', $data);
        $this->load->view('admin/footer');
    }
    
    function deletegiftbox($giftboxId) {
        $this->event_model->deleteGiftbox($giftboxId);
        echo "1";
    }
    
    function giftboxLog($sortby = 'log_id', $order = 'DESC', $user_id = '0', $username = '0', $event_id = '0', $receive_type = '0', $receive_status = '-1', $time = '0', $limit = '50', $start = '0') 
	{
		$filter = array(
			'user_id' => $user_id,
			'username' => $username,
			'event_id' => $event_id,
			'receive_type' => $receive_type,
            'receive_status' => $receive_status,
            'time' => $time,
			'start'	=> $start,
			'limit' => $limit,
			'sortby' => $sortby,
			'order' => $order
		);
        
        $data['userT1'] = $this->event_model->userTym('t1');
        $data['userT2'] = $this->event_model->userTym('t2');
        $data['userT3'] = $this->event_model->userTym('t3');
        $data['userT4'] = $this->event_model->userTym('t4');
        
        $data['sysT1'] = $this->event_model->systemTym('t1');
        $data['sysT2'] = $this->event_model->systemTym('t2');
        $data['sysT3'] = $this->event_model->systemTym('t3');
        $data['sysT4'] = $this->event_model->systemTym('t4');
        
        $data['uniqueUser'] = $this->event_model->uniqueUser();
		
		//if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		//if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$totalLog = $this->event_model->totalFilterLog($filter);
		$logs = $this->event_model->allFilterLog($filter);
		
		$data['totalLog'] = $totalLog;
		$data['logs'] = $logs;
        $data['sort'] = $sortby;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['event_id'] = $event_id;
        $data['user_id'] = $user_id;
        $data['username'] = $username;
        $data['receive_type'] = $receive_type;
        $data['receive_status'] = $receive_status;
        $data['time'] = $time;
        $data['limit'] = $limit;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/event/giftboxLog/$sortby/$order/$user_id/$username/$event_id/$receive_type/$receive_status/$time/$limit");
        $config['total_rows'] = $totalLog;
        $config['uri_segment'] = 13;
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
		$this->load->view('admin/event_giftboxlog', $data);
		$this->load->view('admin/footer');
	}
    
    function giftboxDetail($id) {
        $giftbox = $this->event_model->getGiftbox($id);
        if(!$giftbox) {
            show_error("giftbox does not exists");
        }
        $data['giftbox'] = $giftbox;
        $data['gifts'] = $this->event_model->getGift($id);
        $this->load->view('admin/header');
        $this->load->view('admin/event_giftboxdetail', $data);
        $this->load->view('admin/footer');
    }
    
    function giftDetail($id) {
        $gift = $this->event_model->getGiftById($id);
        if(!$gift) {
            show_error("giftbox does not exists");
        }
        $data['gift'] = $gift;
        $this->load->view('admin/header');
        $this->load->view('admin/event_giftdetail', $data);
        $this->load->view('admin/footer');
    }
}