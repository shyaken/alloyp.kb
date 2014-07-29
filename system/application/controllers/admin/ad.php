<?php
class Ad extends Controller
{
     private $_store = array(
        'http://appstore.vn/' => 'all',
        'http://appstore.vn/a/' => 'a',
        'http://appstore.vn/b/' => 'b',
        'http://appstore.vn/c/' => 'c',
        'http://appstore.vn/e/' => 'e',
        'http://appstore.vn/f/' => 'f',
        'http://appstore.vn/i/' => 'i',
        'http://app.vn/' => 'all'
     );
     private $storeCode;
    function Ad()
    {
            parent::__construct();
            $this->load->model('advertise_model', 'ad');
    $this->storeCode = $this->_store[base_url()];

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
            $data['ads'] = $this->ad->listAll();

            $this->load->view('admin/header');
            $this->load->view('admin/advertise', $data);
            $this->load->view('admin/footer');
    }

    function add()
    {
            $data = array();
            if(isset($_POST['add'])) {
                    $error = false;
                    if($this->input->post('optionad') == "image") {
                            $path = '/' . UPLOADFOLDER .'/ad/';
                        $config['upload_path'] = '.' . $path;
                        if(!is_dir($config['upload_path'])) mkdir($config['upload_path']);
                        $config['allowed_types'] = 'jpg|png|gif';
                        $this->load->library('upload', $config);
                        if(!$this->upload->do_upload('image')) {
                            $data['error'] = 'Upload ảnh bị lỗi';
                            $error = true;
                        } else {
                                $upload = $this->upload->data();
                                $image = $path . $upload['file_name'];
                                $code = '';
                        } 

                    } else {
                        $code = $this->input->post('code');
                        $image = '';
                    }

                    if(!$error) {
                        $data['success'] = 'Thêm mới quảng cáo thành công';
                        if($this->input->post('type') == 'all') $unit = 4;
                        else $unit = $this->input->post('unit');
                        $unixStartDate = strtotime($this->input->post("start_date")."00:00:01");
                        $unixEndDate = strtotime($this->input->post("end_date")."23:59:59");
                        $params = array(
                                'name' => $this->input->post('name'),
                                'url' => $this->input->post('url'),
                                'image' => $image,				
                                'code' => $code,
                                'section' => $this->input->post('section'),
                                'start' => $this->input->post('start'),
                                'unit' => $unit,
                                'type' => $this->input->post('type'),
                                'publish' => $this->input->post('publish'),
                                'start_date' => $unixStartDate,
                                'end_date' =>$unixEndDate
                        );
                        $this->ad->add($params);
                    }
        $this->load->library('Cache', 'cache');
        $this->cache->delete($this->storeCode.'index');
            }

            $data['unitHeader'] = $this->ad->unitUsed('header');
            $data['unitFooter'] = $this->ad->unitUsed('footer');
            $data['checkHeader1'] = $this->ad->checkHeader1();  

            $this->load->view('admin/header');
            $this->load->view('admin/advertiseadd', $data);
            $this->load->view('admin/footer');
    }

