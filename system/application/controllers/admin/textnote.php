<?php
class Textnote extends Controller {
    function Textnote() {
        parent::__construct();
        $this->load->model('textnote_model');
        if($this->session->userdata('is_admin') != 'yes') {
            redirect('admin/login');
        }
        if($this->session->userdata('is_root') != 'yes') {
            redirect('admin/dashboard');
        }        
    }
    
    function index() {
        $this->load->view('admin/header');
        
        $data['textnotes'] = $this->textnote_model->getAll();
        $this->load->view('admin/textnote', $data);
        
        $this->load->view('admin/footer');
    }
    
    function edit($id, $ckeditor = '4') {
        $this->load->view('admin/header');
        
        if(isset($_POST['update'])) {
            $dataUpdate = array(
                'value' => $this->input->post('value'),
                'comment' => $this->input->post('comment')
            );
            $this->textnote_model->update($id, $dataUpdate);
            $data['success'] = 'Cập nhật thành công';
        }
        
        $data['ckeditor'] = $ckeditor;
        $data['textnote'] = $this->textnote_model->getInfo($id);
        if($ckeditor) {
            //ckeditor
            $this->load->library('ckeditor');
            $data['editor'] = new CKEditor(base_url() . 'js/ckeditor/');
            $this->load->view('admin/textnoteckeditor', $data);
        } else {
            $this->load->view('admin/textnoteedit', $data);            
        }
        $this->load->view('admin/footer');
    }
}
?>
