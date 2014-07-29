<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Crawler 
{
	private $CI;
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('dom');
	}
	
	/*
	 * iOS
	 * http://itunes.apple.com/vn/app/everyday/id398081659?mt=8
	 */
	function iOS($url, $type = '')
	{
		$html = file_get_html($url);
		$content = $html->find('#left-stack .list li');
		
		$data = array();
		foreach($content as $li) {
			$data[] = strip_tags($li->innertext);
		}
		
		$count = 0;
		$find = array("Category:", "Released:", "Version:", "Size:", "Language:", "Languages:", "Developer:");
		
		foreach($data as &$value)
		{
			$value = str_replace($find, "", $value);
			$value = trim($value);
			if($count == 8) break;
            $count++;
		}
		
		// get name & description
		$description = $html->find('.product-review p', 0);
		if($type == 'description') return $description->innertext;
		$name = $html->find('.padder #title h1', 0);
		if($type == 'name') return $name->innertext;
		$vendor_site = $html->find('.view-more', 0);
		$requirement = $html->find('#left-stack p', 0);
		
		// images
		$images = array();
		$imgs = $html->find('img[class=portrait]');
		if(!$imgs) $imgs = $html->find('img[class=landscape]');
		$cur = date('mY', microtime(true));
		$path = '/' . UPLOADFOLDER . "/thumbnails/$cur";
		$cur = "." . $path;
		if(!is_dir($cur)) mkdir($cur);
		foreach($imgs as $img) {
			$x = explode("/", $img->src);
			$filename = $x[count($x)-1];
			if(!file_exists($cur . "/" . $filename)) {
				file_put_contents($cur ."/" . $filename,file_get_contents($img->src));
				//$this->download($img->src, "$cur/$filename");
				$images[] = $path . "/" . $filename;
			} else {
				$images[] = $path . "/" . $filename;
			}
		}
		
		// thumbnail
		$thumb = $html->find('#left-stack div[class=artwork] img', 0);
		$x = explode("/", $thumb->src);
		$filename = $x[count($x)-1];
		if(file_exists($cur . "/" . $filename)) {
			$thumbnail = $path . "/" . $filename;
		} else {
			file_put_contents($cur . "/" . $filename, file_get_contents($thumb->src));
			$thumbnail = $path . "/" . $filename;
		}

		$result = new stdClass();
		$result->name 		 = $name->innertext;
		$result->description = $description->innertext;
		$result->price 		 = $data[0];
		$result->category 	 = $data[1];
		$result->released 	 = $data[2];
		$result->version	 = $data[3];
		$result->language	 = $data[5];
		$result->size		 = $data[4];
		$result->vendor 	 = $data[6];
		$result->vendor_site = $vendor_site->href;
		$result->requirement = strip_tags($requirement->innertext);
		$result->rating		 = 'none';
		$result->images 	 = $images;
		$result->thumbnail	 = $thumbnail;
		
		return $result;
	}
	
	/*
	 * android
	 * https://market.android.com/details?id=com.google.android.apps.maps
	 */
	function android($url, $type = '')
	{
		$html = file_get_html($url);
		$dds = $html->find('div[class=doc-metadata] dl[class=doc-metadata-list] dd');
		$dts = $html->find('div[class=doc-metadata] dl[class=doc-metadata-list] dt');	
		
		foreach($dds as $dd) {
			$values[] = strip_tags($dd->innertext);
		}

		$i = 0;
		foreach($dts as $dt) {
			$keys[] = str_replace(':', '', strip_tags($dt->innertext));
			$params[str_replace(':', '', strip_tags($dt->innertext))] = $values[$i];
			$i++;
		}
		
		$allkeys = array('Current Version', 'Requires Android', 'Category', 'Price', 'Size', 'Updated', 'Language');
		for($i=0; $i<count($allkeys); $i++) {
			if(!isset($params[$allkeys[$i]])) $params[$allkeys[$i]] = 'none';
		}
		
		$name = $html->find('.doc-banner-title', 0);
		if($type == 'name') return strip_tags($name->innertext);
		$vendor = $html->find('.doc-header-link', 0);
		$description = $html->find('#doc-original-text', 0);
		if($type == 'description') return $description->innertext;
		
		// images
		$images = array();
		$imgs = $html->find('img[class=doc-screenshot-img lightbox goog-inline-block]');

		$cur = date('mY', microtime(true));
		$path = '/' . UPLOADFOLDER . "/thumbnails/$cur";
		$cur = "." . $path;
		if(!is_dir($cur)) mkdir($cur);
		foreach($imgs as $img) {
			$x = explode("/", $img->src);
			$filename = $x[count($x)-1] . ".jpg";
			//if(!file_exists($cur . "/" . $filename)) {
				file_put_contents($cur ."/" . $filename,file_get_contents($img->src));
				//$this->download($img->src, "$cur/$filename");
			//}
			$images[] = $path . "/" . $filename;
		}		
		
		$thumb = $html->find('div[class=doc-banner-icon] img', 0);
                $x = explode("/", $thumb->src);
                $filename = $x[count($x)-1];
                $rand = random_string('alnum', 8);
                $filename = $rand . $filename;
                    file_put_contents($cur . "/" . $filename, file_get_contents($thumb->src));
                    $thumbnail = $path . "/" . $filename;
		
		$result = new stdClass();
		$result->name 		 = strip_tags($name->innertext);
		$result->description = $description->innertext;
		$result->price 		 = $params['Price'];
		$result->category 	 = $params['Category'];
		$result->released 	 = $params['Updated'];
		$result->version	 = $params['Current Version'];
		$result->size		 = $params['Size'];
		$result->vendor 	 = $vendor->innertext;
		$result->vendor_site = 'http://' . $this->getDomain($url) . $vendor->href;
		$result->requirement = $params['Requires Android'];
		$result->rating		 = $params['Rating'];
		$result->language	 = $params['Language'];
		$result->images		 = $images;
		$result->thumbnail       = $thumbnail;
				
		return $result;
	}
	
	/*
	 * java
	 * http://store.ovi.com/content/62595
	 */
	function java($url, $type = '')
	{
		$html = file_get_html($url);
		$content = $html->find('.contentDescription p');
		
		foreach($content as $p) {
			$data[] = strip_tags($p->innertext);
		}
		
		//if($type == 'description') return $data[2];
		
		$tmp0 = explode(':', $data[0]);
		$vendor = $tmp0[1];
		
		$tmp1 = explode('-', $data[1]);
		$category = $tmp1[0];
		$size = $tmp1[1];
		
		$name = $html->find('.contentDescription .title', 0);
		if($type == 'name') return trim($name->innertext);
		$price = $html->find('.price', 0);
		$vendor_site = $html->find('.createdBy a', 0);
		
		// images
		$images = array();
		$imgs = $html->find('#previewItem .thumb img');
		$cur = date('mY', microtime(true));
		$path = '/' . UPLOADFOLDER . "/thumbnails/$cur";
		$cur = "." . $path;
		if(!is_dir($cur)) mkdir($cur);
		foreach($imgs as $img) {
			$x = explode("/", $img->src);
			$y = explode("?", $x[count($x)-1]);
			$filename = $y[0];
			if(!file_exists($cur . "/" . $filename)) {
				file_put_contents($cur ."/" . $filename,file_get_contents($img->src));
				//$this->download($img->src, "$cur/$filename");
			}
			$images[] = $path . "/" . $filename;
		}	
		
		$result = new stdClass();
		$result->name 		 = trim($name->innertext);
		$result->description = trim($data[4]);
		$result->price 		 = trim($price->innertext);
		$result->category 	 = $category;
		$result->released 	 = 'none';
		$result->version	 = 'none';
		$result->size		 = trim($size);
		$result->vendor 	 = trim($vendor);
		$result->vendor_site = $vendor_site->href;
		$result->requirement = 'none';
		$result->rating		 = 'none';
		$result->images		 = $images;
		$result->language        = 'unknown';
        $result->thumbnail       = $images[0];
		
		return $result;
	}
	
	
	/*
	 * http://appworld.blackberry.com/webstore/content/3729?lang=en
	 */
	function blackberry($url, $type = '') 
	{
		$domain = $this->getDomain($url);
		$html = file_get_html($url);
		$content = $html->find('.awAppVersionInfo .appdetail_right_data');
		
		foreach($content as $span) {
			$data[] = strip_tags($span->innertext);
		}

		$name = $html->find('.awAppDetailsBreak .awAppDetailsBreakColumn', 0);
		$name = strip_tags(trim($name->innertext));
		if($type == 'name') return $name;
		
		$price = $html->find('.awVendorPriceTable .contentLic', 0);
		$vendor_site = $html->find('pre[class=awAppInfoVendor awAppDetailsLeftMargin] .vendorLink', 0);
		$description = $html->find('#j_id1036', 0);
		if($type == 'description') return $description->innertext;
		
		$category = $html->find('#j_id818 a', 1);
		$requirement = $html->find('#j_id1203 .appdetailsText', 0);
		
		$cur = date('mY', microtime(true));
		$path = '/' . UPLOADFOLDER . "/thumbnails/$cur";
		$cur = "." . $path;
		if(!is_dir($cur)) mkdir($cur);
		
		// thumbnail
		$thumb = $html->find('#appIcon img[id=idme]', 0);
		$x = explode("/", $thumb->src);
		$tmp = explode('?', $x[count($x)-1]);
		$filename = $tmp[0];

		if(file_exists($cur . "/" . $filename)) {
			$thumbnail = $path . "/" . $filename;
		} else {
			file_put_contents($cur . "/" . $filename, file_get_contents('http://' . $domain . $thumb->src));
			$thumbnail = $path . "/" . $filename;
		}

		// images
		
		$url = str_replace('content/', 'content/screenshots/', $url);
		$html = file_get_html($url);
		$images = array();
		$imgs = $html->find('#visibleArea .imageScreenshot img');
		$cur = date('mY', microtime(true));
		$path = '/' . UPLOADFOLDER . "/thumbnails/$cur";
		$cur = "." . $path;
		if(!is_dir($cur)) mkdir($cur);
		foreach($imgs as $img) {
			$x = explode("/", $img->src);
			$filename = str_replace('?t=1', '', $x[count($x)-1]);
			if(!file_exists($cur . "/" . $filename)) {
				file_put_contents($cur ."/" . $filename, file_get_contents('http://' . $domain . str_replace('t=1', 't=2', $img->src)));
				//$this->download($img->src, "$cur/$filename");
			}
			$images[] = $path . "/" . $filename;
		}
		
		$result = new stdClass();
		$result->name 		 = $name;
		$result->description = $description->innertext;
		$result->price 		 = trim($price->innertext);
		$result->category 	 = strip_tags($category->innertext);
		$result->released 	 = $data[1];
		$result->version	 = $data[0];
		$result->size		 = $data[2];
		$result->language 	 = 'none';
		$result->vendor 	 = strip_tags(trim($vendor_site->innertext));
		$result->vendor_site = 'http://' . $this->getDomain($url) . $vendor_site->href;
		$result->requirement = $requirement->innertext;
		$result->rating		 = 'none';
		$result->images		 = $images;
		$result->thumbnail 	 = $thumbnail;
		
		return $result;
	}
	
	/*
	 * return domain name function
	 */
	function getDomain($url)
	{
	    if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === FALSE)
	    {
	        return false;
	    }
	    /*** get the url parts ***/
	    $parts = parse_url($url);
	    /*** return the host domain ***/
	    //return $parts['scheme'].'://'.$parts['host'];
	    return $parts['host'];
	}	
	
	/*
	 * url is valid?
	 */
	function validUrl($url)
	{
		/*
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_exec($curl);
		
		if(curl_error($curl)) {
			curl_close($curl);
			return false;
		}
		else {curl_close($curl);return true; }
		*/

		$handle = @fopen($url,'r');
		if($handle !== false){
			return true;
		}
		else {
			return false;
		}	
	}
	
	function download($src, $dst) {
        $f = fopen($src, 'rb');
        $o = fopen($dst, 'wb');
        while (!feof($f)) {
            if (fwrite($o, fread($f, 2048)) === FALSE) {
                   return 1;
            }
        }
        fclose($f);
        fclose($o);
        return 0;
	}
	
	function translate( $text, $destLang = 'vi', $srcLang = 'en' ) {
	 
		$text = urlencode( $text );
		$destLang = urlencode( $destLang );
		$srcLang = urlencode( $srcLang );
		 
		$trans = @file_get_contents( "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q={$text}&langpair={$srcLang}|{$destLang}" );
		$json = json_decode( $trans, true );

		if( $json['responseStatus'] != '200' ) 
			return false; 
		else return $json['responseData']['translatedText'];
	 
	}	

	
}