    function edit($id)
    {
            if(isset($_POST['edit'])) {
                    $curAd = $this->ad->getInfo($id);

                    if($this->input->post('optionad') == 'image') {
                        $path = '/' . UPLOADFOLDER . '/ad/';
                        if(!is_dir('.' . $path)) mkdir('.' . $path);
                        $config['upload_path'] = './' . $path;
                        $config['allowed_types'] = 'jpg|png|gif';

                        $this->load->library('upload', $config);
                        if($this->upload->do_upload('image')) {
                            $upload = $this->upload->data();
                            $image = $path . $upload['file_name'];
                        }
                    } else {
                        $code = $this->input->post('code');
                    }

                    if($this->input->post('type') == 'all') $unit = 4;
                    else $unit = $this->input->post('unit');
                    $unixStartDate = strtotime($this->input->post("start_date")."00:00:01");
                    $unixEndDate = strtotime($this->input->post("end_date")."23:59:59");
                    $params = array(
                        'name' => $this->input->post('name'),
                        'url' => $this->input->post('url'),
                        'section' => $this->input->post('section'),
                        'start' => $this->input->post('start'),
                        'unit' => $unit,
                        'type' => $this->input->post('type'),
                        'publish' => $this->input->post('publish'),
                        'start_date' => $unixStartDate,
                        'end_date' => $unixEndDate,
                        'upload' => false
                    );			
                    if(isset($image)) {
                            $params['image'] = $image;
                            $params['code'] = '';
                            $params['upload'] = true;
                    }

                    if(isset($code)) {
                        $params['code'] = $code;
                        $params['image'] = '';
                    }

                    $this->ad->edit($id, $params);
                    $data['success'] = 'Chỉnh sửa quảng cáo thành công';
        $this->load->library('Cache', 'cache');
        $this->cache->delete($this->storeCode.'index');
            }

            $ad = $this->ad->getInfo($id);

            if(!$ad) redirect('admin/ad');

            $data['ad'] = $ad;

            $data['unitHeader'] = $this->ad->unitUsed('header');
            $data['unitFooter'] = $this->ad->unitUsed('footer');
            $data['checkHeader1'] = $this->ad->checkHeader1();
            $this->load->view('admin/header');
            $this->load->view('admin/advertiseedit', $data);
            $this->load->view('admin/footer');		
    }

    function delete($id)
    {
            $this->ad->delete($id);
            redirect('admin/ad');		
    }
    function publishID(){
        $id = $this->input->post("ad_id");
        $value = $this->input->post("value");
        if($value == 1){
            $this->ad->unpublish($id);
            echo "<a href='javascript:;' onclick='publishID(".$id.",0);'> No </a>";
        } else {
            $this->ad->publish($id);
            echo "<a href='javascript:;' onclick='publishID(".$id.",1);'> Yes </a>";
        }
    }
    
    function all($sort = 'advertise_id', $order = 'DESC', $advertise_id = '0', $starttime = '0', $endtime = '0') {
        $filter = array(
            'sort' => $sort,
            'order' => $order,
            'advertise_id' => $advertise_id,
            'starttime' => $starttime,
            'endtime' => $endtime
        );
        
        if($starttime && $endtime) {
            $s = strtotime($starttime);
            $e = strtotime($endtime);
            $s1 = date('Y-m-d', $s);
            $e1 = date('Y-m-d', $e);
            $dateRange = $this->createDateRangeArray($s1, $e1);
            $tmp = '('.implode(',', $dateRange).')';
            if($advertise_id) {
                $ads = $this->ad->dateRange($advertise_id, $tmp);
                $data['dates'] = $ads;
            }
        }
        
        $data['ads'] = $this->ad->adByDate($filter);
        $data['starttime'] = $starttime;
        $data['endtime'] = $endtime;
        $data['advertise_id'] = $advertise_id;
        $data['firstUrl'] = site_url('admin/ad/all/advertise_id/DESC');
        $this->load->view('admin/header');
        $this->load->view('admin/advertisebydate', $data);
        $this->load->view('admin/footer');
    }
    
    function createDateRangeArray($strDateFrom,$strDateTo) {
      // takes two dates formatted as YYYY-MM-DD and creates an
      // inclusive array of the dates between the from and to dates.

      // could test validity of dates here but I'm already doing
      // that in the main script

      $aryRange=array();

      $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
      $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

      if ($iDateTo>=$iDateFrom) {
        array_push($aryRange,date('Ymd',$iDateFrom)); // first entry

        while ($iDateFrom<$iDateTo) {
          $iDateFrom+=86400; // add 24 hours
          array_push($aryRange,date('Ymd',$iDateFrom));
        }
      }
      return $aryRange;
    }
}
