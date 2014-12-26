<?php
class Keyword extends CI_Model {
    // table name
    private $tbl_keyword= 'tbl_keywords';
 
    function __construct(){
      parent::__construct();
    }
    // get number of keyword in database
    function count_all(){
        return $this->db->count_all($this->tbl_keyword);
    }
    // get keyword with pagings
    function get_list(){
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_keyword);
    }
    // get keyword by id
    function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_keyword);
    }
    // add new keyword
    function save($keyword){
        $this->db->insert($this->tbl_keyword, $keyword);
        return $this->db->insert_id();
    }
    // update keyword by id
    function update($id, $keyword){
        $this->db->where('id', $id);
        $this->db->update($this->tbl_keyword, $keyword);
    }
    // delete keyword by id
    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->tbl_keyword);
    }
}
?>
