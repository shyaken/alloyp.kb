<?php
class Manageradmin extends Controller
{
	private $controllers = array(
		'managerapp' => 		'Quản lý ứng dụng',
		'upload' => 			'Cho phép tải ứng dụng mới',
		'category' => 			'Quản lý category',
		'usertransaction' => 	'Xem log giao dịch của người dùng',
		'ad' => 				'Quản lý quảng cáo',
		'survey' => 			'Quản lý thăm dò',
                'logo' =>                       'Quản lý logo cho trang chủ',
		'statistic' => 			'Quản lý thống kê download',
		'textad' => 			'Quản lý dòng text chạy ...',
		'online' => 			'Xem thống kê người dùng online',
		'packagelog' => 		'Xem thống kê đăng ký gói của người dùng',
		'actionlog' => 			'Xem log tặng tym cho người dùng (login, rate ...)',
		'report' => 			'Xem báo cáo lỗi của người dùng',
		'actionreward' => 		'Quản lý hành động được cộng tym<span id="controller-note">*</span>',
		'user' => 				'Quản lý người dùng',
        'textnote' =>           'Quản lý text trả về SMS, thông báo ...<span id="controller-note">*</span>',
		'manageradmin' => 		'Quản lý Admin, nhóm Admin <span id="controller-note">*</span>',
		'setting' => 			'Quản lý giá <span id="controller-note">*</span>',
        'promotion' =>          'Quản lý giá khuyến mãi',
        'event' =>              'Quản lý các sự kiện trên AppStoreVn'
	);	
	
    function Manageradmin()
    {
        parent::__construct();
        $this->load->model('admin_model', 'admin');
        
        if($this->session->userdata('is_admin') != 'yes') {
            redirect('admin/login');
        }
        if($this->session->userdata('is_root') != 'yes') {
            redirect('admin/dashboard');
        }
    }
    
    function index()
    {
        $data['admins'] = $this->admin->listAll();
        
        $this->load->view('admin/header');
        $this->load->view('admin/admin', $data);
        $this->load->view('admin/footer');
    }
    
    /*
     * edit
     */
    function edit($id)
    {
        if(!$this->admin->getInfo($id)) 
             redirect('admin/manageradmin');
        
        if(isset($_POST['update'])) {
            unset($_POST['update']);
            
            if($this->input->post('password') != '') {
                $_POST['password'] = md5($this->input->post('password') . $this->input->post('salt'));
            } else {
                unset($_POST['password']);
            }
            
            $this->admin->edit($id, $_POST);
            redirect('admin/manageradmin');
        }
        
        $data['admin'] = $this->admin->getInfo($id);
        $data['groups'] = $this->admin->listGroup();
        $this->load->view('admin/header');
        $this->load->view('admin/adminedit', $data);
        $this->load->view('admin/footer');
    }
    
    /*
     * delete
     */
    function delete($id)
    {
        $this->admin->delete($id);
        redirect('admin/manageradmin');
    }
    
    /*
     * add
     */
    function add()
    {
        $data = array();
        if(isset($_POST['insert'])) {
            unset($_POST['insert']);
            $salt = random_string('alnum', 4);
            $_POST['password'] = md5($this->input->post('password') . $salt);
            $_POST['salt'] = $salt;
            $ok = $this->admin->createAdmin($_POST);
            if($ok === true) 
                redirect('admin/manageradmin');
            else 
                $data['error'] = 'Admin đã tồn tại';
        }
        $data['groups'] = $this->admin->listGroup();
        $this->load->view('admin/header');
        $this->load->view('admin/adminadd', $data);
        $this->load->view('admin/footer');
    }
    
    /*=====================================================================================*\
     * 										Admin Group									   *
    \*=====================================================================================*/
    
    /*
     * list admin group
     */
    function group() {
    	$this->load->view('admin/header');
    	$data['groups'] = $this->admin->listGroup();
    	$this->load->view('admin/admingrouplist', $data);
    	$this->load->view('admin/footer');
    }
    
    /*
     * create admin group
     */
    function addGroup() {
    	$this->load->view('admin/header');
    	if(isset($_POST['add'])) {
    		$group_name = $this->input->post('group_name');
    		$comment = $this->input->post('comment');
    		$permissions = array();
			foreach($this->controllers as $key=>$value) {
				if($this->input->post($key) == 'on') $permissions[] = $key; 
			}    	
			$permission = implode('|', $permissions);
				if($group_id = $this->admin->isExistsGroup($permission)) {
					$data['error'] = 'Đã tồn tại nhóm Admin có ID = ' . $group_id;
				} else {
					$groupData = array(
						'group_name' => $group_name,
						'permission' => $permission,
						'comment' => $comment
					);
					$this->admin->addGroup($groupData);
					$data['success'] = 'Thêm mới nhóm Admin thành công!';
				}
    	}
    	$data['controllers'] = $this->controllers;
    	$this->load->view('admin/admingroupadd', $data);
    	$this->load->view('admin/footer');
    }
    
    /*
     * edit admin group
     */
    function editGroup($id) {
    	$this->load->view('admin/header');
    	if(isset($_POST['edit'])) {
    		$group_name = $this->input->post('group_name');
    		$comment = $this->input->post('comment');
    		$permissions = array();
			foreach($this->controllers as $key=>$value) {
				if($this->input->post($key) == 'on') $permissions[] = $key; 
			}    	
			$permission = implode('|', $permissions);
				$group_id = $this->admin->isExistsGroup($permission);
				if($group_id && $group_id != $id) {
					$data['error'] = 'Đã tồn tại nhóm Admin có ID = ' . $group_id;
				} else {
					$groupData = array(
						'group_name' => $group_name,
						'permission' => $permission,
						'comment' => $comment
					);
					$this->admin->updateGroup($id, $groupData);
					$data['success'] = 'Chỉnh sửa nhóm Admin thành công!';
				}
    	}
    	
    	$group = $this->admin->getGroupInfo($id);
    		$permissions = explode('|', $group->permission);
    		$data['permissions'] = $permissions;
    		$data['group'] = $group;
    	$data['controllers'] = $this->controllers;
    	$this->load->view('admin/admingroupedit', $data);
    	$this->load->view('admin/footer');
    }   

    /*
     * delete admin group
     * function deleteGroup() view admingrouplist.php
     */
    function deleteGroup($id) {
    	$this->admin->deleteGroup($id);
    	echo "1";
    }
    
    /*
     * show group permission
     */
    function groupDetail($group_id) {
    	$this->load->view('admin/header');
    	$group = $this->admin->getGroupInfo($group_id);
    		if(!$group) redirect('admin/manageradmin/group');
    		$permissions = explode('|', $group->permission);
    		$data['permissions'] = $permissions;
    		$data['group'] = $group;
    	$data['controllers'] = $this->controllers;
    	$this->load->view('admin/admingroupdetail', $data);
    }
}
?>
