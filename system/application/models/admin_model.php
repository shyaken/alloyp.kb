<?php
class Admin_model extends Model
{
    function Admin_model()
    {
        parent::__construct();
    }
    
    /*
     * đăng nhập admin
     */
    function login($username, $password) 
    {
        $this->db->where('username', $username);
        $query = $this->db->get('admin');
        if($query->num_rows() > 0) {
            $user = $query->row();
            if(!$user->is_active) {
                return array('status' => false, 'msg' => 'Tên đăng nhập đã bị khóa');
            } else {
                $passwd = md5($password . $user->salt);
                if($passwd != $user->password) {
                    return array('status' => false, 'msg' => 'Sai mật khẩu');
                } else {
                    // save ip, time
                    $this->db->where('id', $user->id);
                    $params = array('last_login'=>microtime(true), 'last_ip'=>$this->input->ip_address());
                    $this->db->update('admin', $params);
                    
                    // set session
                    $is_root = 'no';
                    if($user->is_root == 1) $is_root = 'yes';
                    
                    $group = $this->getGroupInfo($user->group_id);
                    
                    $data = array(
                    	'admin_id' => $user->id,
                        'adminname' => $username,
                        'is_admin' => 'yes',
                        'is_root' => $is_root,
                    	'permission' => $group->permission,
                        'base_url' => base_url()
                    );
                    $this->session->set_userdata($data);
                    return array('status' => true, 'msg' => 'Đăng nhập thành công');
                }
            }
            $password_hash = md5($password . $user->salt);
        } else {
            return array('status' => false, 'msg' => 'Sai tên đăng nhập');
        }
    }
    
    /*
     * đổi mật khẩu cho admin
     */
    function changePassword($adminId, $curPass, $newPass) {
        $admin = $this->getInfo($adminId);
        if($admin) {
            if(md5($curPass . $admin->salt) == $admin->password) {
                $hashPass = md5($newPass . $admin->salt);
                $dataUpdate = array('password' => $hashPass);
                $this->edit($adminId, $dataUpdate);
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
     /*
     * tạo mới admin 
     */
    function createAdmin($data) 
    {
        $this->db->where('username', $data['username']);
        $user = $this->db->get('admin');
        if($user->num_rows() > 0) {
            return false;
        } else {
            $user->free_result();
            $this->db->insert('admin', $data);
            return true;
        }
    }    
    
    /*
     * list admin
     */
    function listAll()
    {
        $query = $this->db->get('admin');
        return $query->result();
    }
    
    /*
     * is exists
     */
    function isExists($id)
    {
        
    }
    
    /*
     * get info
     */
    function getInfo($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('admin');
        if($query->num_rows() > 0) {
            return $query->row();
        } else return false;
                
    }
    
    /*
     * edit
     */
    function edit($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('admin', $data);
    }
    
    /*
     * delete
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('admin');
    }
    
    
    //==========================================================================================
    
    /*
     * list admin group
     */
    function listGroup() {
    	$query = $this->db->get('admingroup');
    	return $query->result();
    }
    
    /*
     * add admin group
     */
    function addGroup($data) {
    	$this->db->insert('admingroup', $data);
    	return $this->db->insert_id();
    }

    /*
     * update group
     */
    function updateGroup($id, $data) {
    	$this->db->where('group_id', $id);
    	$this->db->update('admingroup', $data);
    }
    
    /*
     * delete group
     */
    function deleteGroup($id) {
    	$this->db->where('group_id', $id);
    	$this->db->delete('admingroup');
    }
    
    /*
     * isExistsGroup
     */
    function isExistsGroup($permission) {
    	$this->db->where('permission', $permission);
    	$query = $this->db->get('admingroup');
    	if($query->num_rows() > 0)
    		return $query->row()->group_id;
    	else return false;	
    }
    
    /*
     * group info
     */
    function getGroupInfo($id) {
    	$this->db->where('group_id', $id);
    	$query = $this->db->get('admingroup');
    	return $query->row();
    }
    
}
?>
