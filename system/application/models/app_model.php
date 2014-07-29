<?php
class App_model extends Model
{
    public $store = "a";
	function App_model() {
		parent::__construct();
        $last = substr(base_url(),-2,1);
        if($last == '/') $last = 'none';
        $this->store = $last;
	}
	
	/*
	 * thêm mới ứng dụng
	 */
	function add($data)
	{
		$this->db->insert('apps', $data);
		return $this->db->insert_id();
	}
	
	/*
	 * thêm mới version cho ứng dụng
	 */
	function addAppVersion($data)
	{
		$this->db->insert('app_version', $data);
	}
	
	/*
	 * chỉnh sửa ứng dụng
	 */
	function edit($id, $data)
	{
		$this->db->where('app_id', $id);
		$this->db->update('apps', $data);
	}
	
	/*
	 * xóa ứng dụng
	 */
	function delete($id)
	{
		$this->db->where('app_id', $id);
		$this->db->delete('apps');
	}
	
	/*
	 * cập nhật ứng dụng
	 */
	function update($id, $data)
	{
		$this->db->where('app_id', $id);
		$this->db->update('apps', $data);
	}
	
	/*
	 * cập nhật ứng dụng theo điều kiện khác app_id ( có thể là app_id )
	 */
	function updateApp($where, $data) {
		$this->db->where($where);
		$this->db->update('apps', $data);
	}
	
	/*
	 * bật ứng dụng
	 */
	function publish($id)
	{
		$this->db->where('app_id', $id);
		$this->db->set('publish', 1);
		$this->db->update('apps');
	}
	
	/*
	 * tắt ứng dụng
	 */
	function unPublish($id)
	{
		$this->db->where('app_id', $id);
		$this->db->set('publish', 0);
		$this->db->update('apps');
	}
	
	/*
	 * stick ứng dụng
	 */
	function sticky($id)
	{
		$this->db->where('app_id', $id);
		$this->db->set('is_sticky', 1);
		$this->db->update('apps');	
	}
	
	/*
	 * unstick ứng dụng
	 */
	function unsticky($id)
	{
		$this->db->where('app_id', $id);
		$this->db->set('is_sticky', 0);
		$this->db->update('apps');	
	}	
	
	/*
	 * ứng dụng tồn tại hay không
	 */
	function isExists($id)
	{
		$this->db->where('app_id', $id);
		$query = $this->db->get('apps');
		if($query->num_rows() > 0)
			return true;
		else 
			return false;	
	}
	
	/*
	 * lấy thông tin của ứng dụng theo id
	 */
	function getInfo($id)
	{
		$this->db->where('app_id', $id);
		$query = $this->db->get('apps');
		if($query->num_rows() > 0) {
			return $query->row();
		} else return false;
	}

    function getCategory($appId) {
        $this->db->where('app_id', $appId);
        $this->db->join('category', 'category.category_id=apps.category');
        //$this->db->select('*');
        $category = $this->db->get('apps');
        $info = $category->row_array();
        return $info;
    }

