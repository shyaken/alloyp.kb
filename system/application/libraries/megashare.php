<?php
class MegaShare{
	function MegaShare(){
	}
	function getLink($url){
		$curl = curl_init($url);
		$agents = file('browser');
		$agent = $agents[rand(0,count($agents)-1)];
		$header = array(
			'User-Agent:'.$agent,
			'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3',
			'Accept-Encoding:gzip,deflate,sdch',
			'Accept-Language:en-US,en;q=0.8',
			'Cache-Control:no-cache',
		);
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_NOBODY, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_REFERER, 'http://appstore.vn/i');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$header = curl_exec($curl);
		curl_close($curl);
		$header = strtolower($header);  
		$link = substr($header,strpos($header,'location:')+9);
		$link = trim(substr($link,0, strpos($link,"\n")));
		return $link;
	}
}
?>
