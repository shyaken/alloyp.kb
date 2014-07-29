<?php
class User_model extends Model
{
	private $dbuser;
	
	function User_model() {
		parent::__construct();
		$this->dbuser = $this->load->database('dbuser', TRUE);
	}

	/*
	 * add user
	 */
	function add($data) {
		$query = $this->dbuser->insert('user', $data);
		return $this->dbuser->insert_id();
	}
	
	/*
	 * generate random active code
	 */
	function randomCode($len = 32) {
		$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max=strlen($base)-1;
		$activatecode='';
		mt_srand((double)microtime()*1000000);
		while (strlen($activatecode)<$len+1)
		  $activatecode.=$base{mt_rand(0,$max)};
		  
		return $activatecode;
	}
	
	/*
	 * update user
	 */
	function update($id, $data)
	{
		$this->dbuser->where('user_id', $id);
		$this->dbuser->update('user', $data);
	}
	
	/*
	 * delete user
	 */
	function delete($id)
	{
		$this->dbuser->where('user_id', $id);
		$this->dbuser->delete('user');
	}
	
	/*
	 * get user by id
	 */
    function getUserById($userid) {
    	if(is_numeric($userid)) $this->dbuser->where('user_id', $userid);
        else $this->dbuser->where('username', $userid);
        $user = $this->dbuser->get('user');
        if ($user->num_rows()>0) {
            return $user->row();
        } else return false;
    }
    
    function getUserByUsername($username) {
        $this->dbuser->where('username', $username);
        $user = $this->dbuser->get('user');
        if ($user->num_rows()>0) {
            return $user->row();
        } else return false;
    }
    
	/*
	 * get active user by id
	 */
    function getActiveUserById($userid) {
    	$this->dbuser->where('active_by !=', 'inactive');
    	if(is_numeric($userid)) $this->dbuser->where('user_id', $userid);
        else $this->dbuser->where('username', $userid);
        $user = $this->dbuser->get('user');
        if ($user->num_rows()>0) {
            return $user->row();
        } else return false;
    }
    
    function getActiveUserByUsername($username) {
    	$this->dbuser->where('active_by !=', 'inactive');
        $this->dbuser->where('username', $username);
        $user = $this->dbuser->get('user');
        if ($user->num_rows()>0) {
            return $user->row();
        } else return false;
    }   
    
    /*
     * get user by field
     * $where = array('field' => 'value');
     */
    function getUserByField($where) {
    	$this->dbuser->where($where);
    	$query = $this->dbuser->get('user');
        if($query->num_rows() > 0)
            return $query->row();
        else return false;
    }
    
    /*
     * kiểm tra userid hoặc username tồn tại chưa
     */
    function isExists($data)
    {
    	if(is_numeric($data)) $this->dbuser->where('user_id', $data);
    	else $this->dbuser->where('username', $data);    	
    	$query = $this->dbuser->get('user');
    	if($query->num_rows() > 0)
    		return true;
    	else return false;	
    }
    
    /*
     * kiểm tra field tồn tại chưa
     */
    function isExistsField($data) {
    	foreach($data as $key =>& $value)
    		$value = $this->db->escape_str($value);
    	$this->dbuser->where($data);
    	$query = $this->dbuser->get('user');
    	if($query->num_rows() > 0)
    		return $query->row()->user_id;
    	else return false;	
    }
    
   
    /*
     * cộng apcoin vào tài khoản cho người dùng
     * gọi từ appstore.vn/webservice
     */
    function countAp($username, $apcoin)
    {
    	$this->dbuser->where('username', $username);
    	$query = $this->dbuser->get('user');
    	$user = $query->row();
    	
    	$curAp = $user->ap;
    	$curAp += $apcoin;
    	
    	$query->free_result();
    	$this->dbuser->set('ap', $curAp);
    	$this->dbuser->where('username', $username);
    	$this->dbuser->update('user');
    }
    
    function checkPhone($phone) {
        $this->dbuser->where('phone', $phone);
        $this->dbuser->order_by('user_id', 'DESC');
        $query = $this->dbuser->get('user');
        if($query->num_rows()>0) {
            return $query->first_row();
        } else {
            return false;
        }
    }
    
