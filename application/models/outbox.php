<?php
class Outbox extends CI_Model {
    // table name
    private $tbl_outbox= 'tbl_outbox';
 
    function __construct(){
      parent::__construct();
    }
    // get number of outbox in database
    function count_all(){
        return $this->db->count_all($this->tbl_outbox);
    }
    // get number of outbox in database
    function count_it_all($start, $end){
        $this->db->where(array('status' => 'Success', 'DATE(datetime) >=' => $start, 'DATE(datetime) <=' => $end));
        return $this->db->count_all_results($this->tbl_outbox);
    }
    function count_for_date($date){
	$this->db->where(array('DATE(datetime)' => $date, 'status' => 'Success'));
        return $this->db->count_all_results($this->tbl_outbox);
    }
    function count_for_duration($from, $to){
	$this->db->where(array('DATE(datetime)>=' => $from, 'DATE(datetime)<=' => $from, 'status' => 'Success'));
        return $this->db->count_all_results($this->tbl_outbox);
    }
    // get outbox with paging
    function get_list($limit = 10, $offset = 0){
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_outbox, $limit, $offset);
    }
    // get outbox by id
    function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_outbox);
    }
    // add new outbox
    function save($outbox){
        $this->db->insert($this->tbl_outbox, $outbox);
        return $this->db->insert_id();
    }
    // update outbox by id
    function update($id, $outbox){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_outbox, $outbox);
    }
    // delete outbox by id
    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->tbl_outbox);
    }
}
?>
