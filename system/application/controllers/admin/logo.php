<?php 
class Logo extends Controller {
    function Logo() {
        parent::__construct();
        $this->load->model('logo_model');
        
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
        $this->load->view('admin/header');
        $data['logos'] = $this->logo_model->getAll();
        $this->load->view('admin/logo', $data);
        $this->load->view('admin/footer');
    }
    
    function add() {
        $data = array();
        if(isset($_POST['insert'])) {
            $name = $this->input->post('name');
            $default = $this->input->post('default');
            
            $path = 'images/logos/';
            $config['upload_path'] = './' . $path;
            $config['allowed_types'] = "jpg|png|gif";
            $this->load->library('upload', $config);

            if(!$this->upload->do_upload('image')) {
                $data['error'] = 'Upload logo không thành công';
            } else {
                $upload = $this->upload->data();
                $image = $path . $upload['file_name'];
                $logo = array(
                    'name' => $name,
                    'image' => $image,
                    'default' => $default
                );
                $id = $this->logo_model->add($logo);
                $this->logo_model->setDefault($id);
                $data['success'] = 'Upload logo thành công và đã thiết lập mặc định';
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/logoadd', $data);
        $this->load->view('admin/footer');
    }
    
    function edit($id) {
        $data = array();
        if(isset($_POST['update'])) {
            $name = $this->input->post('name');
            $default = $this->input->post('default');
            
            $path = 'images/logos/';
            $config['upload_path'] = './' . $path;
            $config['allowed_types'] = "jpg|png|gif|jpeg";
            $this->load->library('upload', $config);

            if(!$this->upload->do_upload('image')) {
                $data['error'] = 'Upload logo không thành công';
            } else {
                //xóa logo cũ
                $logo = $this->logo_model->getInfo($id);
                if(file_exists('./'.$logo->image)) {
                    unlink('./'.$logo->image);
                }
                $upload = $this->upload->data();
                $image = $path . $upload['file_name'];
                $logo = array(
                    'name' => $name,
                    'image' => $image,
                    'default' => $default
                );
                $this->logo_model->update($id, $logo);

                $data['success'] = 'Chỉnh sửa logo thành công';
            }
        }
        $data['logo'] = $this->logo_model->getInfo($id);
        if(!$data['logo']) redirect('admin/logo');
        $this->load->view('admin/header');
        $this->load->view('admin/logoedit', $data);
        $this->load->view('admin/footer');
    }
    
    function setDefault($id) {
        $logo = $this->logo_model->getInfo($id);
        if(!is_numeric($id) || !$logo) redirect('admin/logo');
        $this->logo_model->setDefault($id);
        echo "logo id=$id is now default :D";
        echo "<script>setTimeout('window.location.href=\"" . site_url('admin/logo') ."\"', 1500);</script>";
        
    }
    
    function delete() {
        $id = $this->input->post('id');
        $logo = $this->logo_model->getInfo($id);
        if($logo->default) {
            echo "1";
            exit;
        } else {
            $this->logo_model->delete($id);
            echo "0";
            exit;
        }
    }
    
}