    /*
     * tổng số users theo filter
     */
    function totalFilterUser($filter)
    {
        $sql = "SELECT COUNT(*) AS total FROM user as a ";
        
        $where = array();
        
        if(isset($filter['username']) && $filter['username'] != '-1') {
            $where[] = "a.username LIKE '%" . $filter['username'] . "%'";
        }
        if(isset($filter['email']) && $filter['email'] != '-1') {
            $where[] = "a.email LIKE '%" . $filter['email'] . "%'";
        }
        if(isset($filter['phone']) && $filter['phone'] != '-1') {
            $where[] = "a.phone LIKE '%" . $filter['phone'] . "%'";
        }
   	 	if(isset($filter['use_package']) && $filter['use_package'] != '-1') {
        	$now = microtime(true);
        	if($filter['use_package'] == '0') {
        		$where[] = "a.package_expired='0' OR a.package_expired<$now";
        	} else {
        		$where[] = "a.package_expired>=$now";
        	}
        }
        if(isset($filter['active_by']) && $filter['active_by'] != '0') {
            if($filter['active_by'] == 'smsemail') {
                $where[] = "a.active_by like '%sms%' OR a.active_by like '%email%'";
            } else {
                $where[] = "a.active_by LIKE '%" . $filter['active_by'] . "%'"; 
            }
        }
        
        if($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0)
            return $query->row()->total;
        else return 0;
        
    }
    
