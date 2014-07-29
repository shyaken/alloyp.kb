<?php
class Setting_model extends Model {

	function Setting_model() {
		parent::__construct();
	}

	function getValueByKey($key) {
		$this->db->where('key', $key);
		$query = $this->db->get('setting');
		if($query->num_rows() > 0) {
			return $query->row()->value;
		} else return false;
	}

	function updateValueByKey($key, $value) {
		$this->db->where('key', $key);
		$this->db->set('value', $value);
		$this->db->update('setting');
		return true;
	}
	
	function getInfo($setting_id) {
		$this->db->where('setting_id', $setting_id);
		$query = $this->db->get('setting');
		return $query->row();
		
	}
	
	function update($setting_id, $data) {
		$this->db->where('setting_id', $setting_id);
		$this->db->update('setting', $data);
	}
	
	function getKey($group = 'rate') {
		$this->db->where('group', $group);
		$query = $this->db->get('setting');
		return $query->result();
	}
	
	/*
	 * save setting log
	 */
	function saveSettingLog($data) {
		$this->db->insert('setting_log', $data);
	}
	
	/*
	 * get setting log
	 */
	function getSettingLog($limit, $start) {
		$this->db->order_by('time', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get('setting_log');
		return $query->result();
	}
    
    /*                               Global Setting                      */
    function globalSetting($key) {
        $dbuser = $this->load->database('dbuser', TRUE);
        $dbuser->where('key', $key);
        $query = $dbuser->get('setting');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function globalSettingUpdate($key, $value) {
        $dbuser = $this->load->database('dbuser', TRUE);
        $dbuser->where('key', $key);
        $dbuser->set('value', $value);
        $dbuser->update('setting');
    }
}