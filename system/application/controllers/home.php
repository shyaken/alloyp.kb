<?php
class Home extends Controller {
    private $user, $storeCode;
    
	function Home()
	{
		parent::Controller();
		$this->load->library('Cache', 'cache');
		$this->load->library('output');
		$this->load->model('app_model');
        $this->load->model('category_model');
        $this->load->model('textad_model', 'textad');
        $this->load->model('user_model');
        $this->load->model('advertise_model');
        //enable profiler
        //$this->output->enable_profiler(TRUE);
        $last = substr(base_url(),-2,1);
        if($last == '/') $last = time();
        $this->storeCode = $last;
        $this->user->logged = $this->session->userdata('logged_in');
        $this->user->userid = $this->session->userdata('userid');
        $this->user->username = $this->session->userdata('username');
        $this->user->style = $this->session->userdata('style');
        if(!$this->user->style) {
            $this->user->style = 'style';
            $this->session->set_userdata('style', 'style');
        }
        $this->user->package_expired = 1;
        if($this->user->logged != '1' && $this->user->logged) {
            $this->session->set_userdata('logged_in', 1);
        }
        // kiểm tra gói tải x
        if($this->user->userid) {
            $userId = $this->user->userid;
            $store = $this->storeCode;
            $userPack = $this->user_model->getUserPack($userId, $store);
            $now = microtime(true);
            if($userPack) {
                $package_expired = $userPack->package_expired;
                if($now > $package_expired || !$package_expired) {
                    $this->user->package_expired = 1;
                } else {
                    $this->user->package_expired = 0;
                }
            }
        }
        // kiểm tra session
        if(!$this->session->userdata('userid')) {
            $session_id = $this->session->userdata('session_id');
            //$this->user->username = substr($session_id, 0, 6);
            $this->user->username = 'session';
            $this->user->userid = 0; // $uid
        }
        // lưu log online
        $timeout = $this->session->userdata('timeout');
        if(!$timeout) {
            $timeout = time() +  6 * 60 * 60;
            $this->session->set_userdata('timeout', $timeout);
        }
		$session_id = $this->session->userdata('session_id');
        $user_id = $this->user->userid;
        $ip_address = $this->input->ip_address();
        $user_agent = $this->input->user_agent();
        $last_activity = time();
        $line = "$session_id||$user_id||$ip_address||$user_agent||$last_activity";
        if($last_activity >= $timeout) {
            $this->session->set_userdata('timeout', time() + 6 * 60 * 60);
            //$this->user_model->onlineData($session_id, $user_id, $ip_address, $user_agent, $last_activity);
            $oldUmask = umask();
            umask(0);
            if(!file_exists('./online.txt')) touch('online.txt');
            umask($oldUmask);
            $total = shell_exec('wc -l ./online.txt');
            if($total >= 5000) shell_exec('echo "" > ./online.txt');
            else shell_exec("echo '$line' >> ./online.txt");
        }
        // kiểm tra popup
        $this->load->model('setting_model');
        $popup = $this->setting_model->getValueByKey('popup');
        if($popup) {
            if(!$this->session->userdata('read_popup')) {
                $this->session->set_userdata('read_popup', 0);
            }
        }
	}
        
        function countAd() {
            $ads = $this->advertise_model->enabledAd();
			if($ads):
            foreach($ads as $ad) {
                $time = date('Ymd');
                $this->advertise_model->countView($ad->id, $time);
            }
			endif;
        }

	function index() {
        $this->loadMainHeader();
        if($view = $this->cache->get($this->storeCode . 'index')){
                $this->output->append_output($view);
        } else {
            $data['categories'] = $this->category_model->listAll();
            $data['newApps'] = $this->category_model->countAllNew();
            $data['headertext'] = $this->textad->getText('headertext');

            $view = $this->load->view('home_view', $data, true);
            $view = compress_output($view);
            $this->output->append_output($view);
            $this->cache->add($this->storeCode . 'index', $view, 7200);
        }
        $this->loadMainFooter();
	}

    function loadMainHeader($title = 'AppStore.vn', $description = '') {
        $this->countAd();
        $data['headerads'] = $this->advertise_model->listAd('header');
        $data['header1ads'] = $this->advertise_model->listAd('header1');
        $data['googlead'] = $this->textad->getText('googlead');
        $this->load->model('logo_model');
        $data['logo'] = $this->logo_model->getDefault();
        $description = strip_tags($description);
        $description = preg_replace("'\s+'", ' ', $description);
        $description = trim($description);
        $data['description'] = $description;
        $data['title'] = $title;
        $this->load->view('header_main', $data);
    }

    function loadMainFooter() {
        $data['footerads'] = $this->advertise_model->listAd('footer');
        $data['footertext'] = $this->textad->getText('footertext');
        $data['user_box'] = $this->loadUserBox();
        $this->load->view('footer_main', $data);
    }
    
    function loadMainFooterMini() {
        $data['user_box'] = $this->loadUserBox();
        $this->load->view('footer_mini', $data);
    }

    function loadUserBox() {
        $data['logged'] = $this->session->userdata('logged_in');
        $data['username'] = $this->session->userdata('username');
        if($data['logged']) {
            $data['user'] = $this->user_model->getUserById($this->session->userdata('userid'));
        }
        return $this->load->view('user_box', $data, true);
    }
    
    function category($categoryId=0, $page=0, $filter="last_update") {
        $this->loadMainHeader();
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->getAppByCategory($categoryId, $filter, $start, $limit);
        $data['apps'] = $this->showApps($apps);
        $category = $this->category_model->getInfo($categoryId);
        $tmp = explode(' ', $category->category_name);
        if(isset($tmp[1])) {
            $category->category_name = $tmp[0].' '.$tmp[1];
        }
        $data['category_name'] = $category->category_name;
        $data['category'] = $categoryId;
        $data['nextPage'] = $page + 1;
        $data['filter'] = $filter;
        $data['headertext'] = $this->textad->getText('headertext');
        $key = $this->storeCode.'category'.$categoryId.$filter;
        $cacheCategory = $this->cache->get($key);
        if($cacheCategory) {
        	$this->output->append_output($cacheCategory);
        } else {
			$cacheCategory = $this->load->view('category_view', $data, true);
			$cacheCategory = compress_output($cacheCategory);
			$this->output->append_output($cacheCategory);
			$expiration = 10800; //3 hours
			if($filter == 'download' || $filter == 'is_sticky') $expiration = 7200; //2 hours
			$this->cache->add($key, $cacheCategory, $expiration);        	
        }
        $this->loadMainFooter();
    }

    function searchApp($keyword="", $filter="vote") {
        $limit = 25;
        $start = 0;
        /*
        if ($keyword=="") {
            if(isset($_POST['keyword'])) { 
                $keyword = urlencode($_POST['keyword']);
                redirect('home/searchApp/'.$keyword);
            }
        }
        */
        //$keyword = urldecode($keyword);
        if(isset($_POST['keyword'])) { 
            $keyword = $_POST['keyword'];
        }
        $apps = $this->app_model->searchApp($keyword, $filter, $start, $limit);
        if ($apps) {
            $data['apps'] = $this->showApps($apps);
        } else {
            $data['apps'] = '0';
        }
        $this->loadMainHeader();
        $data['keyword'] = $keyword;
        $data['filter'] = $filter;
        $data['headertext'] = $this->textad->getText('headertext');
        $data['username'] = $this->session->userdata('username');
        $data['userid'] = $this->session->userdata('userid');
        $this->load->view('search_view', $data);
        $this->loadMainFooter();
    }
    
    function tag($tagname = "", $filter = "vote") {
        $limit = 25;
        $start = 0;

        $apps = $this->app_model->searchAppByTag($tagname, $filter, $start, $limit);
        if ($apps) {
            $data['apps'] = $this->showApps($apps);
        } else {
            $data['apps'] = '0';
        }
        $this->loadMainHeader();
        $data['keyword'] = $tagname;
        $data['filter'] = $filter;
        $data['headertext'] = $this->textad->getText('headertext');
        $this->load->view('tag_view', $data);
        $this->loadMainFooter();
    }
    
    function moreTagResult($keyword, $filter="vote", $page=0) {
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->searchAppByTag($keyword, $filter, $start, $limit);
        if ($apps) {
            echo $this->showApps($apps);
        }
    }

    function moreSearchResult($keyword, $filter="vote", $page=0) {
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->searchApp($keyword, $filter, $start, $limit);
        if ($apps) {
            echo $this->showApps($apps);
        }
    }

