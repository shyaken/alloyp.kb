<?php
class Payment_model extends Model {
	private $dbuser;
	
	function Payment_model() {
		parent::__construct();
		$this->dbuser = $this->load->database('dbuser', TRUE);
	}
	
	/*
	 * tạo transaction_id tạm cho nạp tiền bằng thẻ ĐT
	 */
	function temporaryCardTransaction($data) {
		$this->dbuser->insert('transaction', $data);
		return $this->dbuser->insert_id();
	}
    
    /*
     * lấy thông tin transaction
     */
    function getInfo($id) {
        $this->dbuser->where('id', $id);
        $query = $this->dbuser->get('transaction');
        if($query->num_rows() > 0)
            return $query->row();
        else return false;
    }
	
	/*
	 * cập nhật giao dịch
	 */
	function updateTransaction($id, $data) {
		$this->dbuser->where('id', $id);
		$this->dbuser->update('transaction', $data);
	}
	
	/*
	 * lưu lại giao dịch nạp thẻ
	 */
	function addSMSTransaction($data) {
		$this->dbuser->insert('transaction', $data);
		return $this->dbuser->insert_id();
	}
}