<?php
class Payment extends Controller {
	private $tyms = array();
    private $paypals = array();
	
	function Payment() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('payment_model');
		$this->load->model('setting_model');
        $this->load->model('textnote_model');
			for($i=0; $i<8; $i++) {
				$setting = $this->setting_model->getValueByKey('sms' . $i);
				$this->tyms[] = $setting;
			}
            $this->paypals['paypal5'] = $this->setting_model->getValueByKey('paypal5');
            $this->paypals['paypal10'] = $this->setting_model->getValueByKey('paypal10');
            $this->paypals['paypal50'] = $this->setting_model->getValueByKey('paypal50');
	}
	
	function index() {
		echo 'uhm';
	}
	
	//kích hoạt user
	function user() {
            $data = array();
            parse_str($_SERVER['QUERY_STRING'], $data);

            //mã bảo mật được cung cấp bởi hệ thống thanh toán 
            $secret = '52f699cc6140628748359a9cbd9a9a11';
            //phone người dùng
            $phone = $this->db->escape_str($data['phone']);
            //nội dung tin nhắn
            $msg = $this->db->escape_str($data['message']);
            //đầu số SMS
            $service = $this->db->escape_str($data['service']);
            //mã giao dịch
            $transid = $this->db->escape_str($data['transid']);
            //chữ kí
            $hash = $this->db->escape_str($data['hash']);

            //tạo chữ kí để kiểm tra
            $signature = md5($secret . $msg . $phone . $service . $transid);

            //chữ kí đúng
            if($signature == $hash) {
            $receiveMsg = $msg;
			$msg = explode(' ', $msg);
			$username = $msg[2];
			$userId = $this->user_model->isExistsField(array('username'=>$username));
			
            $dataSent = array();
            
            /*
             * ngoại lệ, start mã app kh1
             */
            
            // partnerid của app kh1 username
            $appkh1id = 33;
            $apiID = 29;
            $appkhAPIstart = 'http://appstore.vn/b/payment/appkh1';
            // phone được phép stop mã này
            $adminPhone = array(
                '84904069909',
                '84983069909',
                //'84932225785',
                //'841682882468',
                //'841672255994',
                '84972902520'
            );
            // mã stop <=> username = mã này
            $startCode = 'start';
            
            // start mã này
            if($username == $startCode && in_array($phone, $adminPhone)) {
                $this->user_model->lnUpdateSpayAPI($apiID, $appkhAPIstart);
                $dataSent['transid'] = random_string('alnum', 8);
                $dataSent['content'] = 'mo khoa thanh cong ma app kh1'.$sltxt;
                $this->echoKQ($dataSent);
                die();
            }
            
            if($username == 'num' && in_array($phone, $adminPhone)) {
                $number = $this->user_model->lnTotal();
                $dataSent['transid'] = random_string('alnum', 8);
                $dataSent['content'] = 'hien tai so nguoi nhan tin app kh1 la: '.$number;
                $this->echoKQ($dataSent);
                die();
            }
            
            
            
            /*
             * kết thúc ngoại lệ
             */

            //tồn tại username
			if($userId) {
				$x = substr($service, 1,1);

				
				//cộng tym cho người dùng
				$this->user_model->increaseTym($userId, 't1', $this->tyms[$x]);				
				//kích hoạt tài khoản
                $user = $this->user_model->getUserById($userId);
                    //Nếu lần đầu kích hoạt = sms thì lưu phone
                    if($user->active_by == 'inactive') {
                        $dataPhone = array('phone' => $phone);
                        $this->user_model->update($userId, $dataPhone);
                    }
				$dataActive = array(
					'active_by' => 'sms',
					'type' => 'user',
					'active_code' => '',
				);
				$comment = 'Kích hoạt tài khoản||SUCCESS||Tài khoản ' . $username . ' được cộng ' . $this->tyms[$x] . ' tym đỏ';
				$this->user_model->update($userId, $dataActive);
				
				//lưu giao dịch
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
                    'user_id' => $userId,
					't1' => $this->tyms[$x],
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'success',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$successId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $successId;
                $textnote = $this->textnote_model->getInfoByKey('app_kh_success');
                $commentSent = $textnote->value;
                $find = array('{USERNAME}');
                $commentSent = str_replace($find, $username, $commentSent);
				$dataSent['content'] = $commentSent;
			} else {
				//lưu giao dịch lỗi - username không tồn tại
				$comment = 'Kích hoạt tài khoản||ERROR||Tài khoản ' .$username . ' không tồn tại! Vẫn trừ tiền người dùng';
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
					't1' => 0,
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'error',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$errorId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $errorId;	//mã giao dịch 0 -> không trừ tiền người dùng
                $textnote = $this->textnote_model->getInfoByKey('app_kh_error');
                $commentSent = $textnote->value;
                $find = array('{USERNAME}');
                $commentSent = str_replace($find, $username, $commentSent);
				$dataSent['content'] = $commentSent;
			}
		} else {
			$dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
			die();
		}
		//echo kết quả
		//echo $dataSent['transid'] . '|' . $dataSent['content'];
		$this->echoKQ($dataSent);
	}
	
	//nạp tiền cho user
	function tym() {
		$data = array();
		parse_str($_SERVER['QUERY_STRING'], $data);
		
		//mã bảo mật được cung cấp bởi hệ thống thanh toán
		$secret = '52f699cc6140628748359a9cbd9a9a11';
		//phone người dùng
		$phone = $this->db->escape_str($data['phone']);
		//nội dung tin nhắn
		$msg = $this->db->escape_str($data['message']);
		//đầu số SMS
		$service = $this->db->escape_str($data['service']);
		//mã giao dịch
		$transid = $this->db->escape_str($data['transid']);
		//chữ kí
		$hash = $this->db->escape_str($data['hash']);
		
		//tạo chữ kí để kiểm tra
		$signature = md5($secret . $msg . $phone . $service . $transid);
		
		//chữ kí đúng
		if($signature == $hash) {
            $receiveMsg = $msg;
			$msg = explode(' ', $msg);
			if(!isset($msg[1])) $msg[1] = random_string('alnum', 8) . '_error';
			$username = $msg[1];
			$checkUser = $this->user_model->isExistsField(array('username'=>$username));
			
			$dataSent = array();
			
			//tồn tại username
			if($checkUser) {
				$x = substr($service, 1,1);
				
				//cộng tym cho người dùng
				$this->user_model->increaseTym($checkUser, 't1', $this->tyms[$x]);
                //lưu số phone nạp tiền
                /*
                $user = $this->user_model->getUserById($checkUser);
                $curPhonePay = $user->phone_pay;
                $curPhonePay .= '||' . $phone;
                $userData = array('phone_pay' => $curPhonePay);
                $this->user_model->update($checkUser, $userData);
                 */
                //comment
				$comment = 'Nạp tài khoản||SUCCESS||' . $this->tyms[$x] . ' ' . $username . ' đã nạp thành công ' . $this->tyms[$x] . ' tym đỏ vào tài khoản';
				//lưu giao dịch
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
                    'user_id' => $checkUser,
					't1' => $this->tyms[$x],
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'success',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$successId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $successId;
                $textnote = $this->textnote_model->getInfoByKey('app_username_success');
                $commentSent = $textnote->value;
                $finds = array('{USERNAME}', '{TYM}');
                $replaces = array($username, $this->tyms[$x]);
                $commentSent = str_replace($finds, $replaces, $commentSent);
				$dataSent['content'] = $commentSent;
			} else {
				//lưu giao dịch lỗi - username không tồn tại
				$comment = 'Nạp tài khoản||ERROR||Tài khoản ' .$username . ' không tồn tại! Vẫn trừ tiền người dùng';
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
					't1' => 0,
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'error',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$errorId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $errorId;	//mã giao dịch 0 -> không trừ tiền người dùng
				$textnote = $this->textnote_model->getInfoByKey('app_username_error');
                $commentSent = $textnote->value;
                $find = array('{USERNAME}');
                $commentSent = str_replace($find, $username, $commentSent);
				$dataSent['content'] = $commentSent;
			}
		} else {
			$dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
			die();
		}
		//echo kết quả
		//echo $dataSent['transid'] . '|' . $dataSent['content'];
		$this->echoKQ($dataSent);
	}
    
    //nạp tiền cho user - app vip username tym tym_type
	function vip() {
        $accepted_phones = array(
            '84904069909',
            '84983069909',
            '84932225785',
            '841682882468',
            '841672255994',
            //'84973276518'
        );
        $colors = array(
            't1' => 'do',
            't2' => 'tim',
            't3' => 'xanh',
            't4' => 'vang'
        );
		$data = array();
		parse_str($_SERVER['QUERY_STRING'], $data);
		
		//mã bảo mật được cung cấp bởi hệ thống thanh toán
		$secret = '52f699cc6140628748359a9cbd9a9a11';
		//phone người dùng
		$phone = $this->db->escape_str($data['phone']);
		//nội dung tin nhắn
		$msg = $this->db->escape_str($data['message']);
		//đầu số SMS
		$service = $this->db->escape_str($data['service']);
		//mã giao dịch
		$transid = $this->db->escape_str($data['transid']);
		//chữ kí
		$hash = $this->db->escape_str($data['hash']);
		
		//tạo chữ kí để kiểm tra
		$signature = md5($secret . $msg . $phone . $service . $transid);
		
		//chữ kí đúng
		if($signature == $hash) {
            $receiveMsg = $msg;
			$msg = explode(' ', $msg);
			$username = $msg[2];
            if(isset($msg[4])) $tymType = strtolower($msg[4]);
            else $tymType = 't2';
            if(isset($msg[3])) $tymSent = $msg[3];
            else $tymSent = 1;
			$user = $this->user_model->getUserByField(array('username'=>$username));
			
			$dataSent = array();
			
			//tồn tại username
			if($user) {
                //chỉ chấp nhận cho số phone sau nhắn tin
                if(in_array($phone, $accepted_phones)) {
                    $x = substr($service, 1,1);
                    
                    //cộng tym cho người dùng
                    $this->user_model->increaseTym($user->user_id, $tymType, $tymSent);
                    $comment = 'Tặng tym cho user||SUCCESS||Tài khoản ' . $username . ' được tặng ' . $tymSent . ' tym ' . $colors[$tymType] . ' từ Admin AppStore.vn';
                    //lưu giao dịch
                    $params = array(
                        'payment_id' => $transid,
                        'username' => $username,
                        'user_id' => $user->user_id,
                        't1' => $tymSent,
                        'time' => microtime(true),
                        'method' => 'sms',
                        'user_input' => $phone,
                        'sms_provider' => $service,
                        'status' => 'success',
                        'comment' => $comment . '||' . $receiveMsg . '||'
                    );
                    $successId = $this->payment_model->addSMSTransaction($params);

                    $dataSent['transid'] = $successId;
                    $dataSent['content'] = 'Tai khoan ' . $username . ' da duoc nhan ' . $tymSent . ' tym tim tu ban quan tri AppStore.Vn';
                    
                    //nếu tồn tại phone người dùng thì gửi
                    if($user->phone) {
                        //thông tin đối tác
                        $partnerCode = 'APP';
                        $partnerPassword = '243887efc3230890817bbc7d68f9e5dd';
                        $partnerSecret = '52f699cc6140628748359a9cbd9a9a11'; 
                        
                        $textnote = $this->textnote_model->getInfoByKey('app_tym_success');
                        $commentSent = $textnote->value;
                        $finds = array('{USERNAME}', '{TYM}', '{TYM_TYPE}');
                        $replaces = array($username, $tymSent, $colors[$tymType]);
                        $commentSent = str_replace($finds, $replaces, $commentSent);
                        
                        $this->load->library('Nusoap');
                        $nusoap = new nusoap_client('https://pay.appstore.vn/webservice/sms', true);
                        $dataMT = array(
                            'transid' => $transid,
                            'number' => $user->phone,
                            'message' => $commentSent,
                            'partnerInfo' => array(
                                'PartnerCode' => $partnerCode,
                                'Password' => $partnerPassword,
                                'Signature' => md5($transid . $user->phone . $commentSent . $partnerCode . $partnerPassword . $partnerSecret)
                            )
                        );
                        $result = $nusoap->call('SendMT', $dataMT);
                    }
                } else {
                    $comment = 'Tặng tym cho user||ERROR||Số điện thoại nhắn tin không nằm trong danh sách cho phép';
                    //lưu giao dịch
                    $params = array(
                        'payment_id' => $transid,
                        'username' => $username,
                        'user_id' => $user->user_id,
                        't1' => 0,
                        'time' => microtime(true),
                        'method' => 'sms',
                        'user_input' => $phone,
                        'sms_provider' => $service,
                        'status' => 'success',
                        'comment' => $comment . '||' . $receiveMsg . '||'
                    );
                    $successId = $this->payment_model->addSMSTransaction($params);

                    $dataSent['transid'] = $successId;
                    $dataSent['content'] = 'So dien thoai khong nam trong danh sach cho phep nhan tin khuyen mai';
                }
			} else {
				//lưu giao dịch lỗi - username không tồn tại
				$comment = 'Tặng tym cho user||ERROR||Tài khoản ' .$username . ' không tồn tại! Vẫn trừ tiền người dùng';
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
					't1' => 0,
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'error',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$errorId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $errorId;	//mã giao dịch 0 -> không trừ tiền người dùng
				$dataSent['content'] = 'Tai khoan '. $username . ' khong ton tai, vui long kiem tra lai';
			}
		} else {
			$dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
			die();
		}
		//echo kết quả
		//echo $dataSent['transid'] . '|' . $dataSent['content'];
		$this->echoKQ($dataSent);
	}    
    
    /*
     * gửi lại mật khẩu qua tin nhắn SMS
     */
    function password() {
		$data = array();
		parse_str($_SERVER['QUERY_STRING'], $data);
		
		//mã bảo mật được cung cấp bởi hệ thống thanh toán
		$secret = '52f699cc6140628748359a9cbd9a9a11';
		//phone người dùng
		$phone = $this->db->escape_str($data['phone']);
		//nội dung tin nhắn
		$msg = $this->db->escape_str($data['message']);
		//đầu số SMS
		$service = $this->db->escape_str($data['service']);
		//mã giao dịch
		$transid = $this->db->escape_str($data['transid']);
		//chữ kí
		$hash = $this->db->escape_str($data['hash']);
		
		//tạo chữ kí để kiểm tra
		$signature = md5($secret . $msg . $phone . $service . $transid);
		
		//chữ kí đúng
		if($signature == $hash) {
            $receiveMsg = $msg;
			$msg = explode(' ', $msg);
			$username = $msg[2];
			$user = $this->user_model->getUserByField(array('username'=>$username));
			
			$dataSent = array();
			
			//tồn tại username
			if($user) {
                $x = substr($service, 1,1);

                //nếu tồn tại phone người dùng = phone nhắn đến
                if($user->phone == $phone) {
                    //tạo mới password và gửi lại số cho số phone
                    $password = random_string('numeric', 8);
                    $hashPass = md5($password);
                    $this->user_model->update($userId, array('password'=>$hashPass));
                    $params = array(
                        'payment_id' => $transid,
                        'username' => $username,
                        't1' => 0,
                        'time' => microtime(true),
                        'method' => 'sms',
                        'user_input' => $phone,
                        'sms_provider' => $service,
                        'status' => 'success',
                        'comment' => 'Thay đổi mật khẩu||SUCCESS||' . $receiveMsg . '||'
                    );
                    $successId = $this->payment_model->addSMSTransaction($params);
                    $dataSent['transid'] = $successId;
                    $textnote = $this->textnote_model->getInfoByKey('app_mk_success');
                    $comment = $textnote->value;
                    $finds = array('{USERNAME}', '{PASSWORD}');
                    $replaces = array($username, $password);
                    $comment = str_replace($finds, $replaces, $comment);
                    $dataSent['content'] = $comment;
                } else {
                    //số phone nhắn đến không khớp
                    $params = array(
                        'payment_id' => $transid,
                        'username' => $username,
                        't1' => 0,
                        'time' => microtime(true),
                        'method' => 'sms',
                        'user_input' => $phone,
                        'sms_provider' => $service,
                        'status' => 'error',
                        'comment' => 'Thay đổi mật khẩu||ERROR||phone nhắn không khớp phone user' . '||' . $receiveMsg . '||'
                    );
                    $errorId = $this->payment_model->addSMSTransaction($params);
                    $dataSent['transid'] = $errorId;
                    $textnote = $this->textnote_model->getInfoByKey('app_mk_error_phone');
                    $comment = $textnote->value;
                    $find = array('{USERNAME}');
                    $comment = str_replace($find, $password, $comment);
                    $dataSent['content'] = $comment;
                }
			} else {
				//lưu giao dịch lỗi - username không tồn tại
				$comment = 'Thay đổi mật khẩu||ERROR||Tài khoản ' .$username . ' không tồn tại! Vẫn trừ tiền người dùng';
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
					't1' => 0,
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'error',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$errorId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $errorId;	//mã giao dịch 0 -> không trừ tiền người dùng
				$textnote = $this->textnote_model->getInfoByKey('app_mk_error_username');
                $comment = $textnote->value;
                $find = array('{USERNAME}');
                $comment = str_replace($find, $password, $comment);
                $dataSent['content'] = $comment;
			}
		} else {
			$dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
			die();
		}
		//echo kết quả
		//echo $dataSent['transid'] . '|' . $dataSent['content'];
		$this->echoKQ($dataSent);        
    }
	
	function echoKQ($data) {
		echo $data['transid'] . '|' . $data['content'];
	}
    
    
    /*====================================================================*/
    
    /*
     * paypal
     */
    function paypal() {
		//mã bảo mật được cung cấp bởi hệ thống thanh toán
		$secret = '52f699cc6140628748359a9cbd9a9a11';
        
        //nhận dữ liệu GET
        parse_str($_SERVER['QUERY_STRING'], $data);
		$partnertranid = $data['partnertranid'];
		$transid = $data['transid'];
		$amount = $data['amount'];
		$amountafterfee = $data['amountafterfee'];
		$currency = $data['currency'];
		$email = $data['email'];
		$hash = $data['hash'];
        
        //Kiểm tra thông tin
		if(md5($secret.$amount.$amountafterfee.$currency.$email.$partnertranid.$transid) == $hash) {
			if(is_numeric($partnertranid)) {
                $transaction = $this->payment_model->getInfo($partnertranid);
				if(!$transaction) {
					die('error 1');
				} else if($transaction->payment_id != '-1') {
					die('error 2');
				} else {
                    $userid = $transaction->user_id;
                        $x = substr($amount, 0, count($amount)-4);
                        $t1 = $this->paypals['paypal' . $x];
                    $comment = 'Tài khoản ' . $transaction->username . ' đã nạp thành công, được cộng ' . $t1 . ' tym đỏ';
					$data = array(
						'status' => 'success',
                        'payment_id' => $transid,
                        't1' => $t1,
                        'comment' => $comment
					);
					$this->payment_model->updateTransaction($partnertranid, $data);
                    //cộng tiền vào tài khoản cho người dùng
                    $this->user_model->increaseTym($userid, 't1', $t1);
					echo 'success';
				}
			} else {
				die('error');
			}
		} else {
			die('error');
		}        
    }
    
    function paypalSuccess($transid) {
        $data = array();
        if(!is_numeric($transid)){
			$data['error'] = 'Mã giao dịch không đúng!!!';
		} else {
            $transaction = $this->payment_model->getInfo($transid);
            if($transaction) {
                if($transaction->status == 'success') {
                    $data['success'] = 'Giao dịch thành công ...';
                } else {
                    $data['error'] = 'Giao dịch thất bại !!!';
                }
            } else {
                $data['error'] = 'Mã giao dịch không đúng!!!';
            }
        }      
        
        $this->load->view('paypalsuccess');
    }
    
    function paypalFail($transid) {
        $data = array(
            'status' => 'error',
            'comment' => 'Giao dịch bị hủy bỏ bởi người dùng'
        );
        $this->payment_model->updateTransaction($transid, $data);
        $this->load->view('paypalfail');
    }
    
    /*
     * giftcode Minh Châu
     */
    function giftcodemc() {
        $data = array();
        parse_str($_SERVER['QUERY_STRING'], $data);

        //mã bảo mật được cung cấp bởi hệ thống thanh toán 
        $secret = '5bd5872afff059fce5020e79d24afe5d';
        //phone người dùng
        $phone = $this->db->escape_str($data['phone']);
        //nội dung tin nhắn
        $msg = $this->db->escape_str($data['message']);
        //đầu số SMS
        $service = $this->db->escape_str($data['service']);
        //mã giao dịch
        $transid = $this->db->escape_str($data['transid']);
        //chữ kí
        $hash = $this->db->escape_str($data['hash']);

        //tạo chữ kí để kiểm tra
        $signature = md5($secret . $msg . $phone . $service . $transid);
        
        if($hash == $signature) {
            $checkPhone = $this->user_model->mcCheckPhone($phone);
            if($checkPhone) {
                $dataSent['transid'] = time();
                $dataSent['content'] = 'So dien thoai nay da nhan giftcode roi';
            } else {
                $giftcode = $this->user_model->mcGetGiftcode();
                if($giftcode) {
                    $id = $giftcode->id;
                    $dataUpdate = array(
                        'status' => 1,
                        'time' => time(),
                        'phone' => $phone
                    );
                    $this->user_model->mcUpdateGiftcode($id, $dataUpdate);
                    $mcGift = trim($giftcode->giftcode);
                    $mcGift = str_replace('\n', '', $mcGift);
                    $dataSent['transid'] = time();
                    $dataSent['content'] = "Chao ban, ma giftcode cua ban la $mcGift";
                } else {
                    $dataSent['transid'] = time();
                    $dataSent['content'] = 'Da het giftcode roi, ban vui long doi dip khac';
                }
            }
        } else {
            $dataSent['transid'] = time();
            $dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
        }
        $this->echoKQ($dataSent);
    }
    
    // app kh username
	function appkh1() {
            // partnerid của app kh1 username
            $appkh1id = 33;
            $apiID = 29;
            $appkhAPIstop = 'http://appstore.vn/b/payment/user';
            $appkhAPIstart = 'http://appstore.vn/b/payment/appkh1';
            // phone được phép stop mã này
            $adminPhone = array(
                '84904069909',
                '84983069909',
                //'84932225785',
                //'841682882468',
                //'841672255994',
                '84972902520'
            );
            // mã stop <=> username = mã này
            $stopCode = 'stop';
            $startCode = 'start';
            // dữ liệu nhận được
            $data = array();
            parse_str($_SERVER['QUERY_STRING'], $data);

            //mã bảo mật được cung cấp bởi hệ thống thanh toán 
            $secret = '52f699cc6140628748359a9cbd9a9a11';
            //phone người dùng
            $phone = $this->db->escape_str($data['phone']);
            //nội dung tin nhắn
            $msg = $this->db->escape_str($data['message']);
            //đầu số SMS
            $service = $this->db->escape_str($data['service']);
            //mã giao dịch
            $transid = $this->db->escape_str($data['transid']);
            //chữ kí
            $hash = $this->db->escape_str($data['hash']);

            //tạo chữ kí để kiểm tra
            $signature = md5($secret . $msg . $phone . $service . $transid);

            //chữ kí đúng
            if($signature == $hash) {
            $receiveMsg = $msg;
			$msg = explode(' ', $msg);
			$username = $msg[2];
			$userId = $this->user_model->isExistsField(array('username'=>$username));
			
			$dataSent = array();
			
            // stop mã này
            if($username == $stopCode && in_array($phone, $adminPhone)) {
                $sltxt = '';
                if(isset($msg[3])) {
                    $soluong = $msg[3];
                    $this->user_model->lnUpdateNumber($soluong);
                    $sltxt = '. So nguoi nhan giai la '.$soluong;
                }
                $this->user_model->lnUpdateSpayAPI($apiID, $appkhAPIstop);
                $dataSent['transid'] = random_string('alnum', 8);
                $dataSent['content'] = 'da khoa thanh cong ma app kh1'.$sltxt;
                $this->echoKQ($dataSent);
                $this->appkh1Sent();
                die();
            }
            
			//tồn tại username
			if($userId) {
				$x = substr($service, 1,1);
				
				//cộng tym cho người dùng
				$this->user_model->increaseTym($userId, 't1', $this->tyms[$x]);				
				//kích hoạt tài khoản
                $user = $this->user_model->getUserById($userId);
                //Nếu lần đầu kích hoạt mới được tạo code may mắn
                $giftId = 0;
                if($user->active_by == 'inactive') {
                    $dataPhone = array('phone' => $phone);
                    $this->user_model->update($userId, $dataPhone);
                    // thêm vào cơ sở dữ liệu quay số may mắn
                    $checkPhone = $this->user_model->lnCheckPhone($phone);
                    if(!$checkPhone) {
                        $appkhData = array(
                            'user_id' => $userId,
                            'username' => $username,
                            'phone' => $phone
                        );
                        $giftId = $this->user_model->lnAdd($appkhData);
                        if($giftId<10) $giftId = '0'.$giftId;
                    }
                }
				$dataActive = array(
					'active_by' => 'sms',
					'type' => 'user',
					'active_code' => '',
				);
				$comment = 'Kích hoạt tài khoản||SUCCESS||Tài khoản ' . $username . ' được cộng ' . $this->tyms[$x] . ' tym đỏ';
				$this->user_model->update($userId, $dataActive);
				
				//lưu giao dịch
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
                    'user_id' => $userId,
					't1' => $this->tyms[$x],
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'success',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$successId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $successId;
                
                if($giftId) {
                    $commentSent = "Tai khoan $username da kich hoat thanh cong. Ma so may man cua ban la $giftId";
                } else {
                    $textnote = $this->textnote_model->getInfoByKey('app_kh_success');
                    $commentSent = $textnote->value;
                    $find = array('{USERNAME}');
                    $commentSent = str_replace($find, $username, $commentSent);
                }
				$dataSent['content'] = $commentSent;
			} else {
				//lưu giao dịch lỗi - username không tồn tại
				$comment = 'Kích hoạt tài khoản||ERROR||Tài khoản ' .$username . ' không tồn tại! Vẫn trừ tiền người dùng';
				$params = array(
					'payment_id' => $transid,
					'username' => $username,
					't1' => 0,
					'time' => microtime(true),
					'method' => 'sms',
					'user_input' => $phone,
					'sms_provider' => $service,
					'status' => 'error',
					'comment' => $comment . '||' . $receiveMsg . '||'
				);
				$errorId = $this->payment_model->addSMSTransaction($params);
				
				$dataSent['transid'] = $errorId;	//mã giao dịch 0 -> không trừ tiền người dùng
                $textnote = $this->textnote_model->getInfoByKey('app_kh_error');
                $commentSent = $textnote->value;
                $find = array('{USERNAME}');
                $commentSent = str_replace($find, $username, $commentSent);
				$dataSent['content'] = $commentSent;
			}
		} else {
			$dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
			die();
		}
		//echo kết quả
		//echo $dataSent['transid'] . '|' . $dataSent['content'];
		$this->echoKQ($dataSent);
	}    
    
    function appkh1Sent() {
        $total = $this->user_model->lnTotal();
        $lists = array();
        $lists = $this->appkh1GenList($lists, $total);
        $length = count($lists);
        // send cho nguoi trung thuong
        $smsid = '20110730-1216';
        $sender = '841699979999';
        if($lists):
        foreach($lists as $list) {
            if(isset($list)) {
                $gift = $this->user_model->lnGetInfo($list);
                if($gift) {
                    $maso = $list;
                    if($maso<10) $maso = '0'.$maso;
                    $number = $gift->phone;
                    $message = 'Chuc mung ban da trung phan qua so '.$maso.', hay ho to ma so phan qua de nhan thuong ban nhe';
                    $this->sentMT($number, $message, $smsid, $sender);
                }
            }
        } 
        endif;
    }
    
    function appkh1GenList(&$list, $total) {
        $min = 1;
        $max = $total;
        $soluong = $this->user_model->lnGetNumber();
        for($i=0; $i<$soluong; $i++) {
            $num = rand($min, $max);
            if(in_array($num, $list)) {
                $i--;
                continue;
            }
            $list[] = $num;
        }
        return $list;
    }
    
    function testnao($total) {
        $list = array();
        $min = 1;
        $max = $total;
        $soluong = $this->user_model->lnGetNumber();
        for($i=0; $i<$soluong; $i++) {
            $num = rand($min, $max);
            if(in_array($num, $list)) {
                $i--;
                continue;
            }
            $list[] = $num;
        }
        var_dump($list);
    }
    
	function sentMT($number,$message,$smsid,$sender){
		$url = 'http://partner.piggymob.com/api_mt.php?';

		$usr_id = 'gsm';
		$url .= 'usr_id='.urlencode($usr_id);
		
		$passwd = 'gsm.2405$';
		$url .= '&passwd='.urlencode($passwd);
		
		$url .= '&smsid='.urlencode($smsid);
		$url .= '&sender='.urlencode($sender);
		$url .= '&receiver='.urlencode($number);
		$url .= '&message='.urlencode($message);
		//file_put_contents('./log',$url);
		return file_get_contents($url);
	}
    
    function test($number, $message) {
        $smsid = '20111130-369';
        $sender = '84948633748';
        var_dump($this->sentMT($number, $message, $smsid, $sender));
    }
    
    /*
     * Event mời bạn tham gia appstore
     * QUA sdt
     */
	function qua() {
        $data = array();
        parse_str($_SERVER['QUERY_STRING'], $data);

        //mã bảo mật được cung cấp bởi hệ thống thanh toán 
        $secret = '52f699cc6140628748359a9cbd9a9a11';
        //phone người dùng
        $phone = $this->db->escape_str($data['phone']);
        //nội dung tin nhắn
        $msg = $this->db->escape_str($data['message']);
        //đầu số SMS
        $service = $this->db->escape_str($data['service']);
        //mã giao dịch
        $transid = $this->db->escape_str($data['transid']);
        //chữ kí
        $hash = $this->db->escape_str($data['hash']);

        //tạo chữ kí để kiểm tra
        $signature = md5($secret . $msg . $phone . $service . $transid);

        $dataSent = array();
        
        //chữ kí đúng
        if($signature == $hash) {
            $msg = explode(' ', $msg);
            //phone người nhận
            $fPhone = $msg[2];
            $fPhone = '84'.substr($fPhone, 1, strlen($fPhone));
            //phone người nhận sai
            if(!is_numeric($fPhone)) {
                $dataSent['transid'] = random_string('alnum', 8).rand(0,100);
                $dataSent['content'] = 'So dien thoai nguoi nhan khong dung dinh dang';
                $this->echoKQ($dataSent);
                exit;
            } else {
                $checkPhone = $this->user_model->checkPhone($phone);
                $checkFphone = $this->user_model->checkPhone($fPhone);
                //nguoi gui chua co tai khoan trong appstore
                if(!$checkPhone) {
                    $dataSent['transid'] = random_string('alnum', 8).rand(0,100);
                    $dataSent['content'] = 'Co loi xay ra.Ban chua kich hoat tai khoan AppStore nao voi so dien thoai '.$phone.' cua ban';
                    $this->echoKQ($dataSent);
                    exit;
                } else if($checkFphone) {
                    $dataSent['transid'] = random_string('alnum', 8).rand(0,100);
                    $dataSent['content'] = 'Co loi xay ra.So dien thoai '.$fPhone.' da kich hoat tai khoan tren AppStore roi';
                    $this->echoKQ($dataSent);
                    die();
                } else {
                    $checkGiftcode = $this->user_model->checkGiftcode($phone, $fPhone);
                    if($checkGiftcode) {
                        $giftcode = $checkGiftcode->time;
                        $successId = random_string('alnum', 8).rand(0,100);
                    } else {
                        //lưu log
                        $giftcode = time();
                        $giftLog = array(
                            'sender' => $phone,
                            'receiver' => $fPhone,
                            'time' => $giftcode,
                            'active' => 1
                        );
                        $successId = $this->user_model->addGiftcode($giftLog);
                    }
                    $dataSent['transid'] = $successId;
                    $dataSent['content'] = 'Da moi ban thanh cong.Tai khoan '.$checkPhone->username.' se duoc cong tym do';
                    //gui tin cho nguoi nhan
                    $commentSent = 'Ban nhan duoc ma giftcode '.$giftcode.' tren AppStore.vn tu so phone '.$phone.'.Hay dang ki va kich hoat tai khoan bang so dt cua ban de nhan qua';
                    $this->load->library('Nusoap');
                        //thông tin đối tác
                        $partnerCode = 'APP QUA';
                        $partnerPassword = '243887efc3230890817bbc7d68f9e5dd';
                        $partnerSecret = '52f699cc6140628748359a9cbd9a9a11'; 
                        
                        $nusoap = new nusoap_client('https://pay.appstore.vn/webservice/sms', true);
                        $dataMT = array(
                            'transid' => $transid,
                            'number' => $fPhone,
                            'message' => $commentSent,
                            'partnerInfo' => array(
                                'PartnerCode' => $partnerCode,
                                'Password' => $partnerPassword,
                                'Signature' => md5($transid . $fPhone . $commentSent . $partnerCode . $partnerPassword . $partnerSecret)
                            )
                        );
                    $result = $nusoap->call('SendMT', $dataMT);
                }
            }

        } else {
            $dataSent['content'] = 'Chu ki bi loi, noi dung nhan duoc khong chinh xac';
            die();
        }   
		//echo kết quả
		//echo $dataSent['transid'] . '|' . $dataSent['content'];
		$this->echoKQ($dataSent);
	}    
    
    /*
     * bank smartlink
     */
    
    function bankFail() {
        redirect('home/tym');
    }
    
    function bankSuccess($transid) {
        if(!is_numeric($tranid))
		redirect('home/tym');
	
        $dbuser = $this->load->database('dbuser', TRUE);
        $dbuser->where('id',$tranid);
        $query = $dbuser->get('transaction');
        $trans = $query->first_row();
        echo '
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                </head>
                <body>
                    <script type="text/javascript">
                        alert("'.$trans->comment.'");
                        window.location.href = "'.base_url().'home/tym";
                    </script>
                </body>
            </html>
        ';
    }
    
    function bank() {
        parse_str($_SERVER['QUERY_STRING'], $GET);
        $partnerCode = 'BANK-APPSTORE';
        $partnerPassword = '243887efc3230890817bbc7d68f9e5dd';
        $secretCode = '52f699cc6140628748359a9cbd9a9a11';

        $tranid = $GET['partnertranid'];
        $payittranid = $GET['transid'];
        $amount = $GET['amount'];
        $amountafterfee = $GET['amountafterfee'];
        $hash = $GET['hash'];
        
        $dbuser = $this->load->database('dbuser', TRUE);
        if($hash != md5($secretCode.$amount.$amountafterfee.$tranid.$payittranid) || $amount <= 0){
            die();
        }else{
            $dbuser->where('id',$tranid);
            $query = $dbuser->get('transaction');
            if($query->num_rows() == 0)
                die();

            $trans = $query->first_row();
            if($amount != $trans->card_value){
                $data = array(
                    'reason' => 'Số tiền không đúng!'
                );
                $dbuser->where('id',$tranid);
                $dbuser->update('transaction',$data);

                die();
            }
            if($trans->status != 0){
                die();
            }
            //cap nhat vao db - cong tym cho user
            $userid = $trans->user_id;
            $x = substr($amount, 0, count($amount)-4);
            $bankValue = $this->setting_model->getValueByKey('bank'.$x);
            $t1 = $bankValue;
            $comment = 'Tài khoản ' . $trans->username . ' đã nạp thành công, được cộng ' . $t1 . ' tym đỏ';
            $data = array(
                'status' => 'success',
                'payment_id' => $payittranid,
                't1' => $t1,
                'comment' => $comment
            );
            $this->payment_model->updateTransaction($tranid, $data);
            //cộng tiền vào tài khoản cho người dùng
            $this->user_model->increaseTym($userid, 't1', $t1);
            echo 'success';
        }
    }
}
