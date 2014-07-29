<?php
class Event_model extends Model {
    private $dbuser;
    function Event_model() {
        parent::__construct();
        $this->dbuser = $this->load->database('dbuser', TRUE);
    }
    
    function getEventType($typeId) {
        $this->dbuser->where('type_id', $typeId);
        $query = $this->dbuser->get('event_type');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function getAll($filter = 'expired_time', $start, $limit) {
        $this->dbuser->where('active', 1);
        $this->dbuser->order_by($filter, 'DESC');
        $this->dbuser->limit($limit, $start);
        $query = $this->dbuser->get('event');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function getEvent($eventId, $frontend = 'true') {
        if($frontend) $this->dbuser->where('active', 1);
        $this->dbuser->where('event_id', $eventId);
        $query = $this->dbuser->get('event');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function getGiftbox($giftboxId, $frontEnd = true) {
        $this->dbuser->where('box_id', $giftboxId);
        if($frontEnd) $this->dbuser->where('publish', 1);
        $query = $this->dbuser->get('event_giftbox');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function deleteGiftbox($giftboxId) {
        $this->dbuser->where('box_id', $giftboxId);
        $this->dbuser->delete('event_giftbox');
    }
    
    function getGift($giftboxId) {
        $this->dbuser->where('giftbox_id', $giftboxId);
        $query = $this->dbuser->get('event_gift');
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function getGiftById($giftId) {
        $this->dbuser->where('gift_id', $giftId);
        $query = $this->dbuser->get('event_gift');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function getGiftboxByEventId($eventId, $frontEnd = false) {
        $this->dbuser->where('event_id', $eventId);
        if(!$frontEnd) $this->dbuser->where('publish', 1);
        $this->dbuser->order_by('order', 'DESC');
        $query = $this->dbuser->get('event_giftbox');
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function editGift($giftId, $data) {
        $this->dbuser->where('gift_id', $giftId);
        $this->dbuser->update('event_gift', $data);
    }
    
    
    function deleteGift($giftId) {
        $this->dbuser->where('gift_id', $giftId);
        $this->dbuser->delete('event_gift');
    }
    
    function updateEvent($eventId, $data) {
        $this->dbuser->where('event_id', $eventId);
        $this->dbuser->update('event', $data);
    }
    
    function addEvent($data) {
        $this->dbuser->insert('event', $data);
        return $this->dbuser->insert_id();
    }
    
    function lastPlayer($limit = 10, $eventId) {
        $sql = "SELECT * FROM event_play WHERE event_id=$eventId ORDER BY last_play DESC LIMIT $limit";
        $query = $this->dbuser->query($sql);
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function mostPlayer($limit = 10, $eventId) {
        $sql = "SELECT * FROM event_play WHERE event_id=$eventId ORDER BY playing DESC LIMIT $limit";
        $query = $this->dbuser->query($sql);
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function luckyPlayer($limit = 10, $eventId) {
        $sql = "SELECT count(user_id) AS total,event_giftbox_log.* 
                FROM event_giftbox_log 
                WHERE receive_status=1 AND event_id=$eventId
                GROUP BY user_id 
                ORDER BY total DESC LIMIT 10";
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function addGift($data) {
        $this->dbuser->insert('event_gift', $data);
        return $this->dbuser->insert_id();
    }
    
    function updateGift($giftId, $data) {
        $this->dbuser->where('gift_id', $giftId);
        $this->dbuser->update('event_gift', $data);
    }
    
    function addGiftbox($data) {
        $this->dbuser->insert('event_giftbox', $data);
        return $this->dbuser->insert_id();
    }
    
    function editGiftbox($giftboxId, $data) {
        $this->dbuser->where('box_id', $giftboxId);
        $this->dbuser->update('event_giftbox', $data);
    }
    
    function updateGiftbox($boxId, $data) {
        $this->dbuser->where('box_id', $boxId);
        $this->dbuser->update('event_giftbox', $data);
    }
    
    function removeGiftbox($boxId) {
        $this->dbuser->where('box_id', $boxId);
        $this->dbuser->delete('event_giftbox');
    }
    
    function totalFilterEvent($filter)
	{
		$sql = "SELECT count(*) AS total FROM event as a";
		
		$where = array();
		
		if(isset($filter['event_id']) && $filter['event_id'] != '0') {
			$where[] = "a.event_id=" . $filter['event_id'];
		}
		if(isset($filter['type']) && $filter['type'] != '-1') {
			$where[] = "a.type_id=" . $filter['type'];
		}
        if(isset($filter['name']) && $filter['name'] != '0') {
			$where[] = "a.name LIKE '%" . $filter['name'] . "%'";
		}
		if(isset($filter['sponsor']) && $filter['sponsor'] != '0') {
			$where[] = "a.sponsor LIKE '%" . $filter['sponsor'] . "%'";
		}
        if(isset($filter['active']) && $filter['active'] != '-1') {
			$where[] = "a.active=" . $filter['active'];
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
	function allFilterEvent($filter) {
		$sql = "SELECT * FROM event as a";
		
		$where = array();
		
		if(isset($filter['event_id']) && $filter['event_id'] != '0') {
			$where[] = "a.event_id=" . $filter['event_id'];
		}
		if(isset($filter['type']) && $filter['type'] != '-1') {
			$where[] = "a.type_id=" . $filter['type'];
		}
        if(isset($filter['name']) && $filter['name'] != '0') {
			$where[] = "a.name LIKE '%" . $filter['name'] . "%'";
		}
		if(isset($filter['sponsor']) && $filter['sponsor'] != '0') {
			$where[] = "a.sponsor LIKE '%" . $filter['sponsor'] . "%'";
		}
        if(isset($filter['active']) && $filter['active'] != '-1') {
			$where[] = "a.active=" . $filter['active'];
		}
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'event_id',
			'active',
     		'expired_time'
     	);
     	if(isset($filter['sortby']) && in_array($filter['sortby'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sortby'];
     	} else {
     		$sql .= " ORDER BY a.event_id";
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
    
    function getInfo($eventId) {
        $this->dbuser->where('event_id', $eventId);
        $query = $this->dbuser->get('event');
        return $query->row();
    }
    
    function allEventType() {
        $query = $this->dbuser->get('event_type');
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function addLog($logData) {
        $this->dbuser->insert('event_giftbox_log', $logData);
        return $this->dbuser->insert_id();
    }
    
    function upEventPlay($eventId) {
        $sql = "UPDATE event set playing=playing+1 WHERE event_id=$eventId";
        $this->dbuser->query($sql);
    }
    
    function upUserPlayEvent($userId, $username, $eventId) {
        $this->dbuser->where('user_id', $userId);
        $this->dbuser->where('event_id', $eventId);
        $query = $this->dbuser->get('event_play');
        if($query->num_rows() > 0) {
            $sql = "update event_play set last_play=".time().", playing=playing+1 where user_id=$userId and event_id=$eventId";
            $this->dbuser->query($sql);
        } else {
            $data = array(
                'user_id' => $userId,
                'username' => $username,
                'event_id' => $eventId,
                'playing' => 1,
                'last_play' => time()
            );
            $this->dbuser->insert('event_play', $data);
        }
    }
    
    /*
     * dem luot trung thuong gift
     */
    function upRewardGift($giftId) {
        $sql = "UPDATE event_gift SET datrung=datrung+1 WHERE gift_id=$giftId";
        $this->dbuser->query($sql);
    }
    
    /*
     * số quà trúng trong hộp quà của người dùng
     */
    function giftOfUserInGiftbox($userId, $giftboxId) {
        $this->dbuser->where('user_id', $userId);
        $this->dbuser->where('giftbox_id', $giftboxId);
        $query = $this->dbuser->get('event_giftbox_log');
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    /*
	 * list filter logs
	 */
	function allFilterLog($filter) {
		$sql = "SELECT * FROM event_giftbox_log as a";
		
		$where = array();
		
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
        if(isset($filter['event_id']) && $filter['event_id'] != '0') {
			$where[] = "a.event_id=" . $filter['event_id'];
		}
        if(isset($filter['receive_type']) && $filter['receive_type'] != '0') {
			$where[] = "a.receive_type='" . $filter['receive_type'] . "'";
		}
        if(isset($filter['receive_status']) && $filter['receive_status'] != '-1') {
			$where[] = "a.receive_status='" . $filter['receive_status'] . "'";
		}
        if(isset($filter['time']) && $filter['time'] != '0') {
			$start = strtotime($filter['time'].' 00:00:01');
            $end = strtotime($filter['time'].' 23:59:59');
            $where[] = "a.time >= $start AND a.time <= $end";
		}
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'log_id',
			'event_id',
     	);
     	if(isset($filter['sortby']) && in_array($filter['sortby'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sortby'];
     	} else {
     		$sql .= " ORDER BY a.log_id";
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
    
    function totalFilterLog($filter) {
		$sql = "SELECT count(*) as total FROM event_giftbox_log as a";
		
		$where = array();
		
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id=" . $filter['user_id'];
		}
		if(isset($filter['username']) && $filter['username'] != '0') {
			$where[] = "a.username LIKE '%" . $filter['username'] . "%'";
		}
        if(isset($filter['event_id']) && $filter['event_id'] != '0') {
			$where[] = "a.event_id=" . $filter['event_id'];
		}
        if(isset($filter['receive_type']) && $filter['receive_type'] != '0') {
			$where[] = "a.receive_type='" . $filter['receive_type'] . "'";
		}
        if(isset($filter['receive_status']) && $filter['receive_status'] != '-1') {
			$where[] = "a.receive_status='" . $filter['receive_status'] . "'";
		}
        if(isset($filter['time']) && $filter['time'] != '0') {
			$start = strtotime($filter['time'].' 00:00:01');
            $end = strtotime($filter['time'].' 23:59:59');
            $where[] = "a.time >= $start AND a.time <= $end";
		}
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

     	$query = $this->dbuser->query($sql);
     	if($query->num_rows() > 0) {
            return $query->row()->total;
        } else {
            return 0;
        }		
	}
    
    function getEventLogByUserId($filter, $userId) {
        $sql = "SELECT * FROM event_giftbox_log AS a";
        $limit = $filter['limit'];
        $start = $filter['start'];
        $sql .= " WHERE user_id=$userId";
        if($filter['time']) {
            $startx = strtotime($filter['time'].' 00:00:01');
            $end = strtotime($filter['time'].' 23:59:59');
            $sql .= " AND a.time >= $startx AND a.time <= $end";
        }
        $sql .= " ORDER BY a.time DESC LIMIT $start,$limit";
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function systemTym($type) {
        $sql = "SELECT SUM(receive_value) as total 
                FROM event_giftbox_log 
                WHERE receive_type='$type'
                ";
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0) {
            return $query->row()->total;
        } else {
            return 0;
        }
    }
    
    function userTym($type) {
        $sql = "SELECT SUM(tym_price) as total 
                FROM event_giftbox_log 
                WHERE tym_type='$type'
                ";
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0) {
            return $query->row()->total;
        } else {
            return 0;
        }
    }
    
    function uniqueUser() {
        $sql = "select count(distinct user_id) as total from event_giftbox_log";
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0) {
            return $query->row()->total;
        } else {
            return 0;
        }
    }
    
    function uniquePlayerInEvent($eventId) {
        $sql = "SELECT count(DISTINCT user_id) AS total FROM event_play WHERE event_id=$eventId";
        $query = $this->dbuser->query($sql);
        if($query->num_rows() > 0) {
            return $query->row()->total;
        } else {
            return 0;
        }
    }
    
}
?>
