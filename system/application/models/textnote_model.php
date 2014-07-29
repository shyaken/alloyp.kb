<?php
class Textnote_model extends Model {
    private $dbuser;
    function Textnote_model() {
        parent::__construct();
        $this->dbuser = $this->load->database('dbuser', TRUE);
    }
    
    function add($data) {
        $this->dbuser->insert('textnote', $data);
        return $this->dbuser->insert_id();
    }
    
    function update($id, $data) {
        $this->dbuser->where('id', $id);
        $this->dbuser->update('textnote', $data);
    }
    
    function getInfo($id) {
        $this->dbuser->where('id', $id);
        $query = $this->dbuser->get('textnote');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function getInfoByKey($key) {
        $this->dbuser->where('key', $key);
        $query = $this->dbuser->get('textnote');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function getAll() {
        $this->dbuser->order_by('group', 'desc');
        $query = $this->dbuser->get('textnote');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
?>