    /*
     * all users theo filter
     */
    function allFilterUser($filter)
    {
        $sql = "SELECT * FROM user as a ";
        
        $where = array();
        
        if(isset($filter['username']) && $filter['username'] != '-1') {
            $where[] = "a.username LIKE '%" . $filter['username'] . "%'";
        }
        if(isset($filter['email']) && $filter['email'] != '-1') {
            $where[] = "a.email LIKE '%" . $filter['email'] . "%'";
        }
        if(isset($filter['phone']) && $filter['phone'] != '-1') {
            $where[] = "a.phone LIKE '%" . $filter['phone'] . "%'";
        }
        if(isset($filter['use_package']) && $filter['use_package'] != '-1') {
        	$now = microtime(true);
        	if($filter['use_package'] == '0') {
        		$where[] = "a.package_expired='0' OR a.package_expired<$now";
        	} else {
        		$where[] = "a.package_expired>=$now";
        	}
        }
        if(isset($filter['active_by']) && $filter['active_by'] != '0') {
            if($filter['active_by'] == 'smsemail') {
                $where[] = "a.active_by like '%sms%' OR a.active_by like '%email%'";
            } else {
                $where[] = "a.active_by LIKE '%" . $filter['active_by'] . "%'"; 
            }
        }        
        
        if($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sort_array = array(
            'user_id',
            'username',
            'email',
            'phone'
        );
        if(isset($filter['sortby']) && in_array($filter['sortby'], $sort_array)) {
            $sql .= " ORDER BY a." . $filter['sortby'];
        } else {
            $sql .= " ORDER BY a.user_id";
        }
        
        if(isset($filter['order']) && $filter['order'] == 'ASC') {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }
        
        if(isset($filter['limit'])) {
            if($filter['start'] < 0) 
                $filter['start'] = 0;
            if($filter['limit'] < 1)
                $filter['limit'] = 20;
            $sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
        }
        $query = $this->dbuser->query($sql);
        return $query->result();
        
    }

	/*
	 * tổng số online theo bộ lọc
	 * $filter = array('user_agent'=>'value', ...)
	 */
	function totalFilterOnline($filter)
	{
		$sql = "SELECT count(*) AS total FROM online_users as a";
		
		$where = array();
		
		if(isset($filter['agent']) && $filter['agent'] != '0') {
			$where[] = "a.user_agent LIKE '%" . $filter['agent'] . "%'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$default = $this->load->database('default', TRUE);
     	$query = $default->query($sql);
		return $query->row()->total;
	}
    
	/*
	 * list online theo bộ lọc
	 * $filter = array('user_agent'=>'value', ...)
	 */
	function allFilterOnline($filter)
	{
		$sql = "SELECT * FROM online_users as a";
		
		$where = array();
		
		if(isset($filter['agent']) && $filter['agent'] != '0') {
			$where[] = "a.user_agent LIKE '%" . $filter['agent'] . "%'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

     	if(isset($filter['page']) || isset($filter['limit'])) {
     		if($filter['start'] < 0) {
     			$filter['start'] = 0;
     		}
     		if($filter['limit'] < 1) {
     			$filter['limit'] = 100;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
     	$default = $this->load->database('default', TRUE);
     	$query = $default->query($sql);
     	return $query->result();		
	}   
	
	/*
	 * xử lý thông tin online của user & guest
	 */
	function onlineData($session_id, $user_id, $ip_address, $user_agent, $last_activity) {
		$now = microtime(true);
        /*// xóa session quá x phút
		$expiration = microtime(true) - 15 * 60;
		$sql = "DELETE FROM online_users WHERE last_activity < $expiration";
		$this->db->query($sql);
        */
		$this->db->where('session_id', $session_id);
        if($user_id) $this->db->or_where('user_id', $user_id);
		$query = $this->db->get('online_users');
		if($query->num_rows()>0) {
			$data = array(
				'user_id' => $user_id,
				'ip_address' => $ip_address, 
				'user_agent' => $user_agent,
				'last_activity' => $last_activity
			);
			$this->db->where('session_id', $session_id);
            if($user_id) $this->db->or_where('user_id', $user_id);
			$this->db->update('online_users', $data);
		} else {
			$data = array(
				'session_id' => $session_id,
				'user_id' => $user_id,
				'ip_address' => $ip_address, 
				'user_agent' => $user_agent,
				'last_activity' => $last_activity
			);
			$this->db->insert('online_users', $data);
		}
	}
	
	function refreshOnlineUser() {
		$exp = time() - 30*60;
		$sql = "DELETE FROM online_users WHERE last_activity<$exp";
		$this->db->query($sql);		
	}

	/*****************************************************************************************/
	function getTym($userId) {
		$sql = "SELECT t1, t2, t3, t4 FROM user WHERE user_id=$userId";
		$result = $this->dbuser->query($sql);
		if ($result->num_rows()>0) {
			$tyms = $result->row_array();
			return $tyms;
		} else {
			return array();
		}
	}
	
	/*
	 * cộng tym cho người dùng
	 */
	function increaseTym($userId, $tymType, $amount) {
		$sql = "update user set $tymType=$tymType+$amount where user_id=$userId";
		$this->dbuser->query($sql);
	}
	
	function getRate() {
		$sql = "SELECT * FROM setting WHERE `key` IN ('rate1_2', 'rate1_3', 'rate1_4')";
		$result = $this->db->query($sql);
		if ($result->num_rows()>0) {
                    $rates = $result->result_array();
                    $rate = array();
                    foreach ($rates as $tmp) {
                        $rate[$tmp['key']] = $tmp['value'];
                    }
                    return $rate;
		} else {
                    return array();
		}
	}
	
	/*
	 * trừ tym của người dùng
	 */
	function decreaseTym($userId, $tymType, $amount) {
		//echo "$userId - $tymType - $amount";
        $tyms = $this->getTym($userId);
		$rate = $this->getRate();
		if (!empty($tyms) && !empty($rate)) {
			$rate1_2 = $rate['rate1_2'];
			$rate1_3 = $rate['rate1_3'];
			$rate1_4 = $rate['rate1_4'];
			
			$currentT1 = $tyms['t1'];
			$currentT2 = $tyms['t2'];
			$currentT3 = $tyms['t3'];
			$currentT4 = $tyms['t4'];
			
			switch ($tymType) {
				case 't1':
					if ($amount <= $currentT1) {
						$currentT1 = $currentT1 - $amount;
						$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
						$this->dbuser->query($sql);
						return true;
					} else return false; // Ko đủ tiền
					break;
				case 't2':
					if ($amount <= $currentT2) {
						$currentT2 = $currentT2 - $amount;
						$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
						$this->dbuser->query($sql);
                        return true;
					} else {
						$totalT2 = $currentT1 * $rate1_2 + $currentT2;
						if ($amount <= $totalT2) {
							$totalT2 = $totalT2 - $amount;
							$currentT2 = 0;
							$currentT1 = ($totalT2 - $currentT2) / $rate1_2;
							$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
							$this->dbuser->query($sql);
							return true;
						} else {
							return false;
						}
						return false;
					}
					break;
				case 't3':
					if ($amount <= $currentT3) {
						$currentT3 = $currentT3 - $amount;
						$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
						$this->dbuser->query($sql);
                        return true;
					} else {
						$totalT3 = ($currentT1 * $rate1_3 + $currentT3);
						if ($amount <= $totalT3) {
							$totalT3 = $totalT3 - $amount;
							$currentT3 = 0;
							$currentT1 = ($totalT3 - $currentT3) / $rate1_3;
							$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
							$this->dbuser->query($sql);
							return true;
						} else {
							return false;
						}
						return false;
					}
					break;
				case 't4':
					if ($amount <= $currentT4) {
						$currentT4 = $currentT4 - $amount;
						$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
						$this->dbuser->query($sql);
                        return true;
					} else {
						$totalT4 = $currentT1 * $rate1_4 + $currentT4;
						if ($amount <= $totalT4) {
							$totalT4 = $totalT4 - $amount;
							$currentT4 = 0;
							$currentT1 = ($totalT4 - $currentT4) / $rate1_4;
							$sql = "UPDATE user SET t1=$currentT1, t2=$currentT2, t3=$currentT3, t4=$currentT4 WHERE user_id=$userId";
							$this->dbuser->query($sql);
							return true;
						} else {
							return false;
						}
						return false;
					}
					break;
			}
		} else {
			return false;
		}
	}

    function exchange($userId, $tymType, $amountT1) {
        if($amountT1<0) return false;
        $tyms = $this->getTym($userId);
        if ($tyms['t1'] >= $amountT1) {

            $rates = $this->getRate();
            if ($tymType=='t2') {
                $rate = $rates['rate1_2'];
                $x = 2;
            } elseif ($tymType=='t3') {
                $rate = $rates['rate1_3'];
                $x = 3;
            } elseif ($tymType=='t4') {
                $rate = $rates['rate1_4'];
                $x = 4;
            }

            $newTx = (int)($amountT1 * $rate);
            $sql = "UPDATE user SET t1=t1-$amountT1, t$x=t$x+$newTx WHERE user_id=$userId";
            $this->dbuser->query($sql);
            $t1_old = $tyms['t1'];
            $t1_new = $t1_old - $amountT1;
            $tx_type = $x;
            $tx_old = $tyms['t'.$x];
            $tx_new = $tyms['t'.$x] + $newTx;
            $user = $this->getUserById($userId);
            $username = $user->username;
            $time = time();
            $logData = array(
                't1_old' => $t1_old,
                't1_new' => $t1_new,
                't1_used' => $amountT1,
                'tx_type' => 't'.$tx_type,
                'tx_old' => $tx_old,
                'tx_receive' => $newTx,
                'tx_new' => $tx_new,
                'rate' => $rate,
                'user_id' => $userId,
                'username' => $username,
                'time' => $time
            );
            $this->exchangeLog($logData);
            return true;

        } else {
            return false;
        }

    }
    
    function exchangeLog($data) {
        $this->dbuser->insert('exchange_log', $data);
    }
    
    
    
    
    /*====================================================================*\
     *                              user logs                             *  
     *====================================================================*/
    
    function totalDownloadLog($filter) {
        $sql = "SELECT count(*) AS total FROM downloads as a";
		
		$where = array();
		
        if(isset($filter['userid'])) {
            $where[] = 'a.user_id=' . $filter['userid'];
        }
		if($filter['startdate'] != '0') {
			$start = $filter['startdate'];
			$end = $filter['enddate'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
	
		$query = $this->db->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;
    }
    
    function allDownloadLog($filter) {
        $sql = "SELECT * FROM downloads as a";
		
		$where = array();
        
        if(isset($filter['userid'])) {
            $where[] = 'a.user_id=' . $filter['userid'];
        }
		if($filter['startdate'] != '0') {
			$start = $filter['startdate'];
			$end = $filter['enddate'];
			$where[] = ' time >= ' . $start . ' AND time <= ' . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
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
		if($query->num_rows() > 0) return $query->result();
		else return false;	
    }  
    
    function totalPaymentLog($filter) {
        $sql = "SELECT count(*) AS total FROM transaction as a";
		
		$where = array();
		
        if(isset($filter['method']) && $filter['method'] != '0') {
            $where[] = "a.method='" . $filter['method'] . "'";
        }
        if(isset($filter['status']) && $filter['status'] != '0') {
            $where[] = "a.status='" . $filter['status'] . "'";
        }         
        if(isset($filter['userid'])) {
            $where[] = "a.user_id=" . $filter['userid'];
        }
		if($filter['startdate'] != '0') {
			$start = $filter['startdate'];
			$end = $filter['enddate'];
			$where[] = " time >= " . $start . " AND time <= " . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
	
		$query = $this->dbuser->query($sql);
		if($query->first_row()->total) return $query->first_row()->total;
		else return 0;
    }
    
    function allPaymentLog($filter) {
        $sql = "SELECT * FROM transaction as a";
		
		$where = array();
        
        if(isset($filter['method']) && $filter['method'] != '0') {
            $where[] = "a.method='" . $filter['method'] . "'";
        }
        if(isset($filter['status']) && $filter['status'] != '0') {
            $where[] = "a.status='" . $filter['status'] . "'";
        }         
        if(isset($filter['userid'])) {
            $where[] = "a.user_id=" . $filter['userid'];
        }
		if($filter['startdate'] != '0') {
			$start = $filter['startdate'];
			$end = $filter['enddate'];
			$where[] = " time >= " . $start . " AND time <= " . $end;
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
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
        
     	$query = $this->dbuser->query($sql);
		if($query->num_rows() > 0) return $query->result();
		else return false;	
    }     
    
    /*
     * register package log
     */
    function addPackageLog($data) {
        $this->dbuser->insert('package_log', $data);
        return $this->dbuser->insert_id();
    }
    
/*
	 * tổng số download
	 */
	function totalPackageLog($filter = array())
	{
		$sql = "SELECT count(*) AS total FROM package_log as a";
        $where = array();
        if(isset($filter['store']) && $filter['store'] != '0') {
            $where[] = "a.store='" . $filter['store'] . "'";
        }
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['package_type']) && $filter['package_type'] != '0') {
			$where[] = "a.package_type='" . $filter['package_type'] . "'";
		}
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "registered_date >= " . $start . " AND registered_date <= " . $end;
		}
		
		if($where) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;	
	}
    
/*
	 * get all package_log
	 */
	function allPackageLog($filter = array(), $limit = '10', $start = '0')
	{
        $sql = "SELECT * FROM package_log as a";
        $where = array();
        if(isset($filter['store']) && $filter['store'] != '0') {
            $where[] = "a.store='" . $filter['store'] . "'";
        }
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['package_type']) && $filter['package_type'] != '0') {
			$where[] = "a.package_type='" . $filter['package_type'] . "'";
		}
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "registered_date >= " . $start . " AND registered_date <= " . $end;
		}
		
		if($where) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}

        $sort_array =  array(
			'log_id',
            'user_id',
			'package_type'
     	);
     	if(isset($filter['sort']) && in_array($filter['sort'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sort'];
     	} else {
     		$sql .= " ORDER BY a.log_id";
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
		return $query->result();
	}

	function totalPackageUser($filter) {
		$sql = "SELECT count(distinct user_id) AS total FROM package_log as a";
        $where = array();
        if(isset($filter['store']) && $filter['store'] != '0') {
            $where[] = "a.store='" . $filter['store'] . "'";
        }
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['package_type']) && $filter['package_type'] != '0') {
			$where[] = "a.package_type='" . $filter['package_type'] . "'";
		}
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "registered_date >= " . $start . " AND registered_date <= " . $end;
		}
		
		if($where) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;
	}
	
	function totalPackageTym($filter) {
		$sql = "SELECT sum(tym_price) AS total FROM package_log as a";
        $where = array();
        if(isset($filter['store']) && $filter['store'] != '0') {
            $where[] = "a.store='" . $filter['store'] . "'";
        }
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}        
		if(isset($filter['package_type']) && $filter['package_type'] != '0') {
			$where[] = "a.package_type='" . $filter['package_type'] . "'";
		}
		if($filter['starttime'] != '0') {
			$start = $filter['starttime'];
			$end = $filter['endtime'];
			$where[] = "registered_date >= " . $start . " AND registered_date <= " . $end;
		}
		
		if($where) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}
		
		$query = $this->dbuser->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;
	}
    
    function getUserPack($userId, $store) {
        $this->dbuser->where('user_id', $userId);
        $this->dbuser->where('store', $store);
        $query = $this->dbuser->get('package_user');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function getAllUserPack($userId) {
    	$this->dbuser->where('user_id', $userId);
    	$query = $this->dbuser->get('package_user');
    	if($query->num_rows()>0) {
    		return $query->result();
    	} else {
    		return false;
    	}
    }
    
    function updateUserPack($userId, $store, $data) {
        $this->dbuser->where('user_id', $userId);
        $this->dbuser->where('store', $store);
        $this->dbuser->update('package_user', $data);
    }
    
    function addUserPack($data) {
        $this->dbuser->insert('package_user', $data);
        return $this->dbuser->insert_id();
    }
    
    
    /*
     * giftcode minh chau
     * prefix mc
     */
    function mcCheckPhone($phone) {
        $this->dbuser->where('phone', $phone);
        $query = $this->dbuser->get('giftcode_mc');
        if($query->num_rows()>0) {
            return true;
        } else {
            return false;
        }
    }
    
    function mcGetGiftcode() {
        $this->dbuser->where('status', 0);
        $this->dbuser->limit(1);
        $query = $this->dbuser->get('giftcode_mc');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function mcUpdateGiftcode($id, $data) {
        $this->dbuser->where('id', $id);
        $this->dbuser->update('giftcode_mc', $data);
    }
    
    /*
     * quay số may mắn
     * prefix ln
     */
    function lnAdd($data) {
        $this->dbuser->insert('appkh1', $data);
        return $this->dbuser->insert_id();
    }
    
    function lnCheckPhone($phone) {
        $this->dbuser->where('phone', $phone);
        $query = $this->dbuser->get('appkh1');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function lnUpdateSpayAPI($id, $api) {
        $spay = $this->load->database('spay', TRUE);
        $spay->where('id', $id);
        $spay->set('api', $api);
        $spay->update('api');
    }
    
    function lnTotal() {
        $sql = "select count(*) as total from appkh1";
        $query = $this->dbuser->query($sql);
        if($query->num_rows()>0) {
            return $query->row()->total;
        } else {
            return 0;
        }
    }
    
    function lnAll() {
        $query = $this->dbuser->get('appkh1');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function lnGetInfo($id) {
        $this->dbuser->where('gift_id', $id);
        $query = $this->dbuser->get('appkh1');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function lnUpdateNumber($number) {
        $this->dbuser->where('key', 'appkh1_num');
        $this->dbuser->set('value', $number);
        $this->dbuser->update('setting');
    }
    
    function lnGetNumber() {
        $this->dbuser->where('key', 'appkh1_num');
        $query = $this->dbuser->get('setting');
        return $query->row()->value;
    }
    
    /*
     * end quay số may mắn
     */
    
    
    /*
     * vip download
     * store: fshare || 4share
     */
	 
    function getDownloadVip($userId) {
        $this->dbuser->where('user_id', $userId);
        $query = $this->dbuser->get('downloadvip_user');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function getDownloadVipByUserId($userId, $store = 'fshare') {
        $this->dbuser->where('user_id', $userId);
        $this->dbuser->where('dvip_store', $store);
        $query = $this->dbuser->get('downloadvip_user');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function updateDownloadVip($user_id, $store = 'fshare', $data) {
        $this->dbuser->where('user_id', $user_id);
        $this->dbuser->where('dvip_store', $store);
        $this->dbuser->update('downloadvip_user', $data);
    }
    
    function addDownloadVip($data) {
        $this->dbuser->insert('downloadvip_user', $data);
    }
    
    function addDownloadVipLog($data) {
        $this->dbuser->insert('downloadvip_log', $data);
    }


    function getDownloadPack($store) {
        $this->dbuser->where('store', $store);
        $this->dbuser->where('active', 1);
        $this->dbuser->order_by('id', 'ASC');
        $query = $this->dbuser->get('downloadvip_pack');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function updateDownloadPack($id, $data) {
        $this->dbuser->where('id', $id);
        $this->dbuser->update('downloadvip_pack', $data);
    }
    
    function getDownloadPrice($id) {
        $this->dbuser->where('id', $id);
        $query = $this->dbuser->get('downloadvip_pack');
        if($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    /*
     * FMC games
     */
    
    function fmcAddTransaction($data) {
        $this->dbuser->insert('partner_fmc', $data);
        return $this->dbuser->insert_id();
    }
    
    function fmcUpdateTransaction($id, $data) {
        $this->dbuser->where('fmc_id', $id);
        $this->dbuser->update('partner_fmc', $data);
    }
    
    /*
     * end FMC
     */
    function addGiftcode($data) {
        $this->dbuser->insert('event_giftcode_log', $data);
        return $this->dbuser->insert_id();
    }
    
    function checkGiftcode($sender, $receiver) {
        $this->dbuser->where('sender', $sender);
        $this->dbuser->where('receiver', $receiver);
        $this->dbuser->order_by('log_id', 'DESC');
        $query = $this->dbuser->get('event_giftcode_log');
        if($query->num_rows()>0) {
            return $query->first_row();
        } else {
            return false;
        }
    }
	
    /*
     * gift code huong 2
     */
    function checkGiftcode2($senderId, $receiver) {
        $this->dbuser->where('sender_id', $senderId);
        $this->dbuser->where('receiver', $receiver);
        $query = $this->dbuser->get('event_giftcode');
        if($query->num_rows()>0) {
            return $query->first_row();
        } else {
            return false;
        }
    }
    
    function getGiftcodeBySender($senderId) {
        $this->dbuser->where('sender_id', $senderId);
        $query = $this->dbuser->get('event_giftcode');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
?>