    function moreAppInCategory($categoryId=0, $page=0, $filter="vote") {
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->getAppByCategory($categoryId, $filter, $start, $limit);
        echo $this->showApps($apps);
    }

    function app($appId) {
        // Tăng lượt view
        $this->app_model->upViewCount($appId);
        $app = $this->app_model->getInfo($appId);
        if($app->publish == 0) redirect('home');
        $data['app'] = $app;

        $description = $data['app']->description;
        $title = $data['app']->app_name;
        $this->loadMainHeader($title, $description);
        $versions = $this->app_model->getListVersion($appId);
        if (sizeof($versions)>0) {
            $data['version'] = $versions[0]->version;
        } else {
            $data['version'] = 'N/A';
        }

        $category = $this->app_model->getCategory($appId);
        $data['categoryId'] = $category['category_id'];
        $tmp = explode(' ', $category['category_name']);
        if(isset($tmp[1])) {
            $category['category_name'] = $tmp[0].' '.$tmp[1];
        }
        $data['categoryName'] = $category['category_name'];
        $data['price'] = $this->app_model->getTymPrice($appId);
        $data['logged'] = $this->user->logged;
        $data['userid'] = $this->user->userid;
        $data['user'] = $this->user_model->getUserById($this->user->userid);
        $data['rates'] = $this->user_model->getRate();
        // related app
        $relatedApps = $this->app_model->relatedApp($appId, $category['category_id']);
        $data['relatedApps'] = $relatedApps;
        // kiểm tra user vote chưa
        $userId = $this->user->userid;
        $data['voted'] = $this->app_model->voted($appId, $userId);
        $limit = 5;
    	$start = $limit * 0;
        $data['commentLimit'] = $limit;
    	$data['comments'] = $this->app_model->getCommentByApp($appId, $start, $limit);
        /* 
         * Kiểm tra gói vip fshare || 4share
         * 0: chưa đăng kí
         * 1: gói vip hết hạn
         * 2: đang là vip
         */
        $vips = array(
            'fshare' => 0,
            '4share' => 0
        );
        $vipStores = $this->user_model->getDownloadVip($this->user->userid);
        if($vipStores) {
            $now = time();
            foreach($vipStores as $vipStore) {
                // fshare
                if($vipStore->dvip_store == 'fshare') {
                    if($vipStore->dvip_expired >= $now) {
                        $vips['fshare'] = 2;
                    } else {
                        $vips['fshare'] = 1;
                    }
                }
            }
        }
        $data['vips'] = $vips;
        
        $fsharePack = $this->user_model->getDownloadPack('fshare');
        $data['fsharePack'] = $fsharePack;
        /*
         * end vip download
         */
        // Kiểm tra xem có áp dụng gói không
        $this->load->model('setting_model');
        $data['package'] = $this->setting_model->getValueByKey('package');
        $data['package_expired'] = $this->user->package_expired;
		// kiểm tra xem app có link tải ko
		$data['haveLink'] = $this->app_model->countLinkDownload($appId);
        $this->load->view('app_view', $data);
        $this->loadMainFooter();
        //$this->load->view('header_mini');
    }
    
    // goi tu ham checkLogged o app_view.php
    function getTymData() {
        if($this->user->logged) {
            $user = $this->user_model->getUserById($this->user->userid);
            $rates = $this->user_model->getRate();
            $txt = $user->t1."_".$user->t2."_".$user->t3."_".$user->t4;
            $txt.= "@";
            $txt.= "0_";
            $moreT2 = floor($user->t1 * $rates['rate1_2']);
            $moreT3 = floor($user->t1 * $rates['rate1_3']);
            $moreT4 = floor($user->t1 * $rates['rate1_4']);
            $txt.= $moreT2."_".$moreT3."_".$moreT4;
            echo $txt;
        } else {
            echo "0";
        }
    }

    function showApp($appId) {
        //$this->loadMainHeader();
        // Tăng lượt view
        $this->app_model->upViewCount($appId);
        $data['app'] = $this->app_model->getInfo($appId);
        $versions = $this->app_model->getListVersion($appId);
        if (sizeof($versions)>0) {
            $data['version'] = $versions[0]->version;
        } else {
            $data['version'] = 'N/A';
        }

        $data['categoryName'] = $this->app_model->getCategoryName($appId);
        $data['headertext'] = $this->textad->getText('headertext');        
        $this->load->view('app_view', $data);
        //$this->loadMainFooter();
        //$this->load->view('header_mini');
    }

    function getAppList($categoryId=0, $filter="vote", $page=0) {
        $limit = 25;
        $start = $limit * $page;
        $data['apps'] = $this->app_model->getAppByCategory($categoryId, $filter, $start, $limit);
        return $this->load->view('apps', $data, true);
    }

    function comment($appId) {
    	if(isset($_POST['comment']) && $this->user->logged) {
    		$words = $this->app_model->listBadWord();
    		$finds = array();
    		$replaces = array();
    		foreach($words as $word) {
    			$finds[] = $word->find . '.';
    			$finds[] = $word->find . ',';
    			$finds[] = $word->find . ' ';
    			$replaces[] = $word->replace . '.';
    			$replaces[] = $word->replace . ', ';
    			$replaces[] = $word->replace . ' ';
    		} 
    		$content = str_replace($finds, $replaces, $this->input->post('comment'));
    		$time = date('Y-m-d H:i:s', microtime(true));
    		$params = array(
    			'app_id' => $appId,
    			'user_id' => $this->user->userid,
    			'content' => $content,
    			'post_date' => $time
    		);
    		$this->app_model->addComment($params);
    		$this->app_model->upCommentCount($appId);
            // cộng tym cho hành động comment
            $this->load->model('useraction_model');
            $action = $this->useraction_model->getActionByName('comment');
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
            echo "1";
        } else {
            echo "0";
        }
    }
    
    function moreCommentInApp($appId, $page) {
    	$limit = 5;
    	$start = $limit * $page;
    	$data['comments'] = $this->app_model->getCommentByApp($appId, $start, $limit);
    	$this->load->view('commentlist', $data);
    }

    function addComment($appId) {
        $data['app_id'] = $appId;
        $data['content'] = $_POST['content'];
        $data['user_id'] = $this->user->userid;
        $result = $this->app_model->addComment($data);
    }

    function vote($appId, $rate) {
    	if($this->user->logged) {
    		$check = $this->app_model->voted($this->user->userid, $appId);
    		if($check){ echo "1";exit; }
	        $data['app_id'] = $appId;
	        $data['user_id'] = $this->user->userid;
	        $data['rate'] = $rate;
	        $result = $this->app_model->vote($data);
            // cộng tym cho hành động login
            $this->load->model('useraction_model');
            $action = $this->useraction_model->getActionByName('vote');
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
	        echo $result;
    	}
    }

    function excel(){
        $this->load->plugin('to_excel');
        $this->db->select('apps.app_id, app_name, app_version.link');
        $this->db->from('apps');
        $this->db->join('app_version', 'app_version.app_id = apps.app_id');
        $this->db->limit(100);
        $query = $this->db->get();
        to_excel($query, 'excel.apps'); // filename is optional, without it, the plugin will default to 'exceloutput' 
    }
/*
     * report App
     * gọi từ hàm reportApp ở view app_view
     */
    function reportApp($code, $appId, $userId) {
    	if(!$this->user->logged) { echo "0"; exit; }
    	$reasons = array(
    		'Dung lượng tải về không đủ',
    		'Chưa có Version/tập mới nhất',
    		'Tải về ok, cài đặt bị lỗi',
    		'Lỗi không tìm thấy file',
    		'Sai mô tả nội dung',
    		'Nội dung nhạy cảm, cần kiểm duyệt lại',
    		'Phần mềm chất lượng kém, lừa đảo'
    	);

    	$data = array(
    		'code' => $code,
    		'content' => $reasons[$code-1],
    		'app_id' => $appId,
    		'user_id' => $userId
    	);
    	
    	$this->app_model->report($data);
    	echo "1";
    }    

    function topDownload() {
        $this->loadMainHeader();
        $limit = 25;
        $start = 0;
        $apps = $this->app_model->getTopDownload($start, $limit, $this->storeCode);
        //$apps = $this->app_model->getAppByCategory(0, 'download', $start, $limit);
        $data['apps'] = $this->showApps($apps);
        $data['headertext'] = $this->textad->getText('headertext');        
      	$cacheTopDownload = $this->load->view('topdownload_view', $data);
        $this->loadMainFooter();
    }

    function moreTopDownload($page) {
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->getTopDownload($start, $limit, $this->storeCode);
        //$apps = $this->app_model->getAppByCategory(0, 'download', $start, $limit);
        echo $this->showApps($apps);
    }
    
