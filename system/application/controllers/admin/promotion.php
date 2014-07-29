<?php
class Promotion extends Controller {
    function Promotion() {
        parent::__construct();
        $this->load->model('app_model');
        $this->load->model('category_model');
        
        $base_url = $this->session->userdata('base_url');
        $is_admin = $this->session->userdata('is_admin');
        $is_root = $this->session->userdata('is_root');
        
        if($base_url != base_url() && $is_root != 'yes') {
            redirect('admin/login/logout');
        }
        if($is_admin != 'yes') {
            redirect('admin/login');
        }
	    $controller = $this->router->fetch_class();
        $permission = $this->session->userdata('permission');
        $permissions = explode('|', $permission);
            if(!in_array($controller, $permissions) && $is_root != 'yes') {
            	show_error("You haven't permission to view this page!");
            }
    }
    
    function index() {
        $this->app();
    }
    
    /*
	 * app settings
	 */
	function app($sort = 'app_id', $order = 'DESC', $app_id = '0', $app_name = '0', $category = '0', $price = '0', $p_price = '0', $tym = '0', $startdate = '0', $enddate = '0', $enable = '-1', $limit = '10', $start = '0') {
        //$this->output->enable_profiler(TRUE);
        $this->load->view('admin/header');
		
		if(isset($_POST['savePrice'])) {
			if(isset($_POST['selected'])) {
				$appUpdate = array(
                    'promo_price' => $this->input->post('list_tym_price'),
                    'promo_enable' => $this->input->post('list_promo_enable')
                );
                $logData = array(
                    'promo_price' => $this->input->post('list_tym_price'),
                    'promo_enable' => $this->input->post('list_promo_enable')
                );
                if(is_numeric($this->input->post('list_startdate')) && is_numeric($this->input->post('list_enddate'))) {
                    $appUpdate['promo_start'] = strtotime($this->input->post('list_startdate').' 00:00:01');
                    $appUpdate['promo_end'] = strtotime($this->input->post('list_enddate').' 23:59:59');
                    $logData['promo_start'] = strtotime($this->input->post('list_startdate').' 00:00:01');
                    $logData['promo_end'] = strtotime($this->input->post('list_enddate').' 23:59:59');
                }
				foreach($_POST['selected'] as $appid) {
                    $logData['app_id'] = $appid;
					$this->app_model->update($appid, $appUpdate);
                    $this->app_model->addPromotionLog($logData);
				}
				$data['success'] = 'Cập nhật giá khuyến mãi thành công cho app (s)';
			}
		}
        
        $save_startdate = $startdate;
        $save_enddate = $enddate;
        
        if($startdate) {
            $startdate = strtotime($startdate . '00:00:01');
            $enddate = strtotime($enddate . '23:59:59');
        }
		
		$filter = array(
			'sort' => $sort,
			'order' => $order, 
			'app_id' => $app_id,
			'app_name' => $app_name,
            'category' => $category,
			'price' => $price,
            'p_price' => $p_price,
			'tym' => $tym,
			'startdate' => $startdate,
			'enddate' => $enddate,
            'enable' => $enable,
			'limit' => $limit,
			'start' => $start
		);
		
		$totalPromotionApp = $this->app_model->totalPromotionApp($filter);
		$data['totalPromotionApp'] = $totalPromotionApp;
		$data['apps'] = $this->app_model->allPromotionApp($filter);
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['app_id'] = $app_id;
		$data['app_name'] = $app_name;
		$data['category_filter'] = $category;
		$data['price'] = $price;
        $data['p_price'] = $p_price;
		$data['tym'] = $tym;
		$data['startdate'] = $save_startdate;
		$data['enddate'] = $save_enddate;		
        $data['enable'] = $enable;
		$data['limit'] = $limit;
        // load all categories
        $data['categories'] = $this->category_model->listAll();
				
		// phân trang cho app
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/promotion/app/$sort/$order/$app_id/$app_name/$category/$price/$p_price/$tym/$save_startdate/$save_enddate/$enable/$limit");
        $config['total_rows'] = $totalPromotionApp;
        $config['uri_segment'] = 16;
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
		
		$this->load->view('admin/promotionapp', $data);
		$this->load->view('admin/footer');
	}
	
	function editApp() {
        $startDate = strtotime($this->input->post('promo_start').' 00:00:01');
        $endDate = strtotime($this->input->post('promo_end').' 23:59:59');
		$data = array(
			'promo_price' => $this->input->post('promo_price'),
			'promo_start' => $startDate,
			'promo_end' => $endDate
		);
		$this->app_model->update($this->input->post('app_id'), $data);
        // luu log
        $logData = array(
            'app_id' => $this->input->post('app_id'),
            'promo_price' => $this->input->post('promo_price'),
            'promo_start' => strtotime($this->input->post('promo_start').' 00:00:01'),
            'promo_end' => strtotime($this->input->post('promo_end').' 23:59:59'),
            'promo_enable' => $this->input->post('promo_enable')
        );
        $this->app_model->addPromotionLog($logData);
		echo "1";
	}
    
    function enablePromo() {
        $appId = $this->input->post('app_id');
        $status = $this->input->post('status');
        $data = array('promo_enable' => $status);
        $this->app_model->update($appId, $data);
        if($status) {
            echo "<a href='javascript:promoEnable(".$appId.", 0);'>Tắt đi</a>";
        } else {
            echo "<a href='javascript:promoEnable(".$appId.", 1);'>Bật lên</a>";
        }
    }
}
?>
