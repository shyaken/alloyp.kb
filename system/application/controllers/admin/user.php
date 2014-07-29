<?php
class User extends Controller
{
    private $msg = array();
    
    function User()
    {
        parent::__construct();
        $this->load->model('user_model', 'user');

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
    function viewAll($sortby = 'user_id', $order = 'DESC', $username = '-1', $email = '-1', $phone = '-1', $use_package = '-1', $active_by = '0', $limit = '20', $start = '0')
    {
            $filter = array(
                    'username' => $username,
                    'email' => $email,
                    'phone' => $phone,
                    'active_by' => $active_by,
            		'use_package' => $use_package,
                    'start'	=> $start,
                    'limit' => $limit,
                    'sortby' => $sortby,
                    'order' => $order
            );

            if(isset($this->msg['error'])) $data['error'] = $this->msg['error'];
            if(isset($this->msg['success'])) $data['success'] = $this->msg['success'];

            $totalUsers = $this->user->totalFilterUser($filter);
            $users = $this->user->allFilterUser($filter);

            $data['totalUsers'] = $totalUsers;
            $data['users'] = $users;
    $data['sortby'] = $sortby;
    $data['order'] = $order;
    $data['start'] = $start;
    $data['username'] = $username;
    $data['email'] = $email;
    $data['phone'] = $phone;
    $data['active_by'] = $active_by;
    $data['use_package'] = $use_package;

            // phân trang cho user
            $this->load->library('pagination');
    $config['base_url'] = site_url("admin/user/viewall/$sortby/$order/$username/$email/$phone/$use_package/$active_by/$limit");
    $config['total_rows'] = $totalUsers;
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


    $data['url_1'] = site_url("admin/user/viewall/$sortby/$order/$username/$email/$phone/$use_package/$active_by");
    $data['url_2'] = $limit;
    $data['url_3'] = $start;

            $this->load->view('admin/header');
            $this->load->view('admin/user', $data);
            $this->load->view('admin/footer');

    }

    /*
     * edit
     */
    function edit($user_id)
    {
    	if(!$this->user->isExists($user_id)) {
    		$this->msg['error'] = 'User không tồn tại';
    		$this->viewAll();
    	}
    	
    	$data = array();
    	
    	if(isset($_POST['update'])) {
            $curUser = $this->user->getUserById($user_id);
            $email = $this->input->post('email');
                $checkEmail = 0;
                if($email) {
                    $checkEmail = $this->user->isExistsField(array('email'=>$email));
                }
            if($curUser->email != $email && $checkEmail) {
                $data['error'] = 'Email đã tồn tại rồi nhá!!!';
            } else {
                $params = array(
                    'email' => $email,
                    'active_by' => $this->input->post('active_by'),
                    'birthday' => $this->input->post('birthday'),
                    'city' => $this->input->post('city')
                    //'t1' => $this->input->post('t1'),
                    //'t2' => $this->input->post('t2'),
                    //'t3' => $this->input->post('t3'),
                    //'t4' => $this->input->post('t4'),
                    /*
                    'package_type' => $this->input->post('package_type'),
                    'package_expired' => strtotime($this->input->post('package_expired'))
                     */
                );
                if($this->input->post('password') != '') {
                    $params['password'] = md5($this->input->post('password'));
                }
                if(isset($_POST['phone'])) $params['phone'] = $this->input->post('phone');

                $this->user->update($user_id, $params);
                $data['success'] = 'Cập nhật thành công';
            }
    	}
    	
    	$data['user'] = $this->user->getUserById($user_id);
		$this->load->view('admin/header');
        $this->load->view('admin/useredit', $data);
        $this->load->view('admin/footer');
    }

    /*
     * delete
     */
    function delete()
    {
    	$i = 0;
    	if(isset($_POST['selected'])) {
    		$i = 1;
			foreach($_POST['selected'] as $id)
				$this->user->delete($id);
    	}
    	if($i == 1) $this->msg['success'] = 'Xóa user (s) thành công';
		$this->viewAll();	
    }
    
    /*
     * log trừ tiền người dùng khi download
     */
    function downloadLog($userid, $sort = 'id', $order = 'DESC', $startdate = '0', $enddate = '0', $limit = '10', $start = '0') {
        $user = $this->user->getUserById($userid);
        if(!$user) redirect('admin/user');
        $startdate_ = $startdate;
		$enddate_ = $enddate;
		if($startdate != '0') {
			$startdate = strtotime(str_replace('_', '/', $startdate) . '00:00:00');
			$enddate = strtotime(str_replace('_', '/', $enddate) . '23:59:59');
		}
		
		if($startdate == '0') {
			$data['info'] = 'Thống kê tất cả lượt tải cho đến hiện tại của người dùng <b>' . $user->username . '</b>'; 
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $startdate) . ' đến ' . date('d/m/Y', $enddate) . '(<font color="green">dd/mm/yyyy</font>) của người dùng <b>' . $user->username . '</b>';
		}
		
		$filter = array(
            'userid' => $userid,
			'startdate' => $startdate,
			'enddate' => $enddate,
			'start'	=> $start,
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order
		);
        
        $totalLogs = $this->user->totalDownloadLog($filter);
        $logs = $this->user->allDownloadLog($filter);
        
        $data['totalLogs'] = $totalLogs;
		$data['startdate'] = $startdate_;
		$data['enddate'] = $enddate_;
		$data['logs'] = $logs;
        $data['userid'] = $userid;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['limit'] = $limit;
        $data['username'] = $user->username;
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/user/downloadLog/$userid/$sort/$order/$startdate_/$enddate_/$limit");
        $config['total_rows'] = $totalLogs;
        $config['uri_segment'] = 10;
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
		$this->load->view('admin/userdownloadlog', $data);
		$this->load->view('admin/footer');        
    }
    