    function testCache($start) {
        var_dump($this->cache->get('e'.$start.'topdownload'));    
    }

    function newest() {
        $this->loadMainHeader();
        $limit = 25;
        $start = 0;
        $apps = $this->app_model->getAppByCategory(0, 'last_update', $start, $limit);
        $data['apps'] = $this->showApps($apps);
        $data['headertext'] = $this->textad->getText('headertext');
        $this->load->view('newest_view', $data);
        $this->loadMainFooter();
    }

    function moreNewest($page) {
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->getAppByCategory(0, 'last_update', $start, $limit);
        echo $this->showApps($apps);
    }

    function showApps($apps) {
        $data['apps'] = $apps;
        return $this->load->view('applist', $data, true);
    }

    function getLinks($appId) {
        $data['versions'] = $this->app_model->getAllVersion($appId);
        $links = array();
        foreach ($data['versions'] as $version) {
            foreach (explode('@@',$version->link) as $tmpLink) {
                $links[$version->app_version_id][] = $tmpLink;
            }
        }
        /*
        $userId = $this->user->userid;
        $data['tickets'] = $this->app_model->addTickets($userId, $appId, $links);
         */
        $this->load->view('link_view', $data);
    }

    function getTickets($versionId) {
        $version = $this->app_model->getVersion($versionId);
        $links = explode('@@', $version->link);
        $appId = $version->app_id;
        $userId = $this->user->userid;
        $data['tickets'] = $this->app_model->addTickets($userId, $appId, $versionId, $links);
        $this->load->view('ticket_view', $data);
    }

    function download($ticketId, $versionId, $order) {
        $this->load->model('setting_model');
        $package = $this->setting_model->getValueByKey('package');
        $ticket = $this->app_model->getTicket($ticketId);
        $version = $this->app_model->getVersion($versionId);
        if ($this->user->logged==1) {
            $userId = $this->user->userid;
        } else {
            $userId = 0;
        }
        if ($ticket!=null) {
            $paid = $ticket->paid;
            $price = $this->app_model->getTymPrice($ticket->app_id);
            if(!$version->price) $price['price'] = 0;
            //$paid = 0;
            if ($price['price']>0 && $paid==0) {
                //khuyen mai
                $app = $this->app_model->getInfo($ticket->app_id);
                $now = time();
                if($app->promo_enable && $app->promo_start <= $now && $now <= $app->promo_end) {
                    $price['price'] -= $app->promo_price;
                }
                $result = $this->user_model->decreaseTym($userId, $price['type'], $price['price']);
            } else {
                $result = true;
                if($package) {
                    // hết hạn
                    if($this->user->package_expired) {
                        echo "1";exit;
                    } else {
                        $result = true;
                    }
                } else {
                    $result = true;
                }
            }
            
            if ($result) {
                $downloadData = array(
                    'app_id' => $ticket->app_id,
                    'user_id' => $userId,
                    'tym_price' => $price['price'],
                    'tym_type' => $price['type'],
                    'time' => microtime(true)
                );
                $this->app_model->countDownload($downloadData, $paid);
                $link = $this->app_model->getLink($ticketId, $versionId, $order, $price['price']);
                $link = $this->getDirectLink($link);
                /*if(strpos($link, '4share.vn/') !== FALSE) {
                    $this->load->library('get4share');
                    $link = $this->get4share->getLink($link);
                }*/
                echo $link;
            } else {
                echo "0";
            }
        }
    }

    function getDirectLink($url) {
        $url = trim($url);
        #if(base_url() == 'http://appstore.vn/f/') return $url;  
        $this->load->library('megashare');
        $this->load->library('fshare');
        $url = str_replace('dl.appstore.vn', 'share.vnn.vn', $url);
        if(strpos($url, 'share.vnn.vn') !== FALSE || strpos($url, 'megashare.vn') !== FALSE) {
            $megashare = new MegaShare();
            $url = $megashare->getLink($url)."\n";
        } else if(strpos($url, 'fshare.vn/') !== FALSE) {
            $userId = $this->user->userid;
            $fshare = new Fshare();
            $fshare->login($userId);
            $url = $fshare->getLink($url);            
        } else if(strpos($url, 'fmc||') !== FALSE) {
            $str = explode('||', $url);
            $fmcId = $str[1];
            $url = $this->getFmcLink($fmcId);
        } else if(strpos($url, 'vnnplus||' !== FALSE)) {
            $str = explode('||', $url);
            $vnnId = $str[1];
            $url = $this->getVnnplusLink($vnnId);
        }
        return trim($url);
    }
    
    function getVnnplusLink($id) {
        $this->load->library('nusoap');
        $soap = new nusoap_client('http://183.91.14.101:8008/WebService/GSM/ServiceData.asmx?wsdl', TRUE);
        $soap->soap_defencoding = 'UTF-8';
        $soap->decode_utf8 = false;
        $data = array('id' => 'ejX/UVnTE/fSEkp5oWp/KA==');
        $a = $soap->call('GetLinkDownEbook', $data);
        $link = $a['GetLinkDownEbookResult'];
        $link = 'http://183.91.14.101:8008' . $link;
        return $link;
    }
    
    function getFmcLink($id) {
        $phone = "";
        //add transaction
        $addData = array(
            'game_id' => $id,
            'user_id' => $this->user->userid,
            'status' => 0,
            'time' => time()
        );
        $transid = $this->user_model->fmcAddTransaction($addData);
        $url = "http://client.mclub.vn/api/fmc.php?user=gsm&pass=msg2011&service=67&phone=$phone&message=$id&shortcode=&cmdcode=&txtid=$transid";
        $xml = file_get_contents($url);
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $statusTag = $dom->getElementsByTagName('dk_status');
        $status = $statusTag->item(0)->nodeValue;
        if($status == 1000) {
            $contentTag = $dom->getElementsByTagName('dk_content');
            $url = trim($contentTag->item(0)->nodeValue);
            //update transaction
            $updateData = array(
                'status' => 1
            );
            $this->user_model->fmcUpdateTransaction($transid, $updateData);
            return $url;
        } else {
            //var_dump($status);
            //$contentTag = $dom->getElementsByTagName('dk_content');
            //$url = trim($contentTag->item(0)->nodeValue);
            //echo $url;
            return false;
        }
    }
    
    /*
     * buy vip
     */
    //fshare
    function buyfshare($package_id) {
        //echo "99";die();
        $userId = $this->user->userid;
        $user = $this->user_model->getUserById($userId);
        $fsharePack = $this->user_model->getDownloadPrice($package_id);
        $priceTym = $fsharePack->value;
        
        if(!$user) {echo "1";die();};                     // chưa đăng nhập
        if($user->t1 < $priceTym) {echo "0";die();}       // hết tiền
        
        $priceData = new stdClass();
        $priceData->price_id = 1;
        $priceData->price_tym = $priceTym;
        $priceData->real_day = $fsharePack->key;
        $this->load->library('fshare');
        $fshare = new Fshare();
        $fshare->login($userId);
        $fshareStatus = $fshare->updateVIP($userId, $priceData);
        if($fshareStatus == 99) {echo "99";die();}        // update VIP thành công
        else if($fshareStatus == 2){echo "2";die();}      // lỗi kết nối với fshare
        else {echo "0";die();}                            // hết tym rồi
    }
    
    function test() {
        
    }
    
    function advertise($id) {
    	$ad = $this->advertise_model->getInfo($id);
        if(!$ad->publish) die('Advertise disabled');
        $time = date('Ymd');
        $this->advertise_model->countClick($id, $time);
    	
    	echo "<script>window.location.href='" . $ad->url . "';</script>";
    }
    
    /*
     * dịch description - gọi từ app_view.php
     */
    function translated() 
    {
    	$appid = $_POST['appid'];
    	$desc = '<p><a href="javascript:translated_vi();">Dịch</a></p>';
    	$desc .= $this->app_model->translateDesc($appid);
    	echo $desc;
    }
    
    /*
     * send email cho người dùng
     * gọi từ hàm sendEmail() trong view app_view.php
     */
    function sendEmail() 
    {
    	$appName = $this->input->post('name');
    	$appId = $this->input->post('app_id');
    	
    	$emails = $this->input->post('email');
    	$email = explode(';', $emails);
    	
    	for($i=0; $i<count($email); $i++) {
    		$from = "admin@appstore.vn";
    		$to = $email[$i];
    		$subject = "Bạn nhận được món quá từ người bạn trên AppStore.Vn";
    		$message = "Xin chào bạn!<br />";
    		$message .= "Ứng dụng $appName trên AppStore.Vn rất là thú vị, <a href='" . site_url("home/app/$appId") ."'>Nhấn vào đây để xem</a><br />";
    		$message .= "Hãy ghé thăm kho ứng dụng khổng lồ trên AppStore.Vn";
    		$senddate = (date("d M Y h:m:s -0500"));
    		$extraheaders = "From: $from" . "\nContent-Type: text/html\n";
    		@mail("$to", "$subject", "$message", $extraheaders);
    	}
        echo "1";
    }
    
