<?php

class User extends Controller {
    private $_store = array(
        'http://appstore.vn/' => 'all',
        'http://appstore.vn/a/' => 'a',
        'http://appstore.vn/b/' => 'b',
        'http://appstore.vn/c/' => 'c',
        'http://appstore.vn/e/' => 'e',
        'http://appstore.vn/f/' => 'f',
        'http://appstore.vn/i/' => 'i',
        'http://appstore.vn/trunk/' => 'i',
        'http://app.vn/' => 'all'
        
    );    

	function User()
	{
		parent::Controller();
        $this->load->model('user_model');
        $this->load->library('session');
        $this->load->library('email');
	}
	
	function index()
	{
		echo $this->session->userdata('logged_in');
        //$this->load->view('user_test');
	}
        
        // tao link so xo VDEC
        function generateVDEC() {
            if($this->session->userdata('logged_in')) {
                $userId = $this->session->userdata('userid');
                $username = $this->session->userdata('username');
                $partner_name = 'vdec';
                $secret_code = 'vdecabc@123';
                $time = time(true);
                $hash = md5($partner_name.$userId.$time.$userId.$username.$secret_code);
                $link = "http://xs.appstore.vn/?userid=$userId&username=$username&appstoreid=$userId&time=$time&hash=$hash";
            } else {
                $link = '#';	
            }
            echo $link;
        }

    function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if (!$username || !$password) {
            echo 'fail';
            return;
        }
        $inactiveUser = $this->user_model->getUserByUsername($username);
        $activeUser = $this->user_model->getActiveUserByUsername($username);
        if ($inactiveUser!=null && $activeUser==null) {
            echo 'inactive';
            return;
        }

