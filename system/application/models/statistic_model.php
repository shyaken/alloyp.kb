<?php
class Statistic_model extends Model
{
	function Statistic_model()
	{
		parent::__construct();
	}
	
	/*
	 * tổng số download
	 */
	function totalDownload($filter = array())
	{
		$sql = "SELECT count(*) AS total FROM downloads as a";
		$where = array();
        if($filter['vendor'] && $filter['vendor'] != '0') {
            $sql .= " JOIN apps ON a.app_id=apps.app_id";
            $where[] = "apps.vendor='" . $filter['vendor'] . "'";
        }
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id=" . $filter['app_id'];
		}
		if(isset($filter['tym_type']) && $filter['tym_type'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym_type'] . "'";
		}
        if(isset($filter['price']) && $filter['price'] != '0') {
			$prices = explode('-', $filter['price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.tym_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.tym_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.tym_price <= " . $prices[1];
		}        
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "time >= " . $start . " AND time <= " . $end;
		}
		
		if($where) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}
		
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;	
	}
	
	/*
	 * tổng số tiền 
	 */
	function totalMoney($filter = array())
	{
		$sql = "SELECT SUM(a.tym_price) AS total FROM downloads as a";
		$where = array();
        if($filter['vendor'] && $filter['vendor'] != '0') {
            $sql .= " JOIN apps ON a.app_id=apps.app_id";
            $where[] = "apps.vendor='" . $filter['vendor'] . "'";
        }
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id=" . $filter['app_id'];
		}
		if(isset($filter['tym_type']) && $filter['tym_type'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym_type'] . "'";
		}
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "time >= " . $start . " AND time <= " . $end;
		}
		
		if($where) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}
		
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;	
	}	
	
	/*
	 * get all download
	 */
	function getAllDownload($filter = array(), $limit = '10', $start = '0')
	{
        //SELECT apps.vendor, downloads.* FROM apps JOIN downloads ON apps.app_id=downloads.app_id WHERE `vendor`='Nguyễn Ngọc Ngạn' LIMIT 30
		$sql = "SELECT * FROM downloads as a";
		$where = array();
        if($filter['vendor'] && $filter['vendor'] != '0') {
            $sql .= " JOIN apps ON a.app_id=apps.app_id";
            $where[] = "apps.vendor='" . $filter['vendor'] . "'";
        }
        if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id=" . $filter['app_id'];
		}
		if(isset($filter['tym_type']) && $filter['tym_type'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym_type'] . "'";
		}
        if(isset($filter['price']) && $filter['price'] != '0') {
			$prices = explode('-', $filter['price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.tym_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.tym_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.tym_price <= " . $prices[1];
		}        
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "time >= " . $start . " AND time <= " . $end;
		}
		
		if($where) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}

        $sort_array =  array(
			'id',
            'app_id',
			'time',
			'tym_price',
			'tym_type'
     	);
     	if(isset($filter['sort']) && in_array($filter['sort'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sort'];
     	} else {
     		$sql .= " ORDER BY a.id";
     	}
        
     	
     	if(isset($filter['order']) && $filter['order'] == 'ASC') {
     		$sql .= " ASC";
     	} else {
     		$sql .= " DESC";
     	}
     	
     	if(isset($filter['start']) || isset($filter['limit'])) {
     		if($filter['start'] < 0) {
     			$filter['start'] = 0;
     		}
     		if($filter['limit'] < 1) {
     			$filter['limit'] = 20;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
        
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	/*
	 * export to excel
	 */
	function exportEXCEL()
	{
		$this->db->select('*');
		$query = $this->db->get('downloads');
		return $query->result();
	}
	
	/*
	 * export to csv
	 */
	function exportCSV($filter = array())
	{
		$this->load->dbutil();
		
		if($filter['start'] != '0') {
			$where = "time <= " . $filter['end'] . " AND time >= " . $filter['start'];
			$this->db->where($where);
		}
		
		$query = $this->db->get('downloads');
		echo $this->dbutil->csv_from_result($query);		
	}
	
}
