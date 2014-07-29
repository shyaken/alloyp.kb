<?php
class Category_model extends Model
{
	function Category_model()
	{
		parent::__construct();
	}
	
	/*
	 * danh sách category
	 */
	function listAll()
	{
		$this->db->order_by('order', 'ASC');
		$query = $this->db->get('category');
		return $query->result();	
	}

    function countAllNew() {
        $this->db->order_by('order', 'ASC');
		$categories = $this->db->get('category');
        $now = time();
        $period = $now - (3 * 24 * 60 * 60);
        $newApps = array();
		foreach ($categories->result() as $category) {
            $where = "category=".$category->category_id." AND (upload_time > ".$period." OR last_update > ".$period.")";
            $this->db->where($where, NULL, false);
            $apps = $this->db->count_all_results('apps');
            $newApps[$category->category_id] = $apps;
        }
        return $newApps;
    }
	
	/*
	 * tổng số category
	 */
	function totalCat()
	{
		$sql = 'SELECT COUNT(*) AS total FROM category';
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
			return $query->row()->total;
		else return 0;	
	}
	
	/*
	 * thêm mới category
	 */
	function add($data) 
	{
		$this->db->insert('category', $data);
	}
	
	/*
	 * cập nhật category
	 */
	function update($id, $data)
	{
		$this->db->where('category_id', $id);
		$this->db->update('category', $data);
	}
	
	/*
	 * xóa category
	 */
	function delete($id)
	{
		$this->db->where('category_id', $id);
		$this->db->delete('category');
	}
	
	/*
	 * bật category
	 */
	function publish($id)
	{
		$this->db->where('category_id', $id);
		$this->db->set('publish', 1);
		$this->db->update('category');	
	}
	
	/*
	 * tắt category
	 */
	function unPublish($id)
	{
		$this->db->where('category_id', $id);
		$this->db->set('publish', 0);
		$this->db->update('category');	
	}	
	
	/*
	 * get info
	 */
	function getInfo($id)
	{
		$this->db->where('category_id', $id);
		$query = $this->db->get('category');
        if($query->num_rows()>0) {
            return $query->row();
        } else {
            $result = new stdClass();
        }
	}
	
	/*
	 * save order
	 */
	function saveOrder($id, $order)
	{
		$this->db->where('category_id', $id);
		$this->db->set('order', $order);
		$this->db->update('category');
	}
        
        /*
         * update category image
         */
        function updateImage()
        {
            $defaul = '/uploads/category/default.jpg';
            
            $query = $this->db->get('category');
            $categories = $query->result();
           
            foreach($categories as $cat) {
                $query->free_result();
                $this->db->where('category', $cat->category_id);
                $this->db->order_by('app_id', 'DESC');
                $query = $this->db->get('apps');
                if($query->num_rows() > 0) {
                    $app = $query->first_row();
                    $image = $app->image;
                } else {
                    $image = $defaul;
                }
                $query->free_result();
                $this->db->where('category_id', $cat->category_id);
                $this->db->set('image', $image);
                $this->db->update('category');
            }
        }
        
        /*
         * check category name
         */
        function checkCategoryName($name)
        {
        	$this->db->where('category_name', $name);
        	$query = $this->db->get('category');
        	if($query->num_rows() > 0)
        		return $query->row()->category_id;
        	else return false;	
        }
        
        /*
         * all category filter
         */
	function allSettingCategory($filter) {
		$sql = "SELECT * FROM category as a";
		
		$where = array();
		
		if(isset($filter['category_id']) && is_numeric($filter['category_id']) && $filter['category_id'] != '0') {
			$where[] = "a.category_id=" . $filter['category_id'];
		}
		if(isset($filter['category_name']) && $filter['category_name'] != '0') {
			$where[] = "a.category_name LIKE '%" . $filter['category_name'] . "%'";
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
			'category_id',
     	);
     	if(isset($filter['sort']) && in_array($filter['sort'], $sort_array)) {
     		$sql .= " ORDER BY a." . $filter['sort'];
     	} else {
     		$sql .= " ORDER BY a.category_id";
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
	
}