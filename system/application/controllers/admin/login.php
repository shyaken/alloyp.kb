<?php
class Login extends Controller
{
    function Login()
    {
        parent::__construct();
        $this->load->model('admin_model', 'admin');
    }
    
    function index() 
    {
        if($this->session->userdata('is_admin') == 'yes') {
            redirect('admin/dashboard');
        }
    	
        $data = array();
        
        if(isset($_POST['submit'])) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $result = $this->admin->login($username, $password);
            if($result['status']) {
                redirect('admin/dashboard');
            } else {
                $data['error'] = $result['msg'];
            }
        }
        
        $this->load->view('admin/login', $data);
    }
    
    function logout()
    {
        $data = array(
        	'admin_id' => '',
            'adminname' => '',
            'is_admin' => '',
            'is_root' => '',
        	'permissions' => '',
            'base_url' => '',
        );
        $this->session->set_userdata($data);
        redirect('admin/login');
    }
}
?>
