<?php
class Giftcode_model extends Model {
    private $dbuser;
    function Giftcode_model() {
        parent::__construct();
        $this->dbuser = $this->load->database('dbuser', TRUE);
    }
    
    function add($data) {
        $this->dbuser->insert('event_giftcode', $data);
    }
    
    function totalFilterGiftcode($filter)
	{
		$sql = "SELECT count(*) AS total FROM event_giftcode as a";
		
		$where = array();
		
		if(isset($filter['code']) && $filter['code'] != '0') {
			$where[] = "a.code LIKE '%" . $filter['code'] . "%'";
		}
		if(isset($filter['type']) && $filter['type'] != '0') {
			$where[] = "a.type='" . $filter['type'] . "'";
		}
        if(isset($filter['sender']) && $filter['sender'] != '-1') {
			$where[] = "a.sender=" . $filter['sender'];
		}
        if(isset($filter['status']) && $filter['status'] != '-1') {
			$where[] = "a.status=" . $filter['status'];
		}
		if(isset($filter['create']) && $filter['create'] != '0') {
            $start = strtotime($filter['create'] . ' 00:00:01');
            $end = strtotime($filter['create'] . ' 23:59:59');
			$where[] = "a.create_date >= $start AND a.create_date <= $end";
		}
        if(isset($filter['use']) && $filter['use'] != '0') {
            $start = strtotime($filter['use'] . ' 00:00:01');
            $end = strtotime($filter['use'] . ' 23:59:59');
			$where[] = "a.use_date >= $start AND a.use_date <= $end";
		}
        if(isset($filter['expire']) && $filter['expire'] != '0') {
            $start = strtotime($filter['expire'] . ' 00:00:01');
            $end = strtotime($filter['expire'] . ' 23:59:59');
			$where[] = "a.expire_date >= $start AND a.expire_date <= $end";
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
	function allFilterGiftcode($filter) {
		$sql = "SELECT * FROM event_giftcode as a";
		
		$where = array();
		
		if(isset($filter['code']) && $filter['code'] != '0') {
			$where[] = "a.code LIKE '%" . $filter['code'] . "%'";
		}
		if(isset($filter['type']) && $filter['type'] != '0') {
			$where[] = "a.type='" . $filter['type'] . "'";
		}
        if(isset($filter['sender']) && $filter['sender'] != '-1') {
			$where[] = "a.sender=" . $filter['sender'];
		}
        if(isset($filter['status']) && $filter['status'] != '-1') {
			$where[] = "a.status=" . $filter['status'];
		}
		if(isset($filter['create']) && $filter['create'] != '0') {
            $start = strtotime($filter['create'] . ' 00:00:01');
            $end = strtotime($filter['create'] . ' 23:59:59');
			$where[] = "a.create_date >= $start AND a.create_date <= $end";
		}
        if(isset($filter['use']) && $filter['use'] != '0') {
            $start = strtotime($filter['use'] . ' 00:00:01');
            $end = strtotime($filter['use'] . ' 23:59:59');
			$where[] = "a.use_date >= $start AND a.use_date <= $end";
		}
        if(isset($filter['expire']) && $filter['expire'] != '0') {
            $start = strtotime($filter['expire'] . ' 00:00:01');
            $end = strtotime($filter['expire'] . ' 23:59:59');
			$where[] = "a.expire_date >= $start AND a.expire_date <= $end";
		}
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'id',
            'status',
			'create_date',
            'use_date',
     		'expire_date'
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
    
    function generateCode($userId) {
        if(!$userId || !is_numeric($userId)) {
            return false;
        }
        $len = strlen($userId);
        $now = time();
        $rand = $now % 3;
        if($len >= 3) $remainLen = 12-$rand-$len;
        else $remainLen = 10-$rand-$len;
        $remainNum = substr($now,-$remainLen);
        $giftcode = "$userId"."$remainNum";
        return $giftcode;
    }
    
    function getInfo($code) {
        $this->dbuser->where('code', $code);
        $query = $this->dbuser->get('event_giftcode');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function update($id, $data) {
        $this->dbuser->where('id', $id);
        $this->dbuser->update('event_giftcode', $data);
    }
}
?>
