<?php

class Other_Model_DbTable_DbGuestbook extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_guestbook';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addabout($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'description'=>$_data['note'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId(),
    			'type'=>2,
                        'date'=>date("Y-m-d"),
    			);
    	$this->insert($_arr);//insert data
    }
    function getAllGuestbook(){
    	$db = $this->getAdapter();
    	$sql=" SELECT id ,`name`,`subject`,email,website,`comment`,`date`,`status` FROM ldc_guestbook WHERE name!='' ORDER BY id DESC";
    	return $db->fetchAll($sql);
    }
    function getGuestbookById($id){
    	$db = $this->getAdapter();
    	$sql="SELECT id ,`name`,`subject`,email,website,`comment`,`date`,`status` FROM ldc_guestbook WHERE id=$id";
    	return $db->fetchRow($sql);
    }
    function updateGuestbook($_data){
    	$_arr = array(

    			'name'=>$_data['name'],
    			'subject'=>$_data['subject'],
    			'comment'=>$_data['comment'],
                        'email'=>$_data['email'],
                        'website'=>$_data['website'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId(),
    			//'type'=>2,
                        'date'=> $_data["date"],
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    function deleteGuestbook($id){
    	$db = $this->getAdapter();
    	$sql = "DELETE FROM `ldc_guestbook` WHERE `id`=$id";
    	$db->query($sql);
    }
}  
	  

