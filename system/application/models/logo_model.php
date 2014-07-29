<?php
class Logo_model extends Model {
    function Logo_model() {
        parent::__construct();
    }
    
    function add($data) {
        $this->db->insert('logo', $data);
        return $this->db->insert_id();
    }
    
    function getInfo($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('logo');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('logo', $data);
    }
    
    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('logo');
    }
    
    function setDefault($id) {
        $this->db->set('default', 0);
        $this->db->update('logo');
        $this->db->set('default', 1);
        $this->db->where('id', $id);
        $this->db->update('logo');
    }
    
    function getAll() {
        $query = $this->db->get('logo');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function getDefault() {
        $this->db->where('default', 1);
        $query = $this->db->get('logo');
        return $query->first_row();
    }
}
?>
