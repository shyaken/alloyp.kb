<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Get4share {
	//--------khong thay doi cac tham so--------------
	//IP appstore.vn: 123.30.174.131
	private $destinationUrl = 'http://up.4share.vn/websitelink/';
	private $WebsiteID = 2;
	private $Account_4share = 'appstore.vn';
	private $key_hash = '9457aef8a9de457d696685e340ab83ed';
	private $OrderID;
	private $key_down = 'down';
	private $key_reg = 'reg';
    
    function __construct() {
        $this->setTime();
        $this->OrderID = date('YmdHis');
    }
    
    function getFileId($link) {
        $tmp = explode('/', $link);
        $FileID = $tmp[4];
        if(strlen($FileID)<5) return false;
        else return $FileID;
    }
    
    function getLink($link) {
        $FileID = $this->getFileId($link);
        if(!$FileID) return false;
        $destinationUrl = $this->destinationUrl;
        $UserID = 'test00001';
        $OrderID = $this->OrderID;
        $WebsiteID = $this->WebsiteID;
        $Account_4share = $this->Account_4share;
        $key_hash = $this->key_hash;
        $key_down = $this->key_down;
        $key_reg = $this->key_reg;

        //Fomat: data = orderID|websiteid|UserID|FileID|Account_4share
        $data = $OrderID ."|".$WebsiteID."|".$UserID."|".$FileID."|".$Account_4share;
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		
		$planText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key_hash, $data, MCRYPT_MODE_ECB, $iv);
		
		$sign = $this->CreateSign($planText);
		
		$destinationUrl = $destinationUrl."?chanel=".$key_down."&websiteid=".$WebsiteID;
	
		$destinationUrl = $destinationUrl."&data=".$data."&sign=".$sign;

        $html = file_get_contents($destinationUrl);
        
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
            $links = $matches[0];
            return str_replace("'", "", $links[2]);
        } else {
            return false;
        }
            
        return $html;      
    }
    
    function createSign($string) {
        $strHex="";
		for($i=0; $i<strlen($string); $i++)
		{   
			if(ord($string{$i})<16)
			{
				$strHex=$strHex."0".dechex(ord($string{$i}));
			}
			else
			{
				$strHex=$strHex.dechex(ord($string{$i}));
			}
		}
        return $strHex;
    }
    
    function setTime() {
        date_default_timezone_set("Asia/Saigon");
    }
}	