<?php
class Exchange_model extends Model {
    private $dbuser;
    function Exchange_model() {
        parent::__construct();
        $this->dbuser = $this->load->database('dbuser', TRUE);
    }
    
    function totalFilterLog($filter)
	{
		$sql = "SELECT count(*) AS total FROM exchange_log as a";
		
		$where = array();
		
		if($filter['startdate'] != '0') {
			$startdate = $filter['startdate'];
			$enddate = $filter['enddate'];
			$where[] = 'time >= ' . $startdate . ' AND time <= ' . $enddate;
		}
		
		if(isset($filter['user_id']) && $filter['user_id'] != '-1') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}
		if(isset($filter['username']) && $filter['username'] != '-1') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
        if(isset($filter['tx_type']) && $filter['tx_type'] != '-1') {
			$where[] = "a.tx_type LIKE '%" . $filter['tx_type'] . "%'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->dbuser->query($sql);
		return $query->row()->total;
	}
	
	/*
	 * list filter logs
	 */
	function allFilterLog($filter) {
		$sql = "SELECT * FROM exchange_log as a";

		$where = array();
		
		if($filter['startdate'] != '0') {
			$startdate = $filter['startdate'];
			$enddate = $filter['enddate'];
			$where[] = 'time >= ' . $startdate . ' AND time <= ' . $enddate;
		}
		
		if(isset($filter['user_id']) && $filter['user_id'] != '-1') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}
		if(isset($filter['username']) && $filter['username'] != '-1') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
        if(isset($filter['tx_type']) && $filter['tx_type'] != '-1') {
			$where[] = "a.tx_type LIKE '%" . $filter['tx_type'] . "%'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'id',
			'user_id',
     		'username',
     		'tx_type'
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
     	$query = $this->dbuser->query($sql);
     	return $query->result();		
	}
    
    function logDetail($id) {
        $this->dbuser->where('id', $id);
        $query = $this->dbuser->get('exchange_log');
        return $query->row();
    }
}
?>
