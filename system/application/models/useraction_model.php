<?php
class Useraction_model extends Model
{
	function Useraction_model() {
		parent::__construct();
	}
	
	/*
	 * list action
	 */
	function listAction()
	{
		$query = $this->db->get('action_reward');
		return $query->result();
	}
	
	/*
	 * action info
	 */
	function getAction($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('action_reward');
		if($query->num_rows() > 0)
			return $query->row();
		else 	
			return false;	
	}
    
    function getActionByName($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('action_reward');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
	/*
	 * update action
	 */
	function updateAction($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('action_reward', $data);
	}

	/*
	 * total filter log
	 */
	function totalFilterLog($filter)
	{
		$sql = "SELECT count(*) AS total FROM action_log as a";
		
		$where = array();
		
		if($filter['startdate'] != '0') {
			$startdate = $filter['startdate'];
			$enddate = $filter['enddate'];
			$where[] = 'time >= ' . $startdate . ' AND time <= ' . $enddate;
		}
		
		if(isset($filter['userid']) && $filter['userid'] != '-1') {
			$where[] = "a.userid LIKE '%" . $filter['userid'] . "%'";
		}
		if(isset($filter['actionid']) && $filter['actionid'] != '-1') {
			$where[] = "a.actionid LIKE '%" . $filter['actionid'] . "%'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->db->query($sql);
		return $query->row()->total;
	}
	
	/*
	 * list filter logs
	 */
	function allFilterLog($filter) {
		$sql = "SELECT * FROM action_log as a";

		$where = array();
		
		if($filter['startdate'] != '0') {
			$startdate = $filter['startdate'];
			$enddate = $filter['enddate'];
			$where[] = 'time >= ' . $startdate . ' AND time <= ' . $enddate;
		}
		
		if(isset($filter['userid']) && $filter['userid'] != '-1') {
			$where[] = "a.userid LIKE '%" . $filter['userid'] . "%'";
		}
		if(isset($filter['actionid']) && $filter['actionid'] != '-1') {
			$where[] = "a.actionid LIKE '%" . $filter['actionid'] . "%'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'id',
			'userid',
     		'actionid',
     		't1',
     		't2',
			't3',
			't4'
     	);
     	if(isset($filter['sortby']) && in_array($filter['sortby'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sortby'];
     	} else {
     		$sql .= " ORDER BY a.id";
     	}
     	if(isset($filter['order']) && $filter['order'] == 'ASC') {
     		$sql .= " ASC";
     	} else {
     		$sql .= " DESC";
     	}
     	if(isset($filter['page']) || isset($filter['limit'])) {
     		if($filter['start'] < 0) {
     			$filter['start'] = 0;
     		}
     		if($filter['limit'] < 1) {
     			$filter['limit'] = 50;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
     	$query = $this->db->query($sql);
     	return $query->result();		
	}
	
	function addLog($data) {
		$this->db->insert('action_log', $data);
	}
    
    function isExistsLog($where) {
        $this->db->where($where);
        $query = $this->db->get('action_log');
        if($query->num_rows()>0) {
            
        }
    }
	
}