    /*
     * log trừ nạp tiền của người dùng
     */
    function paymentLog($userid = '1', $sort = 'id', $order = 'DESC', $method = '0', $status = '0', $startdate = '0', $enddate = '0', $limit = '10', $start = '0') {
        $user = $this->user->getUserById($userid);
        if(!$user) redirect('admin/user');
        $startdate_ = $startdate;
		$enddate_ = $enddate;
		if($startdate != '0') {
			$startdate = strtotime(str_replace('_', '/', $startdate) . '00:00:00');
			$enddate = strtotime(str_replace('_', '/', $enddate) . '23:59:59');
		}
		
		if($startdate == '0') {
			$data['info'] = 'Thống kê tất cả giao dịch cho đến hiện tại của người dùng <b>' . $user->username . '</b>';
		} else {
			$data['info'] = 'Thống kê từ ' . date('d/m/Y', $startdate) . ' đến ' . date('d/m/Y', $enddate) . '(<font color="green">dd/mm/yyyy</font>) của người dùng <b>' . $user->username . '</b>';
		}
		
		$filter = array(
            'userid' => $userid,
			'startdate' => $startdate,
			'enddate' => $enddate,
            'method' => $method,
            'status' => $status,
			'start'	=> $start,
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order
		);
        
        $totalLogs = $this->user->totalPaymentLog($filter);
        $logs = $this->user->allPaymentLog($filter);
        
        $data['totalLogs'] = $totalLogs;
		$data['startdate'] = $startdate_;
		$data['enddate'] = $enddate_;
        $data['method'] = $method;
        $data['status'] = $status;
		$data['logs'] = $logs;
        $data['userid'] = $userid;
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['start'] = $start;
        $data['limit'] = $limit;
        $data['listMethod'] = array('sms', 'card', 'paypal', 'moneybooker', 'bank');
        		
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/user/paymentLog/$userid/$sort/$order/$method/$status/$startdate_/$enddate_/$limit");
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
		$this->load->view('admin/userpaymentlog', $data);
		$this->load->view('admin/footer');        
    }    
}
?>