    /***********************************************************************************
     ************************************* USER **************************************** 
     ***********************************************************************************/
    /*
     * Thông tin tài khoản
     */
    function account()
    {
        if(!$this->user->logged) redirect('home');
        $this->load->model('setting_model');
        $keys = $this->setting_model->getKey('package');
        $package = $this->setting_model->getValueByKey('package');
        $packages = array();
        foreach($keys as $key) {
            if(strlen($key->key) <= 3) {
                $packages[substr($key->key,1)] = $key->value;
            }
        }
        ksort($packages);
	   	$user = $this->user_model->getUserById($this->user->userid);
		$data['user'] = $user;
        $data['userPack'] = $this->user_model->getUserPack($user->user_id, $this->storeCode);
        $data['store'] = $this->storeCode;
        $data['rate'] = $this->user_model->getRate();
        $data['packages'] = $packages;
        $data['package_expired'] = $this->user->package_expired;
        $data['package_open'] = $package;
    	$this->load->view('account', $data);
        $this->loadMainFooterMini();
    }

    function tym()
    {
	if(!$this->user->logged) redirect('home');
    	//thư viện nusoap
    	$this->load->library('Nusoap');

    	//thông tin đối tác
    	$partnerCode = 'APP';
		$partnerPassword = '243887efc3230890817bbc7d68f9e5dd';
		$partnerSecret = '52f699cc6140628748359a9cbd9a9a11';

		//nạp bằng thẻ ĐT
		if(isset($_POST['submitCard'])) {
			//die('card');
			$nusoapClient = new nusoap_client('https://pay.appstore.vn/webservice/card', true);

    		$this->load->model('payment_model');

    		$data['cardSubmitted'] = 1;

    		$card_type = $this->input->post('card_type');
    		$card_code = $this->input->post('card_code');
    		$username = $this->user->username;

    		//tạo mới mã giao dịch tạm cho lần nạp card
    		$params = array(
    			'payment_id' => -1,
                'user_id' => $this->user->userid,
    			'username' => $username,
    			'time' => microtime(true),
    			'method' => 'card',
    			'card_type' => $card_type,
                'user_input' => $card_code,
    			'status' => 'start',
    			'comment' => 'Gửi dữ liệu thẻ đi, đang chờ trả lời'
    		);

    		//lấy mã giao dịch tạm thời
    		$transId = $this->payment_model->temporaryCardTransaction($params);

			$cardData = array(
				//'type' => $card_type,
				'code' => $card_code,
                'type' => $card_type,
				'transId' => $transId,
				'partnerInfo' => array(
					'PartnerCode' => $partnerCode,
					'Password' => $partnerPassword,
					'Signature' => md5($card_code.$card_type.$transId.$partnerCode.$partnerPassword.$partnerSecret)
				)
			);

			$result = $nusoapClient->call('UseCard', $cardData);
			if($fault = $nusoapClient->fault) {
				die($fault);
			}
			if($error = $nusoapClient->getError()) {
				die($error);
			}

			//lấy mã kết quả hệ thống thanh toán trả về
			$result = explode('|', $result);
			$responseCode = $result[0];
			//for debug
			/*$responseCode = '01'; $result[1] = $transId; $result[2] = '100000';*/ 

			//thành công
			if($responseCode == '1') {
				//tỷ lệ quy đổi
				$amount = $result[1];
                $paymentId = $result[2];
                $number = $amount/1000;
                $this->load->model('setting_model');
                $t1 = $this->setting_model->getValueByKey('card' . $number);

				//cộng tym đỏ cho người dùng
				$user = $this->user_model->getUserByField(array('username'=>$username));
				$this->user_model->increaseTym($user->user_id, 't1', $t1);

				//lưu giao dịch tạm thời -> thành công
				$comment = 'Người dùng ' . $username . ' nạp ' . $amount . ' VNĐ thẻ ' . $card_type . '( ' . $card_code . ' ) và được cộng ' . $t1 . ' tym đỏ';
				$params = array(
					'status' => 'success',
					'comment' => $comment,
					't1' => $t1,
					'user_input' => $card_code,
                    'card_value' => $amount,
					'payment_id' => $paymentId,
				);
				$this->payment_model->updateTransaction($transId, $params);
				$data['card_success'] = 'Bạn đã nạp thành công vào tài khoản ' . $t1 . ' tym đỏ';
			} else {
				if($responseCode == "") $responseCode = 'nn';
				$errors = array(
					'0' => 'Thẻ không tồn tại hoặc đã sử dụng',
					'1' => 'Partner không tồn tại',
					'2' => 'Loại thẻ không dùng - Sai thông tin đầu vào',
					'3' => 'Sai IP Partner',
					'4' => 'Đối tác bị cấm hoạt động',
					'5' => 'Sai mật khẩu được cung cấp',
					'6' => 'Sai chữ ký',
					'7' => 'Trùng mã giao dịch',
					'8' => 'Partner không tồn tại',
					'9' => 'Lỗi kết nối nhà cung cấp dịch vụ',
					'10' => 'Hệ thống đang bảo trì',
					'11' => 'Lỗi giao ',
					'nn' => 'Không xác định'
				);
				//cập nhật giao dịch
				$params = array(
					'status' => 'error',
					'comment' => $errors[$responseCode],
					'payment_id' => $transId,
				);
				$this->payment_model->updateTransaction($transId, $params);

				if($responseCode == '0') {
					$data['card_error'] = $errors['0'];
				} else {
					$data['card_error'] = $errors[$responseCode];
				}
			}
    	}

    	//Nạp bằng paypal
    	if(isset($_POST['submitPaypal'])) {
    		$this->load->model('payment_model');

    		$data['paypalSubmitted'] = 1;

    		$amount = $this->input->post('paypal_amount');
    		$username = $this->user->username;

    		//tạo mới mã giao dịch tạm cho lần nạp card
    		$params = array(
    			'payment_id' => -1,
                'user_id' => $this->user->userid,
    			'username' => $username,
    			'time' => microtime(true),
    			'method' => 'paypal',
    			'status' => 'start',
    			'comment' => 'Gửi dữ liệu đi, đang chờ trả lời'
    		);

    		//lấy mã giao dịch tạm thời
    		$transId = $this->payment_model->temporaryCardTransaction($params);
    		//get paypal button
    		$returnUrl = base_url() . 'payment/paypalSuccess/' . $transId;
    		$cancelUrl = base_url() . 'payment/paypalFail/' . $transId;
    		$ppData = array(
    			'transid' => $transId,
    			'amount' => $amount,
    			'return' => $returnUrl,
    			'cancel' => $cancelUrl,
    			'partnerInfo' => array(
    				'PartnerCode' => $partnerCode,
    				'Password' => $partnerPassword,
    				'Signature' => md5($transId . $amount . $returnUrl . $cancelUrl . $partnerCode . $partnerPassword . $partnerSecret)
    			)
    		);
	    	$nusoapPaypal = new nusoap_client('https://pay.appstore.vn/webservice/paypal', true);
	    	$ppResult = $nusoapPaypal->call('GetButton', $ppData);
    		if($fault = $nusoapPaypal->fault) {
				die($fault);
			}
			if($error = $nusoapPaypal->getError()) {
				die($error);
			}
	    	$data['paypalResult'] = $ppResult;
    	}
        
        // nạp bằng Smartlink
        if(isset($_POST['submitBank'])) {
            $this->load->model('payment_model');
            
            $partnerCode = 'BANK-APPSTORE';

    		$data['bankSubmitted'] = 1;

    		$amount = $this->input->post('bank_amount');
            $amount = str_replace('.','',$amount);
    		$username = $this->user->username;

    		//tạo mới mã giao dịch tạm cho lần nạp card
    		$params = array(
    			'payment_id' => -1,
                'user_id' => $this->user->userid,
    			'username' => $username,
    			'time' => microtime(true),
    			'method' => 'bank',
    			'status' => 'start',
    			'comment' => 'Gửi dữ liệu đi, đang chờ trả lời',
                'card_value' => $amount
    		);

    		//lấy mã giao dịch tạm thời
    		$transId = $this->payment_model->temporaryCardTransaction($params);
    		//get paypal button
    		$returnUrl = base_url() . 'payment/bankSuccess/' . $transId;
    		$cancelUrl = base_url() . 'payment/bankFail';
    		$ppData = array(
    			'transid' => $transId,
    			'amount' => $amount,
    			'return' => $returnUrl,
    			'cancel' => $cancelUrl,
    			'partnerInfo' => array(
    				'PartnerCode' => $partnerCode,
    				'Password' => $partnerPassword,
    				'Signature' => md5($transId . $amount . $returnUrl . $cancelUrl . $partnerCode . $partnerPassword . $partnerSecret)
    			)
    		);
	    	$nusoapBank = new nusoap_client('https://pay.appstore.vn/webservice/bank',true);
            $nusoapBank->soap_defencoding = 'UTF-8';
            $bankResult = $nusoapBank->call('GetLink', $ppData);
    		if($fault = $nusoapBank->fault) {
				die($fault);
			}
			if($error = $nusoapBank->getError()) {
				die($error);
			}
	    	$data['bankResult'] = $bankResult;
        }

        /*
    	//sms
    	$partnerSign = md5($partnerCode . $partnerPassword . $partnerSecret);

		$smsData = array(
			'partnerInfo' => array(
				'PartnerCode' => $partnerCode,
				'Password' => $partnerPassword,
				'Signature' => $partnerSign
			)
		);
		$nusoapSMS = new nusoap_client('https://pay.appstore.vn/webservice/sms', true);
		$smsResult = $nusoapSMS->call('GetSMS', $smsData);	//ví dụ: 8x61
        */
		//for debug
		//$smsResult = '6x86';
		$smspartner  = $this->setting_model->globalSetting('partnersms');
		$data['smsResult'] = $smspartner->value;
        
        $data['enableSms'] = $this->setting_model->globalSetting('enable_sms');
	   	$user = $this->user_model->getUserById($this->user->userid);
		$data['user'] = $user;

		//tỉ lệ quy đổi
		$this->load->model('setting_model');
			$keys = $this->setting_model->getKey('rate');
			$smss = array();
			$cards = array();
            $paypals = array();
			foreach($keys as $key) {
				if(substr($key->key, 0, 3) == 'sms') {
					$smss[$key->key] = number_format($key->value, 0, ',', '.');
				} else if(substr($key->key, 0,4) == 'card') {
					$cards[$key->key] = number_format($key->value, 0, ',', '.');
				} else if(substr($key->key, 0,4) == 'bank') {
                    $banks[$key->key] = number_format($key->value, 0, ',', '.');
                } else {
                    $paypals[$key->key] = number_format($key->value, 0, ',', '.');
                }
			}
			$data['smss'] = $smss;
			$data['cards'] = $cards;
            $data['paypals'] = $paypals;
            $data['banks'] = $banks;
        $data['rate'] = $this->user_model->getRate();
        $data['logged'] = $this->session->userdata('logged_in');
    	$this->load->view('tym', $data);
        $this->loadMainFooterMini();
    }
    
