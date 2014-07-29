<?php
class Fshare {
    public $fshare_key = 'JDhhJPLd8HogzDhHE31naoK11K84_X';
    public $user_id;
    public $email;
    public $password = '123465';
    public $session_id;
    public $fshare_email;
    
    private $CI;
    private $soap;
    
    function Fshare() {
        $this->CI =& get_instance();
        $this->CI->load->model('user_model');
        $this->CI->load->library('Nusoap2');
        $this->soap = new nu_soapclient('http://www.fshare.vn/ws/ws_member.php');
        $this->soap->soap_defencoding = "utf-8";
        $this->soap->decode_utf8 = false;   
    }
    
    function login($appUserId) {
        // neu la guest, tao user session, luu session vao db
        $fshare_session = $this->CI->session->userdata('fshare_session');
        $fshare_id = $this->CI->session->userdata('fshare_id');
        if(!$appUserId) {
            if($fshare_session && $fshare_id == 'ko dung session') {
                $this->session_id = $fshare_session;
                $this->user_id = $fshare_id;
                $this->password = $this->CI->session->userdata('fshare_password');
                return true;
            } else {
                // login = user vip
                $user_email = 'tiennv@gsm.vn';
                $password = '17249xkeke';
                $loginReq = array(
                    'user_email'         => $user_email,
                    'user_password'      => $password,
                    'key'                => $this->fshare_key    
                );
                $loginRes = $this->fshare_login($loginReq);
                if($loginRes) {
                    $fshare_id = $loginRes['user_id'];
                    $session_id = $loginRes['session_id'];
                    $fshare_email = $user_email;
                    // cap nhat fshare_id, fshare_session, fshare_email vao session
                    $this->CI->session->set_userdata('fshare_session', $session_id);
                    $this->CI->session->set_userdata('fshare_id', $fshare_id);
                    $this->CI->session->set_userdata('fshare_email', $fshare_email);
                    $this->CI->session->set_userdata('fshare_password', $password);
                    // gan thuoc tinh cho doi tuong fshare
                    $this->user_id = $fshare_id;
                    $this->session_id = $session_id;
                    $this->fshare_email = $user_email;
                    $this->password = $password;
                    return true;
                } else {
                    return false;
                }
            }
        }
        // neu la user
        $appUser = $this->CI->user_model->getUserById($appUserId);
        $sessionId = $appUser->fshare_session;
        if($sessionId) {
            $session = unserialize($sessionId);
        } else {
            $session = false;
        }
        if(isset($session['session_id'])) {
            $this->session_id = $session['session_id'];
            $this->user_id = $appUser->fshare_id;
            return true;
        } else {
            if($appUser->fshare_id) {
                $loginReq = array(
                    'user_email'         => $appUser->fshare_email,
                    'user_password'      => $this->password,
                    'key'                => $this->fshare_key    
                );
                $loginRes = $this->fshare_login($loginReq);  
                if(!$loginRes) {
                    //dang nhap khong thanh cong -> ma loi
                    return false;
                } else {
                    $this->user_id = $loginRes["user_id"]; 
                    $this->session_id = $loginRes["session_id"];
                    //luu vao db fshare_id, fshare_session
                    $dataSession = array(
                        'session_id' => $this->session_id,
                        'time' => time()
                    );
                    $dataUser = array(
                        'fshare_id' => $this->user_id,
                        'fshare_session' => serialize($dataSession)
                    );
                    $this->CI->user_model->update($appUserId, $dataUser);
                    return true;
                }
            } else {
                $this->register($appUserId);
            }
        }
    }
	
	function fshare_login($loginReq) {
        $loginRes = $this->soap->call('logIn', array('req' => $loginReq));  
        $err = $this->soap->getError();   
		if($err) {
			return false;
		} else {
			if($loginRes['returnCode'] == 0) {
				return $loginRes;
			} else {
				return false;
			}
		}
	}