	/*
	 * tổng số ứng dụng
	 */
	function totalApp()
	{
		
	}
	
/*
	 * tổng số ứng dụng theo bộ lọc
	 * $filter = array('app_name'=>'value', 'price'=>'value', ...)
	 */
	function totalSettingApp($filter)
	{
		$sql = "SELECT count(*) AS total FROM apps as a";
		
		$where = array();
		
		if(isset($filter['app_id']) && is_numeric($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id='" . $filter['app_id'] . "'";
		}
		if(isset($filter['app_name']) && $filter['app_name'] != '0') {
			$where[] = "a.app_name LIKE '%" . $filter['app_name'] . "%'";
		}
		if(isset($filter['priority_price']) && $filter['priority_price'] != '-1') {
			$where[] = "a.priority_price='" . $filter['priority_price'] . "'";
		}
		if(isset($filter['price']) && $filter['price'] != '0') {
			$prices = explode('-', $filter['price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.tym_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.tym_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.tym_price <= " . $prices[1];
		}
		if(isset($filter['tym']) && $filter['tym'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym'] . "'";
		}
		if(isset($filter['method']) && $filter['method'] != '0') {
			$where[] = "a.method='" . $filter['method'] . "'";
		}
		if(isset($filter['package']) && $filter['package'] != '-1') {
			$where[] = "a.package=" . $filter['package'];
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->db->query($sql);
		return $query->row()->total;
	}	
	
	function allSettingApp($filter)
	{
		$sql = "SELECT * FROM apps as a";
		
		$where = array();
		
		if(isset($filter['app_id']) && is_numeric($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id=" . $filter['app_id'];
		}
		if(isset($filter['app_name']) && $filter['app_name'] != '0') {
			$where[] = "a.app_name LIKE '%" . $filter['app_name'] . "%'";
		}
		if(isset($filter['priority_price']) && $filter['priority_price'] != '-1') {
			$where[] = "a.priority_price='" . $filter['priority_price'] . "'";
		}		
		if(isset($filter['price']) && $filter['price'] != '0') {
			$prices = explode('-', $filter['price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.tym_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.tym_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.tym_price <= " . $prices[1];
		}		
		if(isset($filter['tym']) && $filter['tym'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym'] . "'";
		}
		if(isset($filter['method']) && $filter['method'] != '0') {
			$where[] = "a.method='" . $filter['method'] . "'";
		}
		if(isset($filter['package']) && $filter['package'] != '-1') {
			$where[] = "a.package = " . $filter['package'];
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'app_id',
			'tym_price',
			'category',
			'package'
     	);
     	if(isset($filter['sort']) && in_array($filter['sort'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sort'];
     	} else {
     		$sql .= " ORDER BY a.app_id";
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
     			$filter['limit'] = 10;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
     	
     	//var_dump($sql);	//for debug
     	$query = $this->db->query($sql);
     	return $query->result();		
	}
	
	/*
	 * tổng số ứng dụng theo bộ lọc
	 * $filter = array('app_name'=>'value', 'vendor'=>'value', ...)
	 */
	function totalFilterApp($filter)
	{
		$sql = "SELECT count(*) AS total FROM apps as a";
		
		$where = array();
		
		if($filter['startdate'] != '0') {
			$startdate = $filter['startdate'];
			$enddate = $filter['enddate'];
			$sql .= ' WHERE upload_time >= ' . $startdate . ' AND upload_time <= ' . $enddate;
		}
		
		if(isset($filter['app_name']) && $filter['app_name'] != '0') {
			$where[] = "a.app_name LIKE '%" . $filter['app_name'] . "%'";
		}
		if(isset($filter['vendor']) && $filter['vendor'] != '0') {
			$where[] = "a.vendor LIKE '%" . $filter['vendor'] . "%'";
		}
		if(isset($filter['category']) && $filter['category'] != '0') {
			$where[] = "a.category = " . $filter['category'];
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->db->query($sql);
		return $query->row()->total;
	}
	
	/*
	 * list ứng dụng theo bộ lọc
	 * $filter = array('app_name'=>'value', 'vendor'=>'value', ...)
	 */
	function allFilterApp($filter)
	{
		$sql = "SELECT * FROM apps as a";
		
		$where = array();
		
		if($filter['startdate'] != '0') {
			$startdate = $filter['startdate'];
			$enddate = $filter['enddate'];
			$sql .= ' WHERE upload_time >= ' . $startdate . ' AND upload_time <= ' . $enddate;
		}
		
		if(isset($filter['app_name']) && $filter['app_name'] != '0') {
			$where[] = "a.app_name LIKE '%" . $filter['app_name'] . "%'";
		}
		if(isset($filter['vendor']) && $filter['vendor'] != '0') {
			$where[] = "a.vendor LIKE '%" . $filter['vendor'] . "%'";
		}
			if(isset($filter['category']) && $filter['category'] != '0') {
			$where[] = "a.category = " . $filter['category'];
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'app_id',
			'is_sticky',
     		'app_name',
     		'vendor',
     		'category',
			'publish',
			'download',
			'view',
			'comment',
			'report'
     	);
     	if(isset($filter['sortby']) && in_array($filter['sortby'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sortby'];
     	} else {
     		$sql .= " ORDER BY a.app_name";
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
     			$filter['limit'] = 10;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
     	$query = $this->db->query($sql);
     	return $query->result();		
	}
	
    // Lấy danh sách ứng dụng theo category và filter
    function getAppByCategory($category=0, $filter="is_sticky", $start, $limit) {
        if ($category!=0) {
            $this->db->where('category', $category);
        }
        $this->db->where('publish', 1);
        //$this->db->order_by('is_sticky', 'DESC');
        $this->db->order_by($filter, "DESC");
        if ($filter=='is_sticky') {
            $this->db->order_by('last_update', "DESC");
        }
        $result = $this->db->get('apps', $limit, $start);
        if ($result->num_rows()>0) {
            return $result->result();
        } else
            return false;
    }

    function getTopDownload($start, $limit, $store = '0') {
        $now = time();
        $filter = $now - (7 * 24 * 60 * 60);
        $this->load->library('Cache', 'cache');
        $key = $store.$start.'topdownload';
        $topDownload = $this->cache->get($key);
        if($topDownload) {
            return $topDownload;
        } else {
            $apps = $this->db->query("SELECT COUNT(a.app_id) AS num_apps, a.app_id
                                      FROM `downloads` AS `a` WHERE `time`> ". $filter ."
                                      GROUP BY a.app_id ORDER BY num_apps DESC
                                      LIMIT ". $start .",". $limit);
            if ($apps->num_rows()>0) {
                $query = "SELECT * FROM apps WHERE ";
                foreach ($apps->result() as $app) {
                    $query .= "app_id=".$app->app_id." OR ";
                }
                $query .= " FALSE";
                $result = $this->db->query($query);
                if ($result->num_rows()>0)
                    $topDownload = $result->result();
                else {
                    $topDownload = false;
                }
            }
            else
                $topDownload = false;
            $this->cache->add($key, $topDownload, 7200);
            return $topDownload;
        }
    }
    
    function searchAppByTag($tagname, $filter, $start, $limit) {
        $tagname = $this->db->escape_str($tagname);
        $tagname = strtolower($tagname);
        $sql = "SELECT * FROM apps
                WHERE app_id IN (SELECT app_id FROM app_tagmap JOIN app_tag ON app_tagmap.tag_id=app_tag.tag_id WHERE app_tag.tag_name='$tagname')
                ORDER BY $filter LIMIT $start, $limit
        ";
        $query = $this->db->query($sql);
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function searchApp($keyword, $filter, $start, $limit) {
    	$keyword = $this->db->escape_str($keyword);
        $keyword = strtolower($keyword);
        $sql = "SELECT * FROM apps
                WHERE app_name LIKE '%$keyword%'
                OR
                app_id IN (SELECT app_id FROM app_tagmap JOIN app_tag ON app_tagmap.tag_id=app_tag.tag_id WHERE app_tag.tag_name='$keyword')
                ORDER BY $filter LIMIT $start, $limit
        ";
        $query = $this->db->query($sql);
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
        /*$this->db->like('app_name', $keyword);
        $this->db->order_by($filter, 'DESC');
        $apps = $this->db->get('apps', $limit, $start);
        if ($apps->num_rows()>0)
            return $apps->result();
        else
            return false;
         */
    }

    function upViewCount($appId) {
        $this->db->query("UPDATE apps SET view=view+1 WHERE app_id=".$appId);
    }
	/*
	 ***************************************************************************************************
	 ************************************ 		category	  ****************************************** 
	 */
	
	/*
	 * trả về thông tin category
	 */
	function getCatInfo($id)
	{
		$this->db->where('category_id', $id);
		$query = $this->db->get('category');
		if($query->num_rows() > 0)
			return $query->row();
		else
			return false;	
	}
	
	/*
	 * trả về list category
	 */
	function getListCat()
	{
		$query = $this->db->get('category');
		return $query->result();
	}
	
	/*
	 * tổng số phiên bản theo app_id
	 */
	function totalVersionByAppId($app_id)
	{
		$sql = 'SELECT count(*) AS total FROM app_version WHERE app_id='. $app_id;
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;	
	}
	
	/*
	 * tổng số link download theo app_id
	 */
	function totalLinkDownloadByAppId($app_id)
	{
		$this->db->where('app_id', $app_id);
		$query = $this->db->get('app_version');
		if($query->num_rows() > 0) {
			$results = $query->result();
			$count = 0; 
			foreach($results as $result) {
				$links = $result->link;
				$link = explode('@@', $links);
				$count += count($link);
			}
			return $count;
		} else return 0;
	}
	
	/*
	 * cộng lượt tải
	 */
	function countDownload($data, $paid = '0')
	{
        // Chèn vào bảng download
        if(!$paid) {
        	$sql = "UPDATE apps set download=download+1 WHERE app_id='" . $data['app_id'] . "'";
        	$this->db->query($sql);
			$this->db->insert('downloads', $data);
		}
	}
	
	/*
	 * trả về danh sách phiên bản
	 */
	function getListVersion($app_id)
	{
		$this->db->where('app_id', $app_id);
		$query = $this->db->get('app_version');
		if($query->num_rows() > 0)
			return $query->result();
		else
			return array();			
	}

    function getAllVersion($app_id)
	{
		$this->db->select('app_version.*, apps.app_id , app_version.version AS version');
        $this->db->where('app_version.app_id', $app_id);
        $this->db->join('apps', 'apps.app_id=app_version.app_id');
        $this->db->order_by('app_version_id', 'DESC');
		$query = $this->db->get('app_version');
		if($query->num_rows() > 0)
			return $query->result();
		else
			return array();
	}

    function getVersion($versionId)
	{
        $this->db->where('app_version_id', $versionId);
		$query = $this->db->get('app_version');
		if($query->num_rows() > 0)
			return $query->row();
		else
			return array();
	}
    
    function getLastVersion($appId) {
        $this->db->where('app_id', $appId);
        $this->db->order_by('app_version_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('app_version');
        if($query->num_rows()>0) {
            $result = $query->row();
            if(base_url() == 'http://appstore.vn/e/') {
                return $result->version;
            } else {
                $tmp = explode(' ', $result->version);
                return $tmp[0];
            }
        } else {
            return false;
        }
    }
	
	/*
	 * xóa phiên bản theo app_version_id
	 */
	function deleteVersion($app_version_id)
	{
		$this->db->where('app_version_id', $app_version_id);
		$this->db->delete('app_version');
	}
	
	/*
	 * chỉnh sửa phiên bản
	 */
	function editVersion($app_version_id, $data)
	{
		$this->db->where('app_version_id', $app_version_id);
		$this->db->update('app_version', $data);
	}

    function getLink($ticketId, $versionId, $order, $price = '0') {
        $this->db->where('ticket_id', $ticketId);
        $this->db->where('version', $versionId);
        $this->db->where('order', $order);
        $versions = $this->db->get('download_tickets');
        // Delete ticket
        /*
        $this->db->where('ticket_id', $ticketId);
        $this->db->delete('download_tickets');
         */
        // Update ticket's paid status
        $this->db->where('ticket_id', $ticketId);
        $this->db->where('version', $versionId);
        if($price) $paid = 1;
        else $paid = 0;
        $this->db->set('paid', $paid);
        $this->db->update('download_tickets');
        if ($versions->num_rows()>0) {
            $version = $versions->row();
            //$link = $version->link;
            $appId = $version->app_id;
            $this->db->where('app_version_id', $versionId);
            $this->db->where('app_id', $appId);
            $apps = $this->db->get('app_version');
            if ($apps->num_rows()>0) {
                $app = $apps->row();
                $link = explode('@@', $app->link);
                return $link[$order-1];
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    /*
     * App Comment
     */
    function getCommentByApp($appid, $start, $limit) {
        $this->db->where('app_id', $appid);
        $this->db->order_by('post_date', 'DESC');
        $this->db->limit($limit, $start);
        $comments = $this->db->get('app_comment');
        if ($comments->num_rows()>0) {
            return $comments->result();
        } else
            return false;
    }
    
    /*
     * tổng số comments trong 1 App
     */
    function totalCommentByAppId($appid)
    {
    	$sql = 'SELECT count(*) AS total FROM app_comment WHERE	app_id=' . $appid;
    	$query = $this->db->query($sql);
    	if($query->num_rows() > 0)
    		return $query->row()->total;
    	else return 0;	
    }

    function addComment($data) {
        return $this->db->insert('app_comment', $data);

    }
    
    /*
     * up comment count
     */
	function upCommentCount($appId) {
        $this->db->query("UPDATE apps SET comment=comment+1 WHERE app_id=".$appId);
    }
    
    /*
     * down comment count
     */
    function downCommentCount($appId, $number) {
    	$this->db->query("UPDATE apps SET comment=comment-$number WHERE app_id=".$appId);
    }
    
    /*
     * xóa comment
     */
    function deleteComment($id)
    {
    	$this->db->where('comment_id', $id);
    	$this->db->delete('app_comment');
    }
    
    /*
     * danh sách từ xấu trong comment
     */
    function listBadWord() {
    	$query = $this->db->get('app_commentfilter');
    	if($query->num_rows() > 0) return $query->result();
    	else return false;
    }
    
    /*
     * cập nhật từ xấu
     */
    function updateBadWord($id, $data) {
    	$this->db->where('id', $id);
    	$this->db->update('app_commentfilter', $data);
    }
    
    /*
     * thêm mới từ xấu
     */
    function addBadWord($data) {
    	$this->db->insert('app_commentfilter', $data);
    }
    
    /*
     * xóa từ xấu
     */
    function deleteBadWord($id) {
    	$this->db->where('id', $id);
    	$this->db->delete('app_commentfilter');
    }

    /*
     * App Vote
     */
    function vote($data) {
        $phase1 = $this->db->insert('app_vote', $data);
        if ($phase1) {
            return $this->db->query('UPDATE apps SET vote_score=vote_score+'. $data['rate']. ', vote=vote+1 WHERE app_id='.$data['app_id']);
        } else
            return false;
    }
    
    /*
     * get vote
     */
    function getVote($where)
    {
    	$this->db->where($where);
    	$query = $this->db->get('app_vote');
    	if($query->num_rows() > 0)
    		return $query->row();
    	else
    		return false;	
    }
    
    /*
     * check user vote
     */
    function voted($appId, $userId)
    {
    	$where = array(
    		'app_id' => $appId,
    		'user_id' => $userId
    	);
    	$this->db->where($where);
    	$query = $this->db->get('app_vote');
    	if($query->num_rows() > 0)
    		return true;
    	else 
    		return false;	
    }
    
    /*
     * report a problem
     */
    function report($data) {
    	$phase1 = $this->db->insert('app_report', $data);
        if ($phase1) {
            return $this->db->query('UPDATE apps SET report=report+1 WHERE app_id='.$data['app_id']);
        } else
            return false;
    }
    
    /*
     * update report fixed status
     */
    function updateReport($report_id, $data) {
    	$this->db->where('report_id', $report_id);
    	$this->db->update('app_report', $data);
    }
    
    /*
     * total filter report 
     */
	function totalFilterReport($filter)
	{
		$sql = "SELECT count(*) AS total FROM app_report as a";
		
		$where = array();
		
		if(isset($filter['code']) && is_numeric($filter['code']) && $filter['code'] != '-1') {
			$where[] = "a.code='" . $filter['code'] . "'";
		}
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id='" . $filter['user_id'] . "'";
		}
		if(isset($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id='" . $filter['app_id'] . "'";
		}
		if(isset($filter['fixed']) && $filter['fixed'] != '-1') {
			$where[] = "a.fixed='" . $filter['fixed'] . "'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		
		$query = $this->db->query($sql);
		return $query->row()->total;
	}	
	
	function allFilterReport($filter)
	{
		$sql = "SELECT * FROM app_report as a";
		
	$where = array();
		
		if(isset($filter['code']) && is_numeric($filter['code']) && $filter['code'] != '-1') {
			$where[] = "a.code='" . $filter['code'] . "'";
		}
		if(isset($filter['user_id']) && $filter['user_id'] != '0') {
			$where[] = "a.user_id='" . $filter['user_id'] . "'";
		}
		if(isset($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id='" . $filter['app_id'] . "'";
		}
		if(isset($filter['fixed']) && $filter['fixed'] != '-1') {
			$where[] = "a.fixed='" . $filter['fixed'] . "'";
		}
		
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sort_array =  array(
			'report_id',
			'user_id',
			'app_id',
			'fixed',
			'code'
     	);
     	if(isset($filter['sort']) && in_array($filter['sort'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sort'];
     	} else {
     		$sql .= " ORDER BY a.report_id";
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
     			$filter['limit'] = 25;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
     	$query = $this->db->query($sql);
     	return $query->result();		
	}
    
    
    /*
     * check app exist by name or applelink
     */
    function check_app($app_name, $applelink) 
    {
    	$name = false;
    	$link = false;
    	
    	$this->db->where('app_name', $app_name);
    	$query = $this->db->get('apps');
    	if($query->num_rows() > 0)
    		$name = true;
		
    	$query->free_result();
		
    	$this->db->where('applelink', $applelink);
		$query = $this->db->get('apps');
		if($query->num_rows() > 0)
			$link = true;
		if($name && $link) 
			return 1;
		if($name && !$link)
			return 2;
		if(!$name && $link)
			return 3;
		return 0;	    		
    }
    
	/*
	 * dịch description
	 */
    function translateDesc($appid) 
    {
    	$app = $this->getInfo($appid);
    	
    	$url = $app->applelink;
    	
    	if(!$url)
    		return "translated";
    	$CI =& get_instance();
    	$CI->load->library('crawler');
    	
    	if($CI->crawler->getDomain($url) == "itunes.apple.com" || strpos($url, "itunes.apple.com") !== false) {
			$desc = $CI->crawler->iOS($url, $type = 'description');
		} else if($CI->crawler->getDomain($url) == "market.android.com") {
			$desc = $CI->crawler->android($url, $type = 'description');
		} else if($CI->crawler->getDomain($url) == "store.ovi.com") {
			$desc = $CI->crawler->java($url, $type = 'description');
		} else if($CI->crawler->getDomain($url) == "appworld.blackberry.com") {
			$desc = $CI->crawler->blackberry($url, $type = 'description');
		} else {
			return 'translated';
		}
		
		$html = str_get_html($app->description);
		//return var_dump(strip_tags($html->innertext));
		//return str_replace($find, '_test_', $html);
		//return $html->innertext;
		//return var_dump($CI->crawler->google_translate('AIzaSyCnlgXPUCJW2zZCqmwUVp7xdfTyzU3OM90', $desc, 'en', 'vi'));
		return $CI->crawler->translate(trim(strip_tags($html->innertext)));	
    }
    
    // Lấy giá của app
    function getTymPrice($appId) {
    	$price = array();
    	$appInfo = $this->getInfo($appId);
    	$appPrice = $appInfo->tym_price;
        $priority = $appInfo->priority_price;
    	if ($priority!=0) {
    		$tymType = $appInfo->tym_type;
    		// Tính giá theo app
    		$method = $appInfo->method;
    		if ($method=='all') {
    			// Toàn bộ
    			$price['price'] = $appPrice;
    			$price['type'] = $tymType;
    		} elseif ($method=='hit') {
    			// Chẵn lẻ
    			$downloads = $appInfo->download;
    			if ($downloads%2 == 0) {
    				$price['price'] = 0;
    				$price['type'] = 't1';
    			} else {
    				$price['price'] = $appPrice;
    				$price['type'] = $tymType;
    			}
    		}
    	} else {
    		// Tính giá theo category
    		$CI =& get_instance();
    		$CI->load->model('category_model');
			$catInfo = $CI->category_model->getInfo($appInfo->category);
            if($catInfo) {
                if(!isset($catInfo->price)) $catInfo->price = 0;
                $catPrice = $catInfo->price;
                if(!isset($catInfo->tym_type)) $catInfo->tym_type = 't1';
                $tymType = $catInfo->tym_type;
                $method = $catInfo->method;
                if ($method=='all') {
                    // Toàn bộ
                    $price['price'] = $catPrice;
                    $price['type'] = $tymType;
                } elseif ($method=='hit') {
                    // Chẵn lẻ
                    $downloads = $appInfo->download;
                    if ($downloads%2 == 0) {
                        $price['price'] = 0;
                        $price['type'] = 't1';
                    } else {
                        $price['price'] = $catPrice;
                        $price['type'] = $tymType;
                    }
                }
            } else {
                $catPrice = 0;
                $price['price'] = 0;
                $price['tym_type'] = 't1';
            }
                        
            // Áp dụng theo gói
            $CI->load->model('setting_model');
            $package = $CI->setting_model->getValueByKey('package');
            if(!$catPrice && $package) {
                $price['price'] = 0;
                $price['type'] = 't1';
            }
    	}
        if(!$price['type']) $price['type'] = 't2';
    	return $price;
    }

    function addTickets($userId, $appId, $versionId, $links) {
        $this->load->helper('string');
        $tickets = array();
        // Kiểm tra ticket hiện tại
        $this->db->where('user_id', $userId);
        $this->db->where('app_id', $appId);
        $this->db->where('version', $versionId);
        $currentTickets = $this->db->get('download_tickets');
        if ($currentTickets->num_rows()>0) {
            $tickets = $currentTickets->result_array();
        } else {
            $ticketID = random_string('alnum', 10);
            $order = 1;
            foreach ($links as $link) {
                $ticket['ticket_id'] = $ticketID;
                $ticket['user_id'] = $userId;
                $ticket['app_id'] = $appId;
                $ticket['version'] = $versionId;
                $ticket['link'] = $link;
                $ticket['order'] = $order++;
                $ticket['paid'] = 0;
                $this->db->insert('download_tickets', $ticket);
                $tickets[] = $ticket;
            }
        }
        return $tickets;
    }

    function getTicket($ticketId) {
        $this->db->where('ticket_id', $ticketId);
        $ticket = $this->db->get('download_tickets');
        if ($ticket->num_rows()>0) {
            return $ticket->row();
        } else return null;
    }
    
    // xu ly tags
    function getTagByApp($appId) {
        $sql = "SELECT a.tag_name,a.tag_id
                FROM app_tag AS a JOIN app_tagmap AS b
                ON a.tag_id=b.tag_id
                WHERE b.app_id=$appId
               ";
        $query = $this->db->query($sql);
        if($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function checkTag($tagName) {
        $this->db->where('tag_name', strtolower($tagName));
        $query = $this->db->get('app_tag');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function addTag($tagName) {
        $data = array('tag_name' => $tagName);
        $this->db->insert('app_tag', $data);
        return $this->db->insert_id();
    }
    
    function checkTagMap($tagId, $appId) {
        $this->db->where('tag_id', $tagId);
        $this->db->where('app_id', $appId);
        $query = $this->db->get('app_tagmap');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function addTagMap($tagId, $appId) {
        $data = array(
            'app_id' => $appId,
            'tag_id' => $tagId
        );
        $this->db->insert('app_tagmap', $data);
    }
    
    function deleteTagMap($tagId, $appId) {
        $this->db->where('app_id', $appId);
        $this->db->where('tag_id', $tagId);
        $this->db->delete('app_tagmap');
    }

	function countLinkDownload($appId) {
		$sql = "SELECT count(*) AS total FROM app_version WHERE app_id=$appId";
		$query = $this->db->query($sql);
		if($query->num_rows()>0) {
			return $query->row()->total;
		} else {
		   	return 0;
		}
	}
    
    function relatedApp($appId, $categoryId) {
        $this->load->library('Cache', 'cache');
        $key = $this->store.'relatedapp'.$appId;
        $cachedApps = $this->cache->get($key);
        if($cachedApps) {
            return $cachedApps;
        } else {
            $apps = array();
            $tag = $this->getTagByAppId($appId);

            $order_array = array(
                'app_id',
                'app_name',
                'download',
                'view',
                'vote',
                'upload_time',
                'uploader'
            );
            $desc_array = array('DESC', 'ASC');
            $num = count($order_array);
            $order = $order_array[time()%$num];
            $by = $desc_array[$num%2];

            if($tag) {
                $tagId = $tag->tag_id;            
                $sql = "SELECT apps.app_id, apps.app_name, apps.image
                        FROM app_tagmap
                        JOIN apps
                        ON apps.app_id=app_tagmap.app_id
                        WHERE app_tagmap.app_id!=$appId AND app_tagmap.tag_id=$tagId
                        ORDER BY $order $by
                        LIMIT 3
                ";
                $query = $this->db->query($sql);
                if($query->num_rows()>0) {
                    $apps = $query->result();
                }            
            }
            // chong trung apps
            $appArray = array();
            $appStr = "";
            if($apps) {
                foreach($apps as $app) $appArray[] = $app->app_id;
                $appStr = implode(',', $appArray);
            }
            // by category
            $num = count($apps);
            // so app con lai theo category
            $remain = 9-$num;
            // lay random id app ngau nhien theo category
            $sql = "SELECT FLOOR(RAND() * COUNT(*)) AS `num` FROM `apps` WHERE category=$categoryId";
            $query = $this->db->query($sql);
            $result = $query->row();
            $targetId = $result->num;

            // lay app co app_id lon hon id ngau nhien
            $sql = "SELECT app_id, app_name, image 
                    FROM apps 
                    WHERE category=$categoryId AND app_id>=$appId
                    ";
            if($appStr) {
                $sql.= " AND app_id not in ($appStr)";
            }

            $sql.= " ORDER BY $order $by 
                    LIMIT $remain
            ";
            $query = $this->db->query($sql);
            if($query->num_rows()>0) {
                $result = $query->result();
                $apps = array_merge($apps, $result);
            }
            // neu chua du $remain app
            if($query->num_rows() != $remain) {
                // so app can lay them
                $newRemain = $remain - $query->num_rows();
                // lay app co app_id nho hon id ngau nhien
                $sql = "SELECT app_id, app_name, image 
                    FROM apps 
                    WHERE category=$categoryId AND app_id<$appId
                    ";
                if($appStr) {
                    $sql.= " AND app_id not in ($appStr)";
                }

                $sql.= " ORDER BY $order $by 
                        LIMIT $remain
                ";
                $query = $this->db->query($sql);
                if($query->num_rows()>0) {
                    $result = $query->result();
                    $apps = array_merge($apps, $result);
                }
            }
            //save in cache
            $this->cache->add($key, $apps, 86400);
            return $apps;
        }
    }
    
    function getTagByAppId($appId) {
        $this->db->where('app_id', $appId);
        $query = $this->db->get('app_tagmap');
        if($query->num_rows()>0) {
            return $query->first_row();
        } else {
            return false;
        }
    }
    
    /*
     * khuyen mai apps
     */

    /*
	 * tổng số ứng dụng theo bộ lọc
	 * $filter = array('app_name'=>'value', 'price'=>'value', ...)
	 */
	function totalPromotionApp($filter)
	{
		$sql = "SELECT count(*) AS total FROM apps as a";
		
		$where = array();
		
		if(isset($filter['app_id']) && is_numeric($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id='" . $filter['app_id'] . "'";
		}
		if(isset($filter['app_name']) && $filter['app_name'] != '0') {
			$where[] = "a.app_name LIKE '%" . $filter['app_name'] . "%'";
		}
		if(isset($filter['category']) && $filter['category'] != '0') {
			$where[] = "a.category='" . $filter['category'] . "'";
		}
		if(isset($filter['price']) && $filter['price'] != '0') {
			$prices = explode('-', $filter['price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.tym_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.tym_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.tym_price <= " . $prices[1];
		}
        if(isset($filter['p_price']) && $filter['p_price'] != '0') {
			$prices = explode('-', $filter['p_price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.promo_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.promo_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.promo_price <= " . $prices[1];
		}
		if(isset($filter['tym']) && $filter['tym'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym'] . "'";
		}
		if(isset($filter['startdate']) && $filter['startdate'] != '0') {
			$where[] = "a.promo_end>=" . $filter['startdate'] . " AND a.promo_end<=" . $filter['enddate'];
		}
        if(isset($filter['enable']) && $filter['enable'] != '-1') {
			$where[] = "a.promo_enable='" . $filter['enable'] . "'";
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		$query = $this->db->query($sql);
		return $query->row()->total;
	}	
	
	function allPromotionApp($filter)
	{
		$sql = "SELECT * FROM apps as a";
		
		$where = array();
		
		if(isset($filter['app_id']) && is_numeric($filter['app_id']) && $filter['app_id'] != '0') {
			$where[] = "a.app_id='" . $filter['app_id'] . "'";
		}
		if(isset($filter['app_name']) && $filter['app_name'] != '0') {
			$where[] = "a.app_name LIKE '%" . $filter['app_name'] . "%'";
		}
		if(isset($filter['category']) && $filter['category'] != '0') {
			$where[] = "a.category='" . $filter['category'] . "'";
		}
		if(isset($filter['price']) && $filter['price'] != '0') {
			$prices = explode('-', $filter['price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.tym_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.tym_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.tym_price <= " . $prices[1];
		}
        if(isset($filter['p_price']) && $filter['p_price'] != '0') {
			$prices = explode('-', $filter['p_price']);
			if($prices[0] != '' && isset($prices[1])) $where[] = "a.promo_price >= " . $prices[0];
			if($prices[0] != '' && !isset($prices[1])) $where[] = "a.promo_price = " . $prices[0];
			if(isset($prices[1]) && $prices[1] != '') $where[] = "a.promo_price <= " . $prices[1];
		}
		if(isset($filter['tym']) && $filter['tym'] != '0') {
			$where[] = "a.tym_type='" . $filter['tym'] . "'";
		}
		if(isset($filter['startdate']) && $filter['startdate'] != '0') {
			$where[] = "a.promo_end>=" . $filter['startdate'] . " AND a.promo_end<=" . $filter['enddate'];
		}
        if(isset($filter['enable']) && $filter['enable'] != '-1') {
			$where[] = "a.promo_enable='" . $filter['enable'] . "'";
		}
	
		if($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
        }

		$sort_array =  array(
			'app_id',
			'category',
     	);
     	if(isset($filter['sort']) && in_array($filter['sort'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sort'];
     	} else {
     		$sql .= " ORDER BY a.app_id";
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
     			$filter['limit'] = 10;
     		}
     		$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
     	}
     	
     	//var_dump($sql);	//for debug
     	$query = $this->db->query($sql);
        $result = array();
        if($query->result()) {
            foreach($query->result() as $app) {
                if(!$app->priority_price) {
                    $price = $this->getTymPrice($app->app_id);
                    $app->tym_type = $price['type'];
                    $app->tym_price = $price['price'];
                }
                $result[] = $app;
            }
        }
     	return $result;		
	}
    
    function addPromotionLog($data) {
        $this->db->insert('app_promotion_log', $data);
    }
    
    function getPromotionApp($start, $limit, $filter) {
        $this->db->where('promo_enable', 1);
        $this->db->order_by($filter, 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get('apps');
        if($query->num_rows()>0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