    /*
     * Đăng kí gói tải 7, 15, 30 ngày
     */
    function registerPackage($package_type) {
        if(!$this->user->logged) {
            echo "login";exit;
        }
        $this->load->model('setting_model');
        $packages = $this->setting_model->getKey('package');
        foreach($packages as $package) {
            if($package->key == $package_type) {
                $userId = $this->user->userid;
                $store = $this->storeCode;
                $day = substr($package->key, 1);
                $userPack = $this->user_model->getUserPack($userId, $store);
                if($userPack) {
                    if($userPack->package_expired && $userPack->package_expired > microtime(true)) {
                        $expired = $userPack->package_expired + $day * 24 * 60 * 60;
                    } else {
                        $expired = microtime(true) + $day * 24 * 60 * 60;
                    }
                    $updatePack = array(
                        'package_type' => $package_type,
                        'package_expired' => $expired
                    );
                    $this->user_model->updateUserPack($userId, $store, $updatePack);
                } else {
                    $expired = microtime(true) + $day * 24 * 60 * 60;
                    $insertPack = array(
                        'user_id' => $userId,
                        'package_type' => $package_type,
                        'package_expired' => $expired,
                        'store' => $store
                    );
                    $this->user_model->addUserPack($insertPack);
                }
                $this->user_model->decreaseTym($userId, 't2', $package->value);
                //save register package log
                $packageLog = array(
                    'user_id' => $userId,
                    'package_type' => $package_type,
                    'registered_date' => microtime(true),
                    'expired_date' => $expired - $day*24*60*60,
                    'last_expired_date' => $expired,
                    'tym_price' => $package->value,
                    'store' => $store
                );
                $this->user_model->addPackageLog($packageLog);
                echo "Bạn đã đăng kí gói tải $day ngày thành công";exit;
            }
        }
    }
    
    /*
     * Chỉnh sửa tài khoản
     */
    function infouser() {
        if(!$this->user->logged) {
            redirect('home');
        }
    	if(isset($_POST['city'])) {
    		$t2 = 0;	//cộng tym vàng( t2 ) cho lần đầu chỉnh sửa
    		
    		$params = array(
    			'city' => $this->input->post('city')
    		);
    		if(!$this->user->logged) {
    			$params['username'] = $this->input->post('username');
    			$this->session->set_userdata('username', $this->input->post('username'));
    		}
    		$curUser = $this->user_model->getUserById($this->user->userid);
            
            $birthday = $this->input->post('birthday');
            $x = explode('-', $birthday);
            $true_birthday = true;
            if(count($x) != 3) $true_birthday = false;
            if(!is_numeric($x[0]) || strlen($x[0]) != 4) $true_birthday = false;
            if(!is_numeric($x[1]) || strlen($x[1]) != 2) $true_birthday = false;
            if(!is_numeric($x[2]) || strlen($x[2]) != 2) $true_birthday = false;
    		
    		//if(!$curUser->city) $t2 += 40;	// 10 cho username, city, active, password lần đầu

    		if($curUser->active_by != 'inactive') {
    			$params['fullname'] = $this->input->post('fullname');
    			if(!$curUser->fullname && $curUser->fullname != $this->input->post('fullname')) $t2 += 10;
    			$params['birthday'] = $this->input->post('birthday');
    			if($curUser->birthday == '0000-00-00' && $true_birthday && $curUser->birthday != $this->input->post('birthday')) $t2 += 10;
    			$params['gender'] = $this->input->post('gender');
    			if($curUser->gender == -1 && $curUser->gender != $this->input->post('gender')) $t2 += 10;
    			$params['chucvu'] = $this->input->post('chucvu');
    			if(!$curUser->chucvu && $curUser->chucvu != $this->input->post('chucvu')) $t2 += 10;
    			$cmnd = $this->input->post('cmnd');
                if(is_numeric($cmnd)) {
                    $params['cmnd'] = $cmnd;
                    if(!$curUser->cmnd && $curUser->cmnd != $cmnd) $t2 += 10;
                }
    		} else {
    			//$params['type'] = 'user';
    			
    			//kích hoạt = sms
    			$active_by = $this->input->post('active_by');
    			if($active_by == 'sms') $data['sms_selected'] = '1';
    			if($active_by == 'email') {
    				if(!$curUser->email) $t2 += 10;
    				//send email
    				$active_code = $this->user_model->randomCode(32);
				    $from = "admin@appstore.vn";
				    $to = $this->input->post('email');
				    $subject = "Kích hoạt tài khoản trên AppStore.Vn!";
				    $message = "Bạn vừa đăng kí một tài khoản trên AppStore.Vn!<br />";
				    $message .= "Tài khoản của bạn cần phải kích hoạt mới sử dụng , <a href='" . site_url("user/active/$active_code") ."'>Nhấn vào đây để kích hoạt</a><br />";
				    $message .= "Nếu không được, hãy copy link sau vào trình duyệt ' " . site_url("user/active/$active_code") . " ' để kích hoạt <br />"; 
				    $message .= "Hãy trở thành thành viên để khám phá kho ứng dụng khổng lồ trên AppStore.Vn";
				    $senddate = (date("d M Y h:m:s -0500"));
				    $extraheaders = "From: $from" . "\nContent-Type: text/html\n";
				    
				    @mail("$to", "$subject", "$message", $extraheaders);
				    $params['email'] = $this->input->post('email');
				    $params['active_code'] = $active_code;
				    $data['email_sent'] = 'Kiểm tra email để kích hoạt tài khoản';
    			}
    		}
			
    		//nếu thay đổi password
    		if($this->input->post('password') != '') $params['password'] = md5($this->input->post('password'));
    		
    		//cộng tym cho user
    		$this->user_model->increaseTym($this->user->userid, 't2', $t2);
    		//lưu thông tin cập nhật
    		$this->user_model->update($this->user->userid, $params);
    		$data['success'] = 'Cập nhật thông tin thành công';
    	}
    	
    	//var_dump($this->user->userid);
    	if($this->user->logged) $user = $this->user_model->getUserById($this->user->userid);
    	else { 
    		//tạm thời khỏi lỗi mất session
    		if(!$this->session->userdata('session_id')) redirect('home');
    		else {
    			//trả về userid theo session_id
    			$user = $this->user_model->getUserByField(array('sessionid'=>$this->session->userdata('session_id')));
    			$this->session->set_userdata('userid', $user->user_id); 
    		}
    	}
		$data['user'] = $user;
		$data['logged'] = $this->user->logged;
		
    	/*
    	 * lấy đầu số SMS active
    	 */
    	$this->load->library('Nusoap');
	    	$nusoapClient = new nusoap_client('https://pay.appstore.vn/webservice/sms', true);
	    	$nusoapClient->soap_defencoding = 'UTF-8';
	    	
	    	//thông tin đối tác
	    	$partnerCode = 'APP';
			$partnerPassword = '243887efc3230890817bbc7d68f9e5dd';
			$partnerSecret = '52f699cc6140628748359a9cbd9a9a11';
			//sms
            /*
			$partnerSign = md5($partnerCode . $partnerPassword . $partnerSecret);  
			
			$smsData = array(
				'partnerInfo' => array(
					'PartnerCode' => $partnerCode,
					'Password' => $partnerPassword,
					'Signature' => $partnerSign
				)
			);
			$smsResult = $nusoapClient->call('GetSMS', $smsData);	//ví dụ: 8x61
             */
			//for debug
			//$smsResult = '6x86';
			$smsResult = '8x61';
			$data['smsResult'] = $smsResult; 		
			
			
    	$this->load->view('infouser', $data);        
        $this->loadMainFooterMini();
    }
    
