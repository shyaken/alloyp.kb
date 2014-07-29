<?php
class Setting extends Controller {
	function Setting() {
		parent::__construct();
	    $this->load->model('setting_model', 'setting');
	    $this->load->model('app_model', 'app');
	    $this->load->model('category_model', 'category');
        
        if($this->session->userdata('is_admin') != 'yes') {
            redirect('admin/login');
        }
        if($this->session->userdata('is_root') != 'yes') {
            redirect('admin/dashboard');
        }
        
        $controller = $this->router->fetch_class();
        $method = $this->router->fetch_method();
	}
	
	function index() {
		$this->load->view('admin/header');
		$data['rate1_2'] = $this->setting->getValueByKey('rate1_2');
		$data['rate1_3'] = $this->setting->getValueByKey('rate1_3');
		$data['rate1_4'] = $this->setting->getValueByKey('rate1_4');
        $data['partnersms'] = $this->setting->globalSetting('partnersms');
        $data['enable_sms'] = $this->setting->globalSetting('enable_sms');
        $data['event'] = $this->setting->globalSetting('random_event');
		$data['keys'] = $this->setting->getKey('rate');	 //list key by group
        $packages = $this->setting->getKey('package');  // list package
        ksort($packages);
        $data['packages'] = $packages;
        $data['popups'] = $this->setting->getKey('option'); // popup
		$this->load->view('admin/setting', $data);
		$this->load->view('admin/footer');
	}
	
	/*
	 * lưu thông tin quy đổi
	 * gọi từ hàm saveRate() trong view setting.php
	 */
	function saveRate() {
		$key = $this->input->post('key');
		$value = $this->input->post('value');
		$this->setting->updateValueByKey($key, $value);
		$tyms = array(
			'rate1_2' => ' tym <span id="yellow-tym">♥</span>(tím)',
			'rate1_3' => ' tym xanh <span id="blue-tym">♥</span>(xanh)',
			'rate1_4' => ' tym xanh <span id="green-tym">♥</span>(vàng)'
		);
		$logs = array(
			'admin_id' => $this->session->userdata('admin_id'),
			'admin' => $this->session->userdata('adminname'),
			'content' => '1 tym <span id="red-tym">♥</span>(đỏ) tương ứng với ' . $value . $tyms[$key],
			'time' => microtime(true)
		);
		$this->setting->saveSettingLog($logs);
		echo "1";
	}
    
    function savePartnerSMS() {
        $key = $this->input->post('key');
        $value = $this->input->post('value');
        $this->setting->globalSettingUpdate($key, $value);
        echo "1";
    }
	
	/*
	 * lưu thông tin quy đổi SMS, CARD
	 * gọi từ hàm saveKey() trong view setting.php
	 */
	function saveKey() {
		$setting_id = $this->input->post('setting_id');
			$setting = $this->setting->getInfo($setting_id);
		$value = $this->input->post('value');
		$this->setting->update($setting_id, array('value'=>$value));
		$rates = array(
			'sms0' => ' đầu số x0xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms1' => ' đầu số x1xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms2' => ' đầu số x2xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms3' => ' đầu số x3xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms4' => ' đầu số x4xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms5' => ' đầu số x5xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms6' => ' đầu số x6xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'sms7' => ' đầu số x7xx được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'card10' => ' thẻ 10.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'card20' => ' thẻ 20.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'card50' => ' thẻ 50.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'card100' => ' thẻ 100.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'card300' => ' thẻ 300.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'card200' => ' thẻ 200.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
			'card500' => ' thẻ 500.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'paypal5' => ' 5$ paypal được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'paypal10' => ' 10$ paypal được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'paypal50' => ' 50$ paypal được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'p2' => 'Gói phí 2 ngày cần ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'p7' => 'Gói phí 7 ngày cần ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'p15' => 'Gói phí 15 ngày cần ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'p30' => 'Gói phí 30 ngày cần ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank20' => 'bank 20.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank50' => 'bank 50.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank100' => 'bank 100.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank200' => 'bank 200.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank500' => 'bank 500.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank1000' => 'bank 1.000.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'bank2000' => 'bank 2.000.000 VNĐ được nhận ' . $value . ' tym <span id="red-tym">♥</span> đỏ',
            'package' => 'Áp dụng gói cho kho là ' . $value,
            'popup' => 'Thiết lập bật popup ở trang chủ là ' . $value
		);
		$logs = array(
			'admin_id' => $this->session->userdata('admin_id'),
			'admin' => $this->session->userdata('adminname'),
			'content' => $rates[$setting->key],
			'time' => microtime(true)
		);
		$this->setting->saveSettingLog($logs);
		echo "1";
	}	
	
