<?php
class Contact extends CI_Model {
    // table name
    private $tbl_contacts= 'tbl_contacts';
 
    function __construct(){
      parent::__construct();
    }
    // get number of contact in database
    function count_all(){
        return $this->db->count_all($this->tbl_contacts);
    }
    // get contact with pagings
    function get_list(){
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_contacts);
    }
    // get contact with pagings
    function get_send_list(){
        $this->db->where('status', 1);
        return $this->db->get($this->tbl_contacts);
    }
    // get contact by id
    function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_contacts);
    }
    // add new contact
    function save($contact){
        $this->db->insert($this->tbl_contacts, $contact);
        return $this->db->insert_id();
    }
    // update contact by id
    function update($id, $contact){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_contacts, $contact);
    }
    // delete contact by phoneNumber
    function delete($phoneNumber){
        $this->db->where('telephone', $phoneNumber);
        $this->db->delete($this->tbl_contacts);
    }
}
?>