        $password = md5($password);
        if ($activeUser!=null && $password==$activeUser->password) {
            // Successful
            $this->session->set_userdata('logged_in', 1);
            $this->session->set_userdata('userid', $activeUser->user_id);
            $this->session->set_userdata('username', $activeUser->username);
            $this->session->set_userdata('style', $activeUser->style);
            $data['logged'] = $this->session->userdata('logged_in');
            $data['username'] = $this->session->userdata('username');
            $data['user'] = $activeUser;
            
            // cộng tym cho hành động login
            $this->load->model('useraction_model');
            $action = $this->useraction_model->getActionByName('login');
            if($action->enable) {
                $userId = $this->session->userdata('userid');
                $logData['userid'] = $userId;
                $logData['actionid'] = $action->id;
                $logData['time'] = microtime(true);
                for($i=1; $i<=4; $i++) {
                    $tymType = "t$i";
                    $amount = $action->$tymType;
                    $this->user_model->increaseTym($userId, $tymType, $amount);
                    $logData[$tymType] = $amount;
                }
                $this->useraction_model->addLog($logData);
            }
            // kiểm tra gói
            $package_expired = 1;
            $userId = $activeUser->user_id;
            $store = $this->_store[base_url()];
            $userPack = $this->user_model->getUserPack($userId, $store);
            $now = microtime(true);
            if($userPack) {
                $package_expired = $userPack->package_expired;
                if($now > $package_expired || !$package_expired) {
                    $package_expired = 1;
                } else {
                    $package_expired = 0;
                }
            }
            $data['package_expired'] = $package_expired; 
            echo $this->load->view('user_box', $data, true);
            return;
        } else {
            // Failure
            $this->session->set_userdata('logged_in', 0);
            echo 'fail';
            return;
        }
    }

    function register() {
        if($this->session->userdata('logged_in')) redirect('home/infouser');
        $this->load->view('register');
    }

    function logout() {
    	 //$this->session->unset_userdata('session_id');
         $this->session->unset_userdata('logged_in');
         $this->session->unset_userdata('userid');
         $this->session->unset_userdata('username');
         $this->session->unset_userdata('read_popup');
         $data['logged'] = 0;
         $data['username'] = '';
         echo $this->load->view('user_box', $data, true);
    }

    function checkExistsUsername($username) {
    	$where = array(
    		'username' => $username,
    		'active_by' => 'sms'
    	);
        if ($this->user_model->isExistsField($where)) {
        	echo "1";exit;
        }
        $where1 = array(
    		'username' => $username,
    		'active_by' => 'email'
    	);
        if ($this->user_model->isExistsField($where1)) {
        	echo "1";exit;
        }
		echo "0";
    }
    
    function checkExistsEmail() {
    	$email = $this->input->post('email');
    	$data = array('email' => $email, 'type' => 'user');
        if ($this->user_model->isExistsField($data)) {
			echo "1";
        }
        else
            echo "0";
    }    
    
    /*
     *  gọi từ hàm registerUser ở view register.php
     */
    function registerUser() {
    	$username = $this->input->post('username');
    	$password = md5($this->input->post('password'));
    	$city = $this->input->post('city');
        
        $user = $this->user_model->getUserByField(array('username'=>$username, 'active_by'=>'inactive'));
        $useractive = $this->user_model->getUserByField(array('username'=>$username, 'active_by !='=>'inactive'));
        $userId = -1;
        if($user) {
            $dataUpdate = array(
                'username' => $username,
                'password' => $password,
                'email' => '',
                'city' => $city,
                'active_by' => 'inactive',
                'type' => 'user'
            );
            // update inactive user
            $this->user_model->update($user->user_id, $dataUpdate);
            $userId = $user->user_id; 
            echo $userId;
            exit;
        } 
        if ($useractive) {
            echo "-1";
            exit;
        } else {
            $dataInsert = array(
                'username' => $username,
                'password' => $password,
                'city' => $city,
                'active_by' => 'inactive',
                'type' => 'user'
            );
            $userId = $this->user_model->add($dataInsert);
            echo $userId;
            exit;
        }
    	
    }
    
    /*
     * kiểm tra email và gửi email kích hoạt
     * gọi từ hàm sendEmailActive từ view register.php
     */
    function sendEmailActive() {
    	$email = $this->input->post('email');
    	
    	$userid = $this->input->post('userid');
        
        //kích hoạt luôn
        $updateData = array(
            'email' => $email,
            'active_by' => 'email'
        );
        $this->user_model->update($userid, $updateData);
        $user = $this->user_model->getUserById($userid);
        $this->session->set_userdata('logged_in', 1);
        $this->session->set_userdata('userid', $userid);
        $this->session->set_userdata('username', $user->username);
        echo "1";exit;
        
    		$active_code = $this->user_model->randomCode(32);
			$updateData = array(
				'email' => $email,
				'active_code' => $active_code
			);
    		$this->user_model->update($userid, $updateData);

        // send email
    	$from = "admin@appstore.vn";
    	$to = $email;
    	$subject = "Kích hoạt tài khoản trên AppStore.Vn!";
    	$message = "Bạn vừa đăng kí một tài khoản trên AppStore.Vn!<br />";
    	$message .= "Tài khoản của bạn cần phải kích hoạt mới sử dụng , <a href='" . site_url("user/active/$active_code") ."'>Nhấn vào đây để kích hoạt</a><br />";
    	$message .= "Nếu không được, hãy copy link sau vào trình duyệt ' " . site_url("user/active/$active_code") . " ' để kích hoạt <br />"; 
    	$message .= "Hãy trở thành thành viên để khám phá kho ứng dụng khổng lồ trên AppStore.Vn";
    	$senddate = (date("d M Y h:m:s -0500"));
    	$extraheaders = "From: $from" . "\nContent-Type: text/html\n";
        @mail("$to", "$subject", "$message", $extraheaders);
            
        /*
        // send email smtp
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'noreply@appstore.vn';
        $config['smtp_pass']    = '1357924680@gsmisds';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->email->initialize($config);

        $this->email->from('noreply@appstore.vn', 'AppStore.VN');
        $this->email->to($email); 

        $this->email->subject($subject);
        $this->email->message($message);  

        $this->email->send();

        echo $this->email->print_debugger();             
 
        */
        
    	echo "1"; 	
    }
    
    /*
     * active user
     */
    function active($active_code = '0') {
    	echo '<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />';
    	$userId = $this->user_model->isExistsField(array('active_code'=>$active_code));
    	if($userId && $active_code) {
    		$data = array(
    			'active_by' => 'email',
    			'type' => 'user',
    			'active_code' => ''
    		);
    		$this->user_model->update($userId, $data);
    		//cộng tym cho lần đầu kích hoạt
    		$this->user_model->increaseTym($userId, 't2', 40);
    		$user = $this->user_model->getUserById($userId);
    		echo '<center>Tài khoản <b>' . $user->username . '</b> đã kích hoạt thành công ... đang chuyển hướng về trang chủ</center>';
    		$userLogged = array(
                'logged_in' => 1,
                'userid' => $userId,
                'username' => $user->username                 
            );
            $this->session->set_userdata($userLogged);
    	} else {
    		echo '<center>Mã kích hoạt không đúng hoặc đã được kích hoạt ... đang chuyển hướng về trang chủ</center>';
    	}
    	echo '<script>var timeout = setTimeout("window.location.href=\'' . base_url() . '\'", 3000);</script>';
    }
    
    function resetPassword() {
    	$username = $this->input->post('username');
    	$email = $this->input->post('email');
    		$dataCheck = array(
    			'username' => $username,
    			'email' => $email
    		);
    		$userId = $this->user_model->isExistsField($dataCheck);
    		if($userId) {
    			//send pass
    			$password = random_string('alnum', 8);
    				$user = $this->user_model->getUserById($userId);
    				$dbpassword = md5($password . $user->salt);
    				$dataUpdate = array(
    					'password' => $dbpassword
    				);
    				$this->user_model->update($userId, $dataUpdate);
    			//send email	
    			$from = "admin@appstore.vn";
		    	$to = $email;
		    	$subject = "Mật khẩu tài khoản trên AppStore.VN!";
		    	$message = "Xin chào $username!<br />";
		    	$message .= "Bạn vừa yêu cầu mật khẩu mới trên AppStore.Vn!<br />";
		    	$message .= "Mật khẩu mới của bạn là $password<br />";
		    	$message .= "Chúc bạn đăng nhập thành công!";
		    	$senddate = (date("d M Y h:m:s -0500"));
		    	$extraheaders = "From: $from" . "\nContent-Type: text/html\n";
		    	@mail("$to", "$subject", "$message", $extraheaders);
                
		    	/*
                // send email smtp
                $config['protocol']    = 'smtp';
                $config['smtp_host']    = 'ssl://smtp.gmail.com';
                $config['smtp_port']    = '465';
                $config['smtp_timeout'] = '7';
                $config['smtp_user']    = 'noreply@appstore.vn';
                $config['smtp_pass']    = '1357924680@gsmisds';
                $config['charset']    = 'utf-8';
                $config['newline']    = "\r\n";
                $config['mailtype'] = 'html'; // or html
                $config['validation'] = TRUE; // bool whether to validate email or not

                $this->email->initialize($config);
                
                $this->email->from('noreply@appstore.vn', 'AppStore.VN');
                $this->email->to($email); 

                $this->email->subject($subject);
                $this->email->message($message);  

                $this->email->send();

                echo $this->email->print_debugger();        
                */        
   
                echo "1"; 	
    		} else {
    			echo "fail";
    		}
    }
    
    function changeStyle() {
        $style = $this->input->post('style');
        $this->session->set_userdata('style', $style);
        $data = array('style' => $style);
        if($this->session->userdata('userid')) {
            $userId = $this->session->userdata('userid');
            if(isset($_POST['gender'])) $data['gender'] = $this->input->post('gender');
            $this->user_model->update($userId, $data);
            echo '1';
        } else {
            echo '0';
        }
    }
}