    function pay() {
    	print_r( $this->app_model->getTymPrice(1) );
    }

    function exchange($tymType, $amountT1) {
        if($amountT1<0) {
            echo "-1";
            exit;
        }
        $result = $this->user_model->exchange($this->user->userid, $tymType, $amountT1);
        if ($result)
            echo "1";
        else
            echo "0";
    }
    
    function readPopup($status = '0') {
        $this->session->set_userdata('read_popup', $status);
        echo $status;
    }

    function help($item = '') {
        if ($item == '') {
            $this->load->view('help');
        } else {
            $data['key'] = $item;
            $this->load->view('help_client', $data);
        }
        $this->loadMainFooterMini();
    }
    function os3popup() {
        $this->session->set_userdata('os3popup', 1);
        echo "1";
    }
    
    function forshare() {
        $this->load->library('get4share');
        $result = $this->get4share->getLink('http://up.4share.vn/f/26171f1f1712121f/figipn241201.jpg.file');
        if($result) echo $result;
        else echo "";
    }
    
    function eventlist($page = 0) {
        $this->load->model('event_model');
        $this->loadMainHeader();
        
        $data['headertext'] = $this->textad->getText('headertext');
        $limit = 5;
        $start = $page * $limit;
        $filter = 'event_id';
        $events = $this->event_model->getAll($filter, $start, $limit);
        $data['events'] = $this->showEvents($events);
        $data['nextPage'] = $page + 1;
        $data['filter'] = $filter;
        $this->load->view('event_view', $data);
        
        $this->loadMainFooter();
    }
    
    function showEvents($events) {
        $data['events'] = $events;
        return $this->load->view('eventlist', $data, true);
    }
    
    function moreEvents($page, $filter = 'event_id') {
        $this->load->model('event_model');
        $limit = 5;
        $start = $limit * $page;
        $events = $this->event_model->getAll($filter, $start, $limit);
        echo $this->showEvents($events);
    }
    
    function event($eventId, $debug = '0') {
        $this->load->model('event_model');
        $this->loadMainHeader();
        $event = $this->event_model->getEvent($eventId);
        if($debug) $event = $this->event_model->getEvent($eventId, false);
        if(!$event) redirect('home/eventlist');
        $data['headertext'] = $this->textad->getText('headertext');
        $data['lastPlayers'] = $this->event_model->lastPlayer(10, $eventId);
        $data['mostPlayers'] = $this->event_model->mostPlayer(10, $eventId);
        $data['luckyPlayers'] = $this->event_model->luckyPlayer(10, $eventId);
        $data['event'] = $event;
        $data['logged'] = $this->user->logged;
        $eventType = $this->event_model->getEventType($event->type_id);
        $data['eventType'] = $eventType->code;
        if($eventType->code == 'giftcode') {
            $playTab = $this->playTabGiftcode($event);
        } else {
            $playTab = $this->playTabEvent($event);
        }
        $data['playTab'] = $playTab;
        $this->load->view('eventdetail', $data);
        $this->loadMainFooter();
    }
    
    function playTabGiftcode($event) {
        $data = array();
        // captcha
        $this->load->helper('captcha');
        $path = 'uploads/captcha/';
        $curTime = date('mY');
        if(!is_dir('./' . $path . $curTime)) mkdir('./' . $path . $curTime);
        $path .= $curTime . '/';
        $vals = array(
            'word' => strtolower(random_string('alnum', 2)),
            'img_path' => './' . $path,
            'img_url' => base_url() . $path,
            'img_width' => 40,
            'img_height' => 30
        );
        $cap = create_captcha($vals);
        //luu session captcha
        $this->session->set_userdata('capt_time', $cap['time']);
        $this->session->set_userdata('capt_word', $cap['word']);
        $data['captcha'] = $cap;
        // end captcha
        $data['event'] = $event;
        $this->load->model('textnote_model');
        $data['sender_tym_type'] = $this->textnote_model->getInfoByKey('sender_tym_type');
        $data['sender_tym_value'] = $this->textnote_model->getInfoByKey('sender_tym_value');
        $data['receiver_tym_type'] = $this->textnote_model->getInfoByKey('receiver_tym_type');
        $data['receiver_tym_value'] = $this->textnote_model->getInfoByKey('receiver_tym_value');
        return $this->load->view('event_giftcode', $data, true);
    }
    
    function genCaptcha() {
        //tao moi captcha
        $this->load->helper('captcha');
        $path = 'uploads/captcha/';
        $curTime = date('mY');
        if(!is_dir('./' . $path . $curTime)) mkdir('./' . $path . $curTime);
        $path .= $curTime . '/';
        $vals = array(
            'word' => strtolower(random_string('alnum', 2)),
            'img_path' => './' . $path,
            'img_url' => base_url() . $path,
            'img_width' => 40,
            'img_height' => 30
        );
        $cap = create_captcha($vals);
        //luu session captcha
        $this->session->set_userdata('capt_time', $cap['time']);
        $this->session->set_userdata('capt_word', $cap['word']);
        echo $cap['src'];
    }
    
    /*
     * generate giftcode
     * error code
     * 0 - request ko fai tu appstore
     * 1 - chua dang nhap
     * 2 - sai word, hoac het time
     */
    function genAppCode() {
        if(!from_appstore()) die('0');
        if(!$this->user->logged) die('1');
        $word = strtolower($this->input->post('captcha'));
        $appId = $this->input->post('app_id');
        //check captcha
        $sessionWord = $this->session->userdata('capt_word');
        $sessionTime = $this->session->userdata('capt_time');
        $expiration = time()-7200;
        if($word != $sessionWord || $expiration > $sessionTime) {
            die('2');
        }
        //check du tien ko
        $userId = $this->user->userid;
        $user = $this->user_model->getUserById($userId);
        $price = $this->app_model->getTymPrice($appId);
        $tymType = isset($price['type'])?$price['type']:'t2';
        $tymPrice = $price['price'];
        if($tymType == 't1' && $user->t1 < $tymPrice) {
            die('3');
        }
        if($tymType == 't2' && $user->t2 < $tymPrice) {
            die('3');
        }
        if($tymType == 't3' && $user->t3 < $tymPrice) {
            die('3');
        }
        if($tymType == 't4' && $user->t4 < $tymPrice) {
            die('3');
        }
        //tru tien user
        $this->user_model->decreaseTym($userId, $tymType, $tymPrice);
        //generate giftcode
        $this->load->model('giftcode_model');
        $code = $this->giftcode_model->generateCode($userId);
        //generate link
        $link = site_url('home/gift/'.$appId.'/'.$code);
        
        $phone = "$tymPrice$tymType";
        $dataGc = array(
            'code' => $code,
            'type' => 'app',
            'phone' => $phone,
            'value' => $link,
            'sender' => $userId,
            'status' => 0,
            'create_date' => time(),
            'expire_date' => time() + 30 * 24 * 60 * 60
        );
        $this->giftcode_model->add($dataGc);
        //tao moi captcha
        $this->load->helper('captcha');
        $path = 'uploads/captcha/';
        $curTime = date('mY');
        if(!is_dir('./' . $path . $curTime)) mkdir('./' . $path . $curTime);
        $path .= $curTime . '/';
        $vals = array(
            'word' => strtolower(random_string('alnum', 2)),
            'img_path' => './' . $path,
            'img_url' => base_url() . $path,
            'img_width' => 40,
            'img_height' => 30
        );
        $cap = create_captcha($vals);
        //luu session captcha
        $this->session->set_userdata('capt_time', $cap['time']);
        $this->session->set_userdata('capt_word', $cap['word']);
        echo $code."@@".$cap['src'];        
    }
        
