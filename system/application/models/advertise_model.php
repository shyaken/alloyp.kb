<?php
class Advertise_model extends Model
{
	function Advertise_model()
	{
		parent::__construct();
		$this->load->database('default', TRUE);		
	}
	
	/*
	 * danh sách quảng cáo
	 */
	function listAd($section = '', $order = 'id', $sort = 'ASC')
	{
                $timeNow = time();
		if($section != '') $this->db->where('section', $section);
		$this->db->where('publish', 1);
                $this->db->where('start_date <',$timeNow);
                $this->db->where('end_date >',$timeNow);
		$this->db->order_by($order, $sort);
		$query = $this->db->get('advertise');
		if($query->num_rows() > 0) {
			return $query->result();
		} else return array();
	}
	
/*
	 * danh sách quảng cáo
	 */
	function listAll($section = '', $order = 'id', $sort = 'ASC')
	{
		if($section != '') $this->db->where('section', $section);
		$this->db->order_by($order, $sort);
		$query = $this->db->get('advertise');
		if($query->num_rows() > 0) {
			return $query->result();
		} else return array();
	}
	
        function enabledAd()
	{
                $this->db->select('id');
		$this->db->where('publish', 1);
		$query = $this->db->get('advertise');
		if($query->num_rows() > 0) {
                    return $query->result();
		} else return false;
	}
        function publish($id) {
            $this->db->where('id',$id);
            $this->db->set('publish',1);
            $this->db->update('advertise');
        }
        function unpublish($id) {
            $this->db->where('id',$id);
            $this->db->set('publish',0);
            $this->db->update('advertise');
        }
	/*
	 * thêm mới quảng cáo
	 */
	function add($data)
	{
		$this->db->insert('advertise', $data);
	}
	
	/*
	 * lấy thông tin quảng cáo
	 */
	function getInfo($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('advertise');
		if($query->num_rows() > 0)
			return $query->row();
		else
			return array();	
	}
	
	/*
	 * chỉnh sửa quảng cáo
	 */
	function edit($id, $data) 
	{
		if($data['upload']) {
			$cur = $this->getInfo($id);
			if(file_exists($cur->image))
				unlink($cur->image);
		}

		unset($data['upload']);
		$this->db->where('id', $id);
		$this->db->update('advertise', $data);
	}
	
	/*
	 * xóa quảng cáo
	 */
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('advertise');
	}
	
	/*
	 * trả về số ô đã sử dụng để đặt quảng cáo ở header
	 * tồn tại: array('a', 'b', ...)
	 * không tồn tại: false
	 */
	function unitUsed($section)
	{
		$this->db->where('publish', 1);
		$this->db->where('section', $section);
		$query = $this->db->get('advertise');
		if($query->num_rows() > 0) {
			$results = $query->result();
			// trường hợp 1 quảng cáo 4 ô
			if(count($results) == 1) {
				$x = $results[0];
				if($x->type == 'all') {
					return array('1', '2', '3', '4');
				} else {
					$start = $x->start;
					$type = $x->type;
					if($type == 'ngang') {
						if($x->unit == 2) return array($start, $start+1);
						return array($start);
					} 
					/*
					 * type == doc
					 * else {
						if($x->unit == 2) return array($start, $start+2);
						return array($start);
					 * }
					*/
				}
			} else {
				$data = array();
				foreach($results as $result) {
					$start = $result->start;
					$type = $result->type;
					$unit = $result->unit;
					
					if($type == 'ngang') {
						if(!in_array($start, $data) && isset($data)) $data[] = $start;
						if($unit == 2) {
							if(!in_array($start+1, $data) && isset($data)) $data[] = $start+1;
						}
					} else {
						if(!in_array($start, $data) && isset($data)) $data[] = $start;
						if($unit == 2) {
							if(!in_array($start+2, $data) && isset($data)) $data[] = $start+2;
						}
					}					
				}
				return $data;
			}
		} else return array('-1');
	}
        
        /*
         * kiểm tra xem section header1 đã dùng chưa
         */
        function checkHeader1()
        {
        	$this->db->where('publish', 1);
            $this->db->where('section', 'header1');
            $query = $this->db->get('advertise');
            if($query->num_rows() > 0)
                return 1;
            else 
                return 0;
        }
        
        function countClick($id, $time)
        {
            $sql = "UPDATE advertise_log SET click=click+1 WHERE advertise_id=$id AND time=$time";
            $this->db->query($sql);
        }
        
        /*
         * count view
         */
        function countView($id, $time)
        {
            $this->db->where('advertise_id', $id);
            $this->db->where('time', $time);
            $query = $this->db->get('advertise_log');
            if($query->num_rows()>0) {
                $sql = "UPDATE advertise_log SET view=view+1 WHERE advertise_id=$id AND time=$time";
                $this->db->query($sql);
            } else {
                $data = array(
                    'advertise_id' => $id,
                    'time' => $time
                );
                $this->db->insert('advertise_log', $data);
            }
        }
        
        function adByDate($filter) {
            $sql = "SELECT advertise_id, time, SUM(click) AS click, SUM(view) AS view FROM advertise_log";
            $where = array();
            if($filter['advertise_id'] != '0') {
                $where[] = "advertise_id=".$filter['advertise_id'];
            }
            if($filter['starttime'] && $filter['endtime']) {
                $where[] = " time>=".$filter['starttime']." AND time<=".$filter['endtime'];
            }
            if($where) {
                $sql.= " WHERE " . implode(' AND ', $where);
            }
            $sql.= " GROUP BY advertise_id ORDER BY ".$filter['sort']." ".$filter['order'];
            $query = $this->db->query($sql);
            if($query->num_rows()>0) {
                return $query->result();
            } else {
                return false;
            }
        }
        
        function dateRange($id, $dateRange) {
            $sql = "SELECT * FROM advertise_log";
            $sql.= " WHERE advertise_id=$id AND time in $dateRange";
            $query = $this->db->query($sql);
            if($query->num_rows()>0) {
                return $query->result();
            } else {
                return false;
            }
        }
}