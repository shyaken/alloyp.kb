<?php
class Textad_model extends Model
{
	function Textad_model()
	{
		parent::__construct();
	}
	
	/*
	 * list all
	 */
	function listAll()
	{
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get('textad');
		return $query->result();
	}
	
	/*
	 * add
	 */
	function add($data)
	{
		$this->db->insert('textad', $data);
	}
	
	/*
	 * edit
	 */
	function edit($id, $data) 
	{
		$this->db->where('id', $id);
		$this->db->update('textad', $data);	
	}
	
	/*
	 * get Text
	 */
	function getText($type)
	{
		$this->db->where('type', $type);
		$query = $this->db->get('textad');
		return $query->row();
	}
	
	/*
	 * delete
	 */
	function delete()
	{
		$this->db->where('id > ', '0');
		$this->db->delete('textad');
	}
}