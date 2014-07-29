<?php
class Import_model extends Model
{
	function Import_model()
	{
		parent::__construct();
	}		
	
	function insertApp()
	{
		for($i=0; $i<70; $i++) {
		echo "Importing ".$i."<br>";
		
		$start = $i * 100;
		$query = $this->db->get('import_app', 100, $start);
		$apps = $query->result();
		$count = 0;
		
		foreach($apps as $app)
		{
			$screenshot = $this->getScreenshot($app->id);
			
			$params = array(
				'app_name' => $app->title,
				'vendor' => $app->company,
				'vendor_site' => '#',
				'description' => $app->description,
				'size' => $app->size,
				'requirement' => $app->requirement,
				'applelink' => $app->applelink,
				'download' => $app->downloads,
				'comment' => 0,
				'vote' => $app->score,
				'image' => '/uploads/old_version/' . $app->icon,
				'screenshot' => $screenshot,
				'category' => $app->catid,
				'view' => $app->hits,
				'report' => 0,
				'is_sticky' => 0,
				'publish' => $app->published,
				'upload_time' => microtime(true),
				'last_update' => microtime(true)
			);
			// insert app
			$this->db->insert('apps', $params);
			$insert_id = $this->db->insert_id();

			$link = $app->downloadlink;
			if($app->downloadlink1 != '') $link .= '@@' . $app->downloadlink1;

			$ver_params = array(
				'app_id' => $insert_id,
				'version' => $app->version, 
				'link' => $link
			);
			
			$this->db->insert('app_version', $ver_params);	
			$count++;
		}}
		echo $count;
	}
	
	function getScreenshot($app_id)
	{
		$this->db->where('appid', $app_id);
		$query = $this->db->get('import_img');
		if($query->num_rows() > 0) {
			$imgs = $query->result();
			$cur = '/uploads/old_version/';
			
			$txt = array();
			foreach($imgs as $img) {
				if(substr($img->images, 0, 4) == 'http')
					$txt[] = $img->images;
				else
					$txt[] = $cur . $img->images;	
			}
			
			$result = implode('@@', $txt);
			return $result;
			
		} else return '/uploads/old_version/no_screenshot.jpg';
	}
	
	function insertCat()
	{
		$query = $this->db->get('import_cat');
		$cats = $query->result();
		
		foreach($cats as $cat) {
			$params = array(
				'category_id' => $cat->id,
				'category_name' => $cat->title,
				'method' => 'hit',
				'order' => $cat->ordering,
				'price' => 10000,
				'publish' => $cat->published,
				'image' => 'uploads/cat/default.jpg'
			);
			$this->db->insert('category', $params);			
		}
	}
	
	function updateImageCat()
	{
		$query = $this->db->get('category');
		$cats = $query->result();
		$query->free_result();
		foreach($cats as $cat) {
			$query->free_result();
			$this->db->where('category', $cat->category_id);
			$this->db->order_by('app_id', 'DESC');
			$query = $this->db->get('apps');
			if($query->num_rows() > 0) {
				$app = $query->row();
				$param = array('image'=>$app->image);
				var_dump($param);
				$this->db->where('category_id', $cat->category_id);
				$this->db->update('category', $param);
			}
		}
		
	}
	
	/*
	function testdb()
	{
		$this->dbx = $this->load->database('dbx', TRUE);  
		$arrays = array("id"=>"1");
		$this->dbx->insert("aaa", $arrays);
	}
	*/
}