    /*
     * generate giftcode
     * error code
     * 0 - request ko fai tu appstore
     * 1 - chua dang nhap
     * 2 - sai word, hoac het time
     * 3 - phone da duoc su dung
     * 4 - kich hoat = email ko tham gia
     * 5 - phone ko dung
     */
    function genGiftcode() {
        if(!from_appstore()) die('0');
        if(!$this->user->logged) die('1');
        $phone = $this->input->post('phone');
        if(!is_numeric($phone)) die('5');
        $first = substr($phone, 0, 1);
        if($first == '0') {
            $last = substr($phone, 1);
            $phone = "84"."$last";
        }
        $word = strtolower($this->input->post('captcha'));
        //check captcha
        $sessionWord = $this->session->userdata('capt_word');
        $sessionTime = $this->session->userdata('capt_time');
        $expiration = time()-7200;
        if($word != $sessionWord || $expiration > $sessionTime) {
            die('2');
        }
        //user kich hoat = email bi cam
        $username = $this->user->username;
        $userId = $this->user->userid;
        $user = $this->user_model->getUserById($userId);
        if(!$user->phone) {
            die('4');
        }
        //check phone chua su dung
        $checkPhone = $this->user_model->checkPhone($phone);
        if($checkPhone) {
            die('3');
        }
        //luu log view
        $eventId = $this->input->post('event_id');
        $this->load->model('event_model');
        $this->event_model->upEventPlay($eventId);        
        $this->event_model->upUserPlayEvent($userId, $username, $eventId);
        //generate giftcode
        $this->load->model('giftcode_model');
        $userId = $this->user->userid;
        $code = $this->giftcode_model->generateCode($userId);
        $dataGc = array(
            'code' => $code,
            'type' => 'invite',
            'phone' => $phone,
            'value' => 'from config',
            'sender' => $userId,
            'status' => 0,
            'create_date' => time(),
            'expire_date' => time() + 30 * 24 * 60 * 60
        );
        $this->giftcode_model->add($dataGc);
        //tao moi captcha
        $this->load->helper('captcha');
        $path = 'uploads/captcha/';
        $curTime = date('mY');
        if(!is_dir('./' . $path . $curTime)) mkdir('./' . $path . $curTime);
        $path .= $curTime . '/';
        $vals = array(
            'word' => strtolower(random_string('alnum', 2)),
            'img_path' => './' . $path,
            'img_url' => base_url() . $path,
            'img_width' => 40,
            'img_height' => 30
        );
        $cap = create_captcha($vals);
        //luu session captcha
        $this->session->set_userdata('capt_time', $cap['time']);
        $this->session->set_userdata('capt_word', $cap['word']);
        echo $code."@@".$cap['src'];        
    }
    
    /*
     * ma loi
     * 0 - request ko fai tu appstore
     * 1 - chua dang nhap
     * 2 - giftcode ko ton tai
     * 3 - giftcode da su dung
     * 4 - giftcode het han
     * 5 - phone nguoi dung # phone tuong ung giftcode
     */
    function chargeGiftcode() {
        if(!from_appstore()) die('0');
        if(!$this->user->logged) die('1');
        $code = $this->input->post('code');
        $userId = $this->user->userid;
        $user = $this->user_model->getUserById($userId);
        
        $this->load->model('giftcode_model');
        $giftcode = $this->giftcode_model->getInfo($code);
        if(!$giftcode) die('2');
        if($giftcode->status) die('3');
        if($giftcode->expire_date < time()) die('4');
        
        $tyms = array(
            't1' => 'đỏ',
            't2' => 'tím',
            't3' => 'xanh',
            't4' => 'vàng'
        );
        $type = $giftcode->type;
        if($type == 't1' || $type == 't2') {
            //cong tym cho nguoi nhan
            $tymType = $type;
            $tymValue = $giftcode->value;
            $this->user_model->increaseTym($userId, $tymType, $tymValue);
            //log
            $update = array(
                'receiver' => $userId,
                'status' => 1,
                'use_date' => time()
            );
            //neu nguoi gui la user # admin
            $senderId = $giftcode->sender;
            if($senderId) {
                $this->user_model->decreaseTym($senderId, $tymType, $tymValue);
            }
            $str = 'Giftcode hợp lệ. Bạn được tặng ' . $tymValue . ' TYM ' . $tyms[$tymType] . ' vào TK';
            $this->giftcode_model->update($giftcode->id, $update);
            echo $type."@@".$str;            
        } else if($type == 'invite') {
            $phone = $giftcode->phone;
            if($phone != $user->phone) {
                die('5');
            } else {
                $this->load->model('textnote_model');
                $senderTymType = $this->textnote_model->getInfoByKey('sender_tym_type');
                $senderTymValue = $this->textnote_model->getInfoByKey('sender_tym_value');
                $receiverTymType = $this->textnote_model->getInfoByKey('receiver_tym_type');
                $receiverTymValue = $this->textnote_model->getInfoByKey('receiver_tym_value');
                $sUserId = $giftcode->sender;
                $sTymType = $senderTymType->value;
                $sTymValue = $senderTymValue->value;
                $rTymType = $receiverTymType->value;
                $rTymValue = $receiverTymValue->value;
                $rUserId = $userId;
                
                $this->user_model->increaseTym($sUserId, $sTymType, $sTymValue);
                $this->user_model->increaseTym($rUserId, $rTymType, $rTymValue);
                //update status
                $value = "sender=$sUserId:$sTymValue$sTymType@@receiver=$rUserId:$rTymValue$rTymType";
                $update = array(
                    'value' => $value,
                    'receiver' => $rUserId,
                    'status' => 1,
                    'use_date' => time()
                );
                $rUsername = $this->user->username;
                $sUser = $this->user_model->getUserById($sUserId);
                $sUsername = $sUser->username;
                $str = 'Giftcode hợp lệ. Bạn được tặng' . $rTymValue . ' TYM ' . $tyms[$rTymType] . ' vào TK. TK ' . $sUsername . ' được tặng ' . $sTymValue . ' TYM ' . $tyms[$sTymType] . ' vào TK.';
                $this->giftcode_model->update($giftcode->id, $update);
                echo $type."@@".$str;
            }
        } else if($type == 'app')  {
            echo $type."@@".$giftcode->value;
        } else if($type == 'film') {
            $str = 'Giftcode hợp lệ. Bạn được tặng gói xem phim có thời hạn xxx ngày vào TK.';
            //echo $type."@@".$str;
        }  
    }
    
    function playTabEvent($event) {
        $data = array();
        $giftboxs = $this->event_model->getGiftboxByEventId($event->event_id);
        $data['event'] = $event;
        $data['giftboxs'] = $giftboxs;
        return $this->load->view('event_event', $data, true);
    }
    
