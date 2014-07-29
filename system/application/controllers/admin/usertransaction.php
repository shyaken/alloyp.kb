<?php
class Usertransaction extends Controller
{
	private $msg = array();
	function Usertransaction()
	{
		parent::__construct();
		$this->load->model('transaction_model', 'transaction');
 
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
	
	function index()
	{
		$this->viewAll();
	}
	
	function viewAll($sort = 'id', $order = 'DESC', $username = '0', $method = '0', $user_input = '-1', $sms_provider = '-1', $card_value = '0', $status = '0', $starttime = '0', $endtime = '0', $limit = '20', $start = '0') 
	{
		$start_store = $starttime;
		$end_store = $endtime;
		
		if($starttime != '0') {
			$starttime = strtotime(str_replace('_', '/', $starttime) . '00:00:00');
			$endtime = strtotime(str_replace('_', '/', $endtime) . '23:59:59');
		}
		
		$filter = array(
			'sort' => $sort,
			'order' => $order,
			'username' => $username,
			'method' => $method,
            'user_input' => $user_input,
            'sms_provider' => $sms_provider,
            'card_value' => $card_value,
            'status' => $status,
			'starttime'	=> $starttime,
			'endtime' => $endtime,
			'limit' => $limit,
			'start' => $start
		);
		
		$totalTransaction = $this->transaction->totalTransaction($filter);
		$totalTransactionBySMS = $this->transaction->totalTransactionBySMS($filter);
		$totalTransactionByCARD = $this->transaction->totalTransactionByCARD($filter);
		$transactions = $this->transaction->allTransaction($filter);
		$totalTym = $this->transaction->totalTym($filter);
		$totalTymBySMS = $this->transaction->totalTymBySMS($filter);
		$totalTymByCARD = $this->transaction->totalTymByCARD($filter);
		
        $data['remainT1'] = number_format($this->transaction->remainTym('t1'), 0, '.', '.');
        $data['remainT2'] = number_format($this->transaction->remainTym('t2'), 0, '.', '.');
        $data['remainT3'] = number_format($this->transaction->remainTym('t3'), 0, '.', '.');
        $data['remainT4'] = number_format($this->transaction->remainTym('t4'), 0, '.', '.');
		$data['totalTransaction'] = $totalTransaction;
		$data['totalTransactionBySMS'] = $totalTransactionBySMS;
		$data['totalTransactionByCARD'] = $totalTransactionByCARD;
		$data['transactions'] = $transactions;
		$data['totalTym'] = number_format($totalTym, 0, ',', '.');
		$data['totalTymBySMS'] = number_format($totalTymBySMS, 0, ',', '.');
		$data['totalTymByCARD'] = number_format($totalTymByCARD, 0, '.', '.');
        $data['starttime'] = $start_store;
        $data['endtime'] = $end_store;
        $data['username'] = $username;
        $data['method'] = $method;
        $data['order'] = $order;
        $data['user_input'] = $user_input;
        $data['sms_provider'] = $sms_provider;
        $data['card_value'] = $card_value;
        $data['status'] = $status;
        $data['sort'] = $sort;
        $data['start'] = $start;
        $data['limit'] = $limit;
		if($starttime == '0') {
			$data['info'] = 'Thống kê tất cả giao dịch cho đến hiện tại';
		} else {
			$data['info'] = 'Thống kê giao dịch ' . date('d/m/Y', $starttime) . ' đến ' . date('d/m/Y', $endtime) . '(<font color="green">dd/mm/yyyy</font>)';
		}        		
		// phân trang
		$this->load->library('pagination');
        $config['base_url'] = site_url("admin/usertransaction/viewAll/$sort/$order/$username/$method/$user_input/$sms_provider/$card_value/$status/$start_store/$end_store/$limit");
        $config['total_rows'] = $totalTransaction;
        $config['uri_segment'] = 15;
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
        
        $this->load->model('setting_model');
        $rates = $this->setting_model->getKey();
        $cards = array();
        foreach($rates as $rate) {
            if(substr($rate->key, 0, 4) == 'card') 
                $cards[] = substr($rate->key, 4, strlen ($rate->key)) * 1000;
        }
        asort($cards);
        $data['cards'] = $cards;

        $data['is_admin'] = $this->session->userdata('is_root');
        $this->load->view('admin/header');
		$this->load->view('admin/transaction', $data);
		$this->load->view('admin/footer');
	}
	
	function detail($id) {
		$data['transaction'] = $this->transaction->getTransaction($id);
		$this->load->view('admin/transactiondetail', $data);
	}
}
