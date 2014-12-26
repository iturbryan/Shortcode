<?php
class Inbox extends CI_Model {
    // table name
    private $tbl_inbox= 'tbl_inbox';
 
    function __construct(){
      parent::__construct();
    }
    // get number of inbox in database
    function count_all(){
        return $this->db->count_all($this->tbl_inbox);
    }
    // get inbox with pagings
    function get_list($limit = 10, $offset = 0){
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_inbox, $limit, $offset);
    }
    // get inbox by id
    function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_inbox);
    }
    // add new inbox
    function save($inbox){
        $this->db->insert($this->tbl_inbox, $inbox);
        return $this->db->insert_id();
    }
    // update inbox by id
    function update($id, $inbox){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_inbox, $inbox);
    }
    // delete inbox by id
    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->tbl_inbox);
    }
}
?>
