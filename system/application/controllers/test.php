<?php
class Test extends Controller {
	function Test() {
		parent::__construct();
	}
	
	function index() {
		echo 'uhm';
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
    
    /*
    function test($start, $end) {
        $dbtest = $this->load->database('spay', TRUE);
        $sql = "SELECT *
FROM `transaction`
WHERE `partnertranid` LIKE CONVERT( _utf8 '%NOCALL%'
USING latin1 )
COLLATE latin1_swedish_ci
AND `inputcontent` LIKE CONVERT( _utf8 '%20120113%TYM PIT%'
USING latin1 )
COLLATE latin1_swedish_ci
LIMIT $start, $end
";
        $query = $dbtest->query($sql);
        if($query->num_rows()>0) {
            $result = $query->result();
            foreach($result as $x) {
                $input = explode('|', $x->inputcontent);
                $smsid = $input[1];
                $sender = $input[2];
                $number = $sender;
                $service = $input[3];
                $cuphap = $input[6];
                $transid = $x->id;
                $encode = urlencode("message=$cuphap&phone=$sender&service=$service&transid=$transid&hash=3");
                $tmp = file_get_contents("http://thirdparty.pitayasoftware.com/services/AppStoreVN.aspx?$encode");
                echo $encode;
                break;
            }
        }
        $smsid = '20111130-369';
        $sender = '84948633748';
        //var_dump($this->sentMT($number, $message, $smsid, $sender));
    }
     */
}