	/*
	 * hiển thị 20 setting logs
	 */
	function loadSettingLog($limit = '20', $page = '0') {
        $page = $page - 1;
		$logs = $this->setting->getSettingLog($limit, $page*$limit);
		$data['logs'] = $logs;
		$this->load->view('admin/settinglog', $data);
	}
	
	/*
	 * app settings
	 */
	function app($sort = 'app_id', $order = 'DESC', $app_id = '0', $app_name = '0', $priority_price = '-1', $price = '0', $tym = '0', $method = '0', $package = '-1', $limit = '10', $start = '0') {
		$this->load->view('admin/header');
		
		if(isset($_POST['savePrice'])) {
			if(isset($_POST['selected'])) {
				$appUpdate = array(
						'tym_price' => $this->input->post('list_tym_price'),
						'tym_type' => $this->input->post('list_tym_type'),
						'method' => $this->input->post('list_method'),
						'package' => $this->input->post('list_package'),
						'priority_price' => 1
					);
				foreach($_POST['selected'] as $appid) {
					$this->app->update($appid, $appUpdate);
				}
				$data['success'] = 'Cập nhật giá thành công cho app (s)';
			}
		}
		
		$filter = array(
			'sort' => $sort,
			'order' => $order, 
			'app_id' => $app_id,
			'app_name' => $app_name,
			'priority_price' => $priority_price,
			'price' => $price,
			'tym' => $tym,
			'method' => $method,
			'package' => $package,
			'limit' => $limit,
			'start' => $start
		);
		
		$totalSettingApp = $this->app->totalSettingApp($filter);
		$data['totalSettingApp'] = $totalSettingApp;
		$data['apps'] = $this->app->allSettingApp($filter);
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['app_id'] = $app_id;
		$data['app_name'] = $app_name;
		$data['priority_price'] = $priority_price;
		$data['price'] = $price;
		$data['tym'] = $tym;
		$data['method'] = $method;
		$data['package'] = $package;		
		$data['limit'] = $limit;
				
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/setting/app/$sort/$order/$app_id/$app_name/$priority_price/$price/$tym/$method/$package/$limit");
        $config['total_rows'] = $totalSettingApp;
        $config['uri_segment'] = 14;
        $config['per_page'] = $limit;
        $config['full_tag_open'] = '<div class="pagination"><div class="links">';
    	$config['full_tag_close'] = '</div></div>';
		$config['cur_tag_open'] = '<span class="active curved">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';
        $this->pagination->initialize($config);	
		
		$this->load->view('admin/settingapp', $data);
		$this->load->view('admin/footer');
	}
	
	function editApp() {
		$data = array(
			'priority_price' => $this->input->post('priority_price'),
			'tym_price' => $this->input->post('tym_price'),
			'tym_type' => $this->input->post('tym_type'),
			'method' => $this->input->post('method'),
			'package' => $this->input->post('package')
		);
		$this->app->update($this->input->post('app_id'), $data);
		echo "1";
	}
	
/*
	 * category settings
	 */
	function category($sort = 'category_id', $order = 'DESC', $category_id = '0', $category_name = '0', $tym = '0', $method = '0', $package = '-1', $limit = '50', $start = '0') {
		$this->load->view('admin/header');
		
		$filter = array(
			'sort' => $sort,
			'order' => $order, 
			'category_id' => $category_id,
			'category_name' => $category_name,
			'tym' => $tym,
			'method' => $method,
			'package' => $package,
			'limit' => $limit,
			'start' => $start
		);
		
		$data['categories'] = $this->category->allSettingCategory($filter);
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;
		
		$this->load->view('admin/settingcategory', $data);
		$this->load->view('admin/footer');
	}
	
	function editCategory() {
		$dataCategory = array(
			'price' => $this->input->post('price'),
			'tym_type' => $this->input->post('tym_type'),
			'method' => $this->input->post('method'),
			'package' => $this->input->post('package')
		);
		
		//cập nhật giá cho các app không ưu tiên, thuộc category
		$where = array(
			'category' => $this->input->post('category_id'),
			'priority_price' => 0
		);
		$data = array(
			'tym_price' => $this->input->post('price'),
		);
		$this->app->updateApp($where, $data);
		
		$this->category->update($this->input->post('category_id'), $dataCategory);
		echo "1";
	}
}
