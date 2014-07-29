<?php
class Transaction_model extends Model
{
	private $dbuser;
	
	function Transaction_model()
	{
		parent::__construct();
		$this->dbuser = $this->load->database('dbuser', TRUE);
	}
	
	/*
	 * tổng số giao dịch theo bộ lọc
	 */
	function totalTransaction($filter)
	{
		$sql = "SELECT count(*) AS total FROM transaction as a";
		
		$where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
		if(isset($filter['method']) && $filter['method'] != '0') {
			$where[] = "a.method LIKE '%" . $filter['method'] . "%'";
		}
        if(isset($filter['user_input']) && $filter['user_input'] != '-1') {
            $where[] = "a.user_input LIKE '%" . $filter['user_input'] . "%'";
        }  
        if(isset($filter['sms_provider']) && $filter['sms_provider'] != '-1') {
            $where[] = "substring(a.sms_provider,2,1)='" . $filter['sms_provider'] . "'";
        }        
        if(isset($filter['card_value']) && $filter['card_value'] != '0') {
            $where[] = "a.card_value='" . $filter['card_value'] . "'";
        }          
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
        
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;		
	}
	
	/*
	 * tổng số giao dịch theo bộ lọc
	 */
	function totalTransactionBySMS($filter)
	{
		$sql = "SELECT count(*) AS total FROM transaction as a";
		
		$where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
            
        $where[] = "a.method LIKE '%sms%'";
        
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}	
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;		
	}	
	
/*
	 * tổng số giao dịch theo bộ lọc
	 */
	function totalTransactionByCARD($filter)
	{
		$sql = "SELECT count(*) AS total FROM transaction as a";
        
        $where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
		
        $where[] = "a.method LIKE '%card%'";
        
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}

        if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;		
	}		
	
	/*
	 * list giao dịch theo bộ lọc
	 * $filter = array('username'=>'value', 'payit_id'=>'value', ...)
	 */
	function allTransaction($filter)
	{
		$sql = "SELECT * FROM transaction as a";
		
		$where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
		if(isset($filter['method']) && $filter['method'] != '0') {
			$where[] = "a.method LIKE '%" . $filter['method'] . "%'";
		}
        if(isset($filter['user_input']) && $filter['user_input'] != '-1') {
            $where[] = "a.user_input LIKE '%" . $filter['user_input'] . "%'";
        }
        if(isset($filter['sms_provider']) && $filter['sms_provider'] != '-1') {
            $where[] = "substring(a.sms_provider,2,1)='" . $filter['sms_provider'] . "'";
        }
        if(isset($filter['card_value']) && $filter['card_value'] != '0') {
            $where[] = "a.card_value='" . $filter['card_value'] . "'";
        }        
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
        
        $sort_array =  array(
			'id',
            'username',
			'method',
			'user_input',
			'status'
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
    	
     	$query = $this->dbuser->query($sql);
		if($query->num_rows() > 0) return $query->result();
		else return false;		
	}	
	
	/*
	 * lưu log 
	 */
	function log($data)
	{
		$this->dbuser->insert('transaction', $data);
	}
	
	/*
	 * gọi từ webservice.php
	 */
	function checkValidInfo($payitkey, $payitsign, $data)
	{
		$data = implode("", $data);
		if(md5(KEYBYPAYIT . $data . SECRETBYPAYIT) == $payitsign)
			return true;
		else 
			return false;	
	}	
	
	/*
	 * tổng số tym đỏ đã được nạp
	 */
	function totalTym($filter) {
		$sql = "SELECT sum(t1) as total FROM transaction as a";
		
		$where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
		if(isset($filter['method']) && $filter['method'] != '0') {
			$where[] = "a.method LIKE '%" . $filter['method'] . "%'";
		}
        if(isset($filter['user_input']) && $filter['user_input'] != '-1') {
            $where[] = "a.user_input LIKE '%" . $filter['user_input'] . "%'";
        }
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;
	}
	
	/*
	 * tổng số tym đỏ đã được nạp bằng SMS
	 */
	function totalTymBySMS($filter) {
		$sql = "SELECT sum(t1) as total FROM transaction as a";
		
		$where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}

        $where[] = "a.method LIKE '%sms%'";
        
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}
				
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
	
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;
	}
	
	/*
	 * tổng số tym đỏ đã được nạp bằng SMS
	 */
	function totalTymByCARD($filter) {
		$sql = "SELECT sum(t1) as total FROM transaction as a";
		
		$where = array();
		
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}

        $where[] = "a.method LIKE '%card%'";
        
        if(isset($filter['status']) && $filter['status'] != '0') {
			$where[] = "a.status LIKE '%" . $filter['status'] . "%'";
		}
		
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}		
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;
	}
	
	/*
	 * get transaction
	 */
	function getTransaction($id) {
		$this->dbuser->where('id', $id);
		$query = $this->dbuser->get('transaction');
		return $query->row();
	}
    
    /*
     * số tym còn lại
     */
    function remainTym($tym_type = 't1') {
        $sql = "SELECT SUM($tym_type) as total FROM user";
        $query = $this->dbuser->query($sql);
        if($query->num_rows()>0) {
            return $query->row()->total;
        } else {
            return 0;
        }
    }
}