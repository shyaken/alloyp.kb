<?php
class Survey_model extends Model
{
	function Survey_model()
	{
		parent::__construct();
	}
	
	/*
	 * list danh sách thăm dò
	 */
	function listAll($limit, $start)
	{
		$this->db->limit($limit, $start);
		$query = $this->db->get('survey');
		if($query->num_rows() > 0)
			return $query->result();
		else return false;	
	}
	
	/*
	 * tổng số cuộc thăm dò
	 */
	function totalSurvey()
	{
		$sql = "SELECT count(*) as total FROM survey";
		$query = $this->db->query($sql);
		return $query->row()->total;
	}
	
	/*
	 * lấy thông tin 1 thăm dò
	 */
	function getInfo($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('survey');
		if($query->num_rows() > 0) {
			return $query->row();
		} else return false;
	}
	
	/*
	 * thêm mới thăm dò
	 */
	function add($data)
	{
		$this->db->insert('survey', $data);
	}
	
	/*
	 * chỉnh sửa thăm dò
	 */
	function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('survey', $data);
	}
	
	/*
	 * xóa thăm dò
	 */
	function delete($id) 
	{
		$this->db->where('id', $id);
		$this->db->delete('survey');
	}
	
	/*
	 * cộng phiếu vào thăm dò
	 */
	function countValue($id, $field)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('survey');
		$survey = $query->row();
		$value = $survey->$field;
		
		$value++;
		$query->free_result();
		
		$this->db->where('id', $id);
		$this->db->set($field, $value);
		$this->db->update('survey');
	}
}