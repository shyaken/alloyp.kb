<?php
class Giftcode extends Controller {
    function Giftcode() {
        parent::__construct();
        $this->load->model('giftcode_model', 'gc');
        
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
    
    function viewAll($sortby = 'id', $order = 'DESC', $code = '0', $type = '0', $sender = '-1', $status = '-1', $create = '0', $use = '0', $expire = '0', $limit = '10', $start = '0') 
	{
		$filter = array(
            'code' => $code,
			'type' => $type,
			'sender' => $sender,
			'status' => $status,
            'create' => $create,
            'use' => $use,
            'expire' => $expire,
			'start'	=> $start,
			'limit' => $limit,
			'sortby' => $sortby,
			'order' => $order
		);
		
		//if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
		//if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];
		
		$totalGiftcode = $this->gc->totalFilterGiftcode($filter);
		$giftcodes = $this->gc->allFilterGiftcode($filter);
		
		$data['totalGiftcode'] = $totalGiftcode;
		$data['giftcodes'] = $giftcodes;
        $data['sort'] = $sortby;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['code'] = $code;
        $data['type'] = $type;
        $data['sender'] = $sender;
        $data['status'] = $status;
        $data['create'] = $create;
        $data['use'] = $use;
        $data['expire'] = $expire;
        $data['limit'] = $limit;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/giftcode/viewall/$sortby/$order/$code/$type/$sender/$status/$create/$use/$expire/$limit");
        $config['total_rows'] = $totalGiftcode;
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
		$this->load->view('admin/giftcode', $data);
		$this->load->view('admin/footer');
	}
    
    function add() {
        $data = array();
        if(isset($_POST['insert'])) {
            $type = $this->input->post('type');
            $value = $this->input->post('value');
            $count = $this->input->post('quantity');
            $expire = $this->input->post('expire');
            $expire = strtotime($expire . ' 23:59:59');
            $reason = $this->input->post('reason');
            $now = time();
            $giftcodes = array();
            for($i=0; $i<$count; $i++) {
                $giftcode = random_string('numeric', 12);
                $addData = array(
                    'code' => $giftcode,
                    'type' => $type,
                    'value' => $value,
                    'sender' => 0,
                    'status' => 0,
                    'create_date' => $now,
                    'expire_date' => $expire,
                    'reason' => $reason
                );
                $this->gc->add($addData);
                $giftcodes[] = $giftcode;
            }
            if($giftcodes) {
                $data['giftcode'] = implode(';', $giftcodes);
            }
            $data['success'] = 'Thêm mới giftcode thành công';
        }
        $this->load->view('admin/header');
        $this->load->view('admin/giftcodeadd', $data);
        $this->load->view('admin/footer');
    }
    
    function generateCode($userId) {
        if(!$userId || !is_numeric($userId)) {
            return false;
        }
        $len = strlen($userId);
        $now = time();
        $rand = $now % 3;
        if($len >= 3) $remainLen = 12-$rand-$len;
        else $remainLen = 10-$rand-$len;
        $remainNum = substr($now,-$remainLen);
        $giftcode = "$userId"."$remainNum";
        return $giftcode;
    }
    
    function test($userId) {
        echo $this->generateCode($userId);
    }
}
?>
