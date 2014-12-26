<?php
class Subscription extends CI_Model {
    // table name
    private $tbl_subscriptions= 'tbl_subscriptions';
 
    function __construct(){
      parent::__construct();
    }
    // get number of new subscriptions in database
    function get_new_subscriptions($date){	
	$this->db->where(array('subscribe' => 1, 'DATE(datetime) >=' => $date));
        return $this->db->count_all_results($this->tbl_subscriptions);
    }
    function count_for_date($date, $option = true){
	if($option == true)
		$this->db->where(array('DATE(datetime)<=' => $date, 'subscribe' => 1));
	else
		$this->db->where(array('DATE(datetime)<=' => $date, 'subscribe' => 0));
        return $this->db->count_all_results($this->tbl_subscriptions);
    }
    function count_for_duration($from, $to, $option = true){
	if($option == true)
		$this->db->where(array('DATE(datetime)<=' => $from, 'DATE(datetime)<=' => $to, 'subscribe' => 1));
	else
		$this->db->where(array('DATE(datetime)<=' => $from, 'DATE(datetime)<=' => $to, 'subscribe' => 0));
        return $this->db->count_all_results($this->tbl_subscriptions);
    }
    // get number of unsubscriptions in database
    function get_unsubscriptions($date){	
	$this->db->where(array('subscribe' => 0, 'DATE(datetime) >=' => $date));
        return $this->db->count_all_results($this->tbl_subscriptions);
    }
    // get subscription with pagings
    function get_list($limit = 10, $offset = 0){
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_subscriptions, $limit, $offset);
    }
    function get_start_date(){
	$this->db->order_by('datetime', 'asc');
        return $this->db->get($this->tbl_subscriptions, 1)->result()[0]->datetime;
    }
    // get subscription by id
    function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_subscriptions);
    }
    // add new subscription
    function save($subscription){
        $this->db->insert($this->tbl_subscriptions, $subscription);
        return $this->db->insert_id();
    }
    // update subscription by id
    function update($id, $subscription){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_subscriptions, $subscription);
    }
    // delete subscription by id
    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->tbl_subscriptions);
    }
}
?>