    /*
     * mở hộp quà
     * 0 - chưa đăng nhập
     * 1 - xịt lô :D
     * 2 - không đủ tym
     */
    function openGiftbox($giftboxId) {        
        $this->load->model('event_model');
        if(!$this->user->logged) {
            echo "0";exit;
        }
        // trừ tiền user
        $giftbox = $this->event_model->getGiftbox($giftboxId);
        $returnText = $giftbox->return_text;
        $userId = $this->user->userid;
        $tymPrice = $giftbox->input_tym;
        $tymType = $giftbox->tym_type;
        $result = $this->user_model->decreaseTym($userId, $tymType, $tymPrice);
        if(!$result) {
            echo "2";exit;
        }
        $logData = array(
            'user_id' => $userId,
            'event_id' => $giftbox->event_id,
            'username' => $this->user->username,
            'giftbox_id' => $giftboxId,
            'tym_type' => $tymType,
            'tym_price' => $tymPrice,
            'receive_status' => 0,
            'time' => time()
        );
        /*
         * set log tham gia sự kiện
         */
        $this->event_model->upEventPlay($giftbox->event_id);
        $username = $this->user->username;
        $this->event_model->upUserPlayEvent($userId, $username, $giftbox->event_id);
        /*
         * xác suất có thể được tham gia random trúng quà
         * = time() / n
         */
        if($giftbox->random > 0) {
            $n = $giftbox->random;
        } else {
            $n = 5;
        }
        $time = time();
        $xs1 = $time%$n;
        if($xs1) {
            //lưu log xịt lô :D
            $logData['reason'] = 'Trượt lần đầu do xác suất';
            $this->event_model->addLog($logData);
            echo $returnText;exit;
        }
        
        // ko có hộp quà nào
        $gifts = $this->event_model->getGift($giftboxId);
        if(!$gifts) {
            //lưu log xịt lô :D
            $logData['reason'] = 'Hộp quà không có quà nào';
            $this->event_model->addLog($logData);
            echo $returnText;exit;
        }        
        /*
         * xác suất trúng quà = time() / 100
         */
        $time = $time - $this->user->userid;
        $xs = $time % 100;
        $start = 0;
        $end = $gifts[0]->xacsuat;
        for($i=0; $i<count($gifts); $i++) {
            if($i==count($gifts)-1) {
                $gift = $gifts[count($gifts)-1]; 
                /*
                 *  quà trúng với nhiều quà
                 *  quà xét với 1 quà
                 */
                if($xs >= $gift->xacsuat) {
                    //lưu log xịt lô :D
                    $logData['reason'] = 'Không trúng thưởng (chỉ có 1 quà)';
                    $this->event_model->addLog($logData);
                    echo $returnText;exit;
                }
                break;
            }
            if($start <= $xs && $xs < $end) {
                $gift = $gifts[$i]; // hộp quà trúng
                break;
            }
            $start += $gifts[$i]->xacsuat;
            $end = $start + $gifts[$i+1]->xacsuat;
        }
        /*
         * hộp quà trúng $gift
         */
        $quantity = $gift->quantity;
        $giftType = $gift->type;
        if($giftType == 'giftcode' || $giftType == 'card') {
            $valueStr = $gift->value;
            $valueArr = explode(';',$valueStr);
            $quantity = count($valueArr);
            if(!$valueStr) $quantity = 0;
        }
        $datrung = $gift->datrung;
        if($datrung >= $quantity && $giftType != 'giftcode' && $giftType != 'card' && $giftType != 'text') {
            //lưu log xịt lô :D
            $logData['gift_id'] = $gift->gift_id;
            $logData['reason'] = 'Đã hết phần thưởng';
            $this->event_model->addLog($logData);
            echo $returnText;exit;
        }
        
        /*
         * khoảng trúng quà [0,n] - n bằng time() % $quantity
         * khoảng chắc chắn trúng [0,x] - x là số quà còn lại = $remain-1
         */
        /*
         * bỏ xác suất lần 2
        $remain = $quantity - $datrung;
        $n = $time % $quantity;
        $x = $remain - 1;
        if($n > $x) {
            //lưu log xịt lô :D
            $logData['gift_id'] = $gift->gift_id;
            $logData['reason'] = 'Trượt lần 2 do xác suất';
            $this->event_model->addLog($logData);
            echo "1";exit;
        }
         */
        // trúng lô :D
        switch($gift->type) {
            case 't1':
            case 't2':
            case 't3':
            case 't4':
                $reason = 'Trúng thưởng '. $gift->value . ' TYM ' . $gift->type;
                $giftType = 'tym';
                $this->user_model->increaseTym($userId, $gift->type, $gift->value);
                break;
            case 'giftcode':
                $reason = 'Trúng thưởng mã giftcode = ' . $gift->value;
                $giftType = 'giftcode';
                break;
            case 'card':
                $reason = 'Trúng thưởng mã thẻ cào ' . $gift->value;
                $giftType = 'card';
                break;
            case 'text':
                $reason = 'Trúng thưởng đoạn text ;)) - ' . $gift->value;
                $giftType = 'text';
                break;
            case 'img':
                $reason = 'Trúng thưởng bức ảnh ;)) - ' . $gift->value;
                $giftType = 'text';
                break;
            case 'mp3':
                $reason = 'Trúng thưởng đoạn nhạc ;))';
                $giftType = 'mp3';
                break;
            default:
                $reason = 'Mặc định là trúng icon :D';
                $giftType = 'icon';
                break;
        }
        $logData['gift_id'] = $gift->gift_id;
        $logData['receive_status'] = 1;
        $logData['receive_type'] = $gift->type;
        $giftValue = $gift->value;
        if($giftType == 'giftcode' || $giftType == 'card') {
            //xóa 1 phần tử nếu là card và giftcode
            $giftValue = $valueArr[0];
            unset($valueArr[0]);
            $valueStr = implode(';', $valueArr);
            //cap nhat lai giftvalue
            $dataGift = array('value' => $valueStr);
            $this->event_model->updateGift($gift->gift_id, $dataGift);
            if($giftType == 'giftcode') {
                $reason = 'Trúng thưởng mã giftcode = ' . $giftValue;
            }
            if($giftType == 'card') {
                $reason = 'Trúng thưởng mã thẻ cào = ' . $giftValue;
            }
        }
        if($giftType == 'text' || $giftType == 'img') {
            $valueStr = $gift->value;
            $valueArr = explode(';', $valueStr);
            $rand = time() % count($valueArr);
            $giftValue = $valueArr[$rand];
            $reason = 'Trúng thưởng ' . $giftType;
        }
        $logData['reason'] = $reason;
        $logData['receive_value'] = $giftValue;
        $this->event_model->upRewardGift($gift->gift_id);
        $this->event_model->addLog($logData);
        echo $gift->type . "@@" . $giftValue . '@@' . $gift->more_text;
    }
    
    /*
     * khuyen mai 
     */
    function promotion($page = '0', $filter = 'promo_price') {
        $this->loadMainHeader();
        $limit = 25;
        $start = $page * $limit;
        $apps = $this->app_model->getPromotionApp($start, $limit, $filter);
        if($apps) {
            $data['apps'] = $this->showPromotionApps($apps);
        } else {
            $data['apps'] = 0;
        }
        $data['filter'] = $filter;
        $data['headertext'] = $this->textad->getText('headertext');
        $this->load->view('promotion_view', $data);
        $this->loadMainFooter();
    }
    
    function morePromotionApp($page, $filter) {
        $limit = 25;
        $start = $limit * $page;
        $apps = $this->app_model->getPromotionApp($start, $limit, $filter);
        echo $this->showPromotionApps($apps);
    }
    
    function showPromotionApps($apps) {
        $data['apps'] = $apps;
        return $this->load->view('promotionlist', $data, true);
    }
    
    /*
     * giao dich nguoi dung
     */
    function transaction() {
        if(!$this->user->logged) {
            redirect('home');
        }
        $this->load->view('trans_view');
        $this->loadMainFooterMini();
    }
    
    function eventLog($page = '0', $day = '0', $month = '0', $year = '0') {
        if(!$this->user->logged) {
            redirect('home');
        }
        $this->load->model('event_model');
        $limit = 15;
        $start = $page * $limit;
        if($day && $month && $year) {
            $checkDate = checkdate($month, $day, $year);
            if($checkDate) $time = $year.$month.$day;
            else $time = 0;
        } else {
            $time = 0;
        }
        $userId = $this->user->userid;
        $filter['start'] = $start;
        $filter['limit'] = $limit;
        $filter['time'] = $time;
        $logs = $this->event_model->getEventLogByUserId($filter, $userId);
        $data['logs'] = $this->showEventLog($logs);
        $data['nextPage'] = $page + 1;
        $data['time'] = $time;
        
        $this->load->view('trans_eventlog', $data);
        $this->loadMainFooterMini();
    }
    
    function moreEventLog($time, $page) {
        $this->load->model('event_model');
        $limit = 15;
        $start = $limit * $page;
        $userId = $this->user->userid;
        $filter['start'] = $start;
        $filter['limit'] = $limit;
        $filter['time'] = $time;
        $logs = $this->event_model->getEventLogByUserId($filter, $userId);
        echo $this->showEventLog($logs);
    }
    
    function showEventLog($logs) {
        $data['logs'] = $logs;
        return $this->load->view('trans_eventloglist', $data, TRUE);
    }
}