	function register($appUserId) {
        $appUser = $this->CI->user_model->getUserById($appUserId);	
        $appEmail = $appUser->email;

        // người dùng có email thật
        if($appEmail) {
            $registerReq = array(
                'user_email' => $appEmail,
                'user_password' => $this->password,
                'user_fullname' => $appUser->username,
                'key' => $this->fshare_key
            );
            $registerRes = $this->soap->call('registerFshare', array('req' => $registerReq));
            $err = $this->soap->getError();
            if($err) {
                /* 
                 * nếu là lỗi đã tồn tại email trên fshare
                 * thì dùng email tự sinh để đăng ký
                 */
                $returnStr = $registerRes['result'];
                $returnArr = explode('|', $returnStr);
                $returnCode = $returnArr[0];
                /*
                 * email thật của người dùng trên AppStore đã tồn tại trên Fshare
                 * dùng email ảo username@gmail.com|yahoo.com
                 */
                if($returnCode == '66') {
                    // dùng email ảo username@gmail.com|hotmail.com
                    $emailSuffix = array('@gmail.com', '@hotmail.com', '@yahoo.com', '@zing.vn');
                    $email = $emailSuffix[time()%4];
                    $registerReq = array(
                        'user_email' => $email,
                        'user_password' => $this->password,
                        'user_fullname' => 'appstore_'.$appUser->username,
                        'key' => $this->fshare_key
                    );
                    $registerRes = $this->soap->call('registerFshare', array('req' => $registerReq));
                    $err = $this->soap->getError();
                    if($err) {
                        // chịu thật rồi
                        return false;
                    // ko bi loi dang ki nick username@apsptore
                    } else {
                        // luu fshare_id, dang nhap vao fshare, luu fshare_session
                        $loginReq = array(
                            'user_email'         => $email,
                            'user_password'      => $this->password,
                            'key'                => $this->fshare_key    
                        );
                        $loginRes = $this->fshare_login($loginReq);
                        if($loginRes) {
                            $fshare_id = $loginRes['user_id'];
                            $session_id = $loginRes['session_id'];
                            $fshare_email = $email;
                            $this->register_session($appUserId, $email, $session_id, $fshare_id);
                            return true;
                        }
                    }
                    // /ko bi loi dang ki nick username@apsptore
                // /ko ton tai email that
                } else {
                    
                }
            } else {
                // luu fshare_id, dang nhap vao fshare, luu fshare_session
                $loginReq = array(
                    'user_email'         => $appEmail,
                    'user_password'      => $this->password,
                    'key'                => $this->fshare_key    
                );
                $loginRes = $this->fshare_login($loginReq);
                if($loginRes) {
                    $fshare_id = $loginRes['user_id'];
                    $session_id = $loginRes['session_id'];
                    $fshare_email = $appEmail;
                    $this->register_session($appUserId, $appEmail, $session_id, $fshare_id);
                    return true;
                }
            }
        } else {
            // dùng email ảo username@gmail.com|hotmail.com
            $emailSuffix = array('@gmail.com', '@hotmail.com', '@yahoo.com', '@zing.vn');
            $email = $appUser->username.$emailSuffix[rand(0,3)];
            $registerReq = array(
                'user_email' => $email,
                'user_password' => $this->password,
                'user_fullname' => $appUser->username,
                'key' => $this->fshare_key
            );
            $registerRes = $this->soap->call('registerFshare', array('req' => $registerReq));
            $err = $this->soap->getError();
            if($err) {
                // chịu thật rồi
                return false;
            } else {
                // luu fshare_id, dang nhap vao fshare, luu fshare_session
                $loginReq = array(
                    'user_email'         => $email,
                    'user_password'      => $this->password,
                    'key'                => $this->fshare_key    
                );
                $loginRes = $this->fshare_login($loginReq);
                if($loginRes) {
                    $fshare_id = $loginRes['user_id'];
                    $session_id = $loginRes['session_id'];
                    $fshare_email = $email;
                    $this->register_session($appUserId, $email, $session_id, $fshare_id);
                    return true;
                }
            }
        }
	}
    
