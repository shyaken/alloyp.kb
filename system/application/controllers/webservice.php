<?php
class Webservice extends Controller
{
	function Webservice()
	{
		parent::__construct();
		$this->load->library('Nusoap');
		$this->soapserver = new soap_server();
		$this->soapserver->configureWSDL('appstoreWSDL','urn:appstore.vn');
        $this->soapserver->wsdl->schemaTargetNamespace = 'urn:appstore.vn';
	}
	
	function index()
	{
		$_SERVER['QUERY_STRING'] = "wsdl";
		
		 $this->soapserver->wsdl->addComplexType(
        	'status',
        	'complexType',
        	'struct',
        	'all',
        	'',
        	array(
        		'status'	=> array('name'	=> 'status'	, 'type' => 'xsd:string'),
        		'message'	=> array('name'	=> 'message', 'type' => 'xsd:string')
        	)
        );
        
        $this->soapserver->register(
            'chargeCoin',
            array(
                'username' 	=> "xsd:string",
                'coin' 		=> "xsd:string",
                'payitkey'	=> "xsd:string",
           		'payitsign'	=> "xsd:string"
            ),
            array("return"=>"tns:status"),
	            "urn:appstore.vn",
	            "urn:appstore.vn#chargeCoin",
	            "rpc",
	            "encoded",
	            "receive gCoin from payit"
        );
        
        function chargeCoin($username, $coin, $payitkey, $payitsign) 
        {
        	$CI =& get_instance();
        	$CI->load->model('user_model', 'user');
        	$CI->load->model('transaction_model', 'transaction');
        	
        	$data = array($username, $coin);
        	
        	if($CI->transaction->checkValidInfo($payitkey, $payitsign, $data)) {
        		if($CI->user->isExists($username)) {
        			$data = array(
        				'username' => $username,
        				'apcoin' => $coin,
        				'time' => microtime(true),
						'comment' => 'Nạp trực tiếp từ hệ thống SMS'	
        			);
        			
        			$CI->transaction->log($data);
        			//cong app coin cho user
        			$CI->user->countAp($username, $coin);
        			
        			return array('status'=>'true', 'message'=>'Đã nạp thành công tài khoản cho người dùng');
        		} else {
        			return array('status'=>'false', 'message'=>$username . ' không tồn tại!');
        		}
        	} else {
        		return array('status'=>'false', 'message'=>'Thông tin nhận được không chính xác');
        	}
        }
        
		$this->soapserver->service(file_get_contents("php://input"));        
	}
}