    function register_session($appUserId, $email, $session_id, $fshare_id) {
        $sessionData = array(
            'session_id' => $session_id,
            'time' => time()
        );
        $fshare_session = serialize($sessionData);
        // cap nhat fshare_id, fshare_session
        $userData = array(
            'fshare_id' => $fshare_id,
            'fshare_session' => $fshare_session,
            'fshare_email' => $email
        );
        $this->CI->user_model->update($appUserId, $userData);
        // gan thuoc tinh cho doi tuong
        $this->user_id = $fshare_id;
        $this->session_id = $session_id;
        $this->fshare_email = $email;
    }
        
    // for debug        
    function setVariable($user_id, $password, $session_id) {
        $this->user_id = $user_id;
        $this->password = $password;
        $this->session_id = $session_id;
    }

    function getKey() {
        return $this->fshare_key;
    }
    // end debug

	function getLink($link) {
        $fileReq = array(
            'key' => $this->fshare_key,
            'user_id' => $this->user_id,
            'user_password' => $this->password,
            'session_id' => $this->session_id,
            'file_linkcode' => $link
        );
        $fileWs = new nu_soapclient('http://www.fshare.vn/ws/ws_file.php');
        $fileWs->soap_defencoding = "utf-8";
        $fileWs->decode_utf8 = false;   
            $fileRes = $fileWs->call('genFileDownload', array('req' => $fileReq));
            $err = $fileWs->getError();
            if($err) {
                // tra ve ma loi
            } else {
                if(isset($fileRes['link_download'])) {
                    return trim($fileRes['link_download']);
                } else return false;    
            }
	}

	/*
	 * priceData - Object Class
	 * 	- price_id
	 *	- price_tym
     *  - real_day
	*/
	function updateVIP($appUserId, $priceData) {
        $appUser = $this->CI->user_model->getUserById($appUserId);	
	    $updateVipReq = array(
            'user_id' => $this->user_id,
            'user_password' => $this->password,
            'session_id' => $this->session_id,
            'price_id' => $priceData->price_id,
            'key' => $this->fshare_key
        );
        //$updateVipRes = $this->soap->call('updateVip', array('req' => $updateVipReq));
        //$err = $this->soap->getError();
        // debug
        $err = false;
        if($err) {
            // cap nhat VIP that bai -> ma loi
            return 2;
        } else {
            // tru tym cua nguoi dung
            $result = $this->CI->user_model->decreaseTym($appUserId, 't1', $priceData->price_tym);
            if(!$result) {
                return 0;   // ko du tym
            }
            // cap nhat VIP cho user
            $curPack = $this->CI->user_model->getDownloadVipByUserId($appUserId, 'fshare');
            $last_expired_date = 0;
            $expired_date = 0;
            $registered_data = time();
            if($curPack) {
                $curExpire = $curPack->dvip_expired;
                $last_expired_date = $curExpire;
                if($curExpire < time()) {
                    $expire = time() + $priceData->real_day * 24 * 60 * 60;
                } else {
                    $expire = $curExpire + $priceData->real_day * 24 * 60 * 60;
                }
                $expired_date = $expire;
                $vipUpdate = array(
                    'dvip_expired' => $expire
                );
                $this->CI->user_model->updateDownloadVip($appUserId, 'fshare', $vipUpdate);
            } else {
                $expire = time() + $priceData->real_day * 24 * 60 * 60;
                $last_expired_date = time();
                $expired_date  = $expire;
                $vipData = array(
                    'user_id' => $appUserId,
                    'dvip_type' => 'p'.$priceData->real_day,
                    'dvip_expired' => $expire,
                    'dvip_store' => 'fshare'
                );
                $this->CI->user_model->addDownloadVip($vipData);
            }
            // luu log dang ki VIP	
            $logData = array(
                'user_id' => $appUserId,
                'dvip_type' => 'p'.$priceData->real_day,
                'expired_date' => $expired_date,
                'last_expired_date' => $last_expired_date,
                'registered_date' => $registered_data,
                'tym_price' => $priceData->price_tym,
                'dvip_store' => 'fshare'
            );
            $this->CI->user_model->addDownloadVipLog($logData);
            return 99;
        }
    }
}
?>
