<?php

class Other_Model_DbTable_DbOtherinformation extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_otherinformation';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addOtherinformation($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'description'=>$_data['note'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId(),
    			);
    	$this->insert($_arr);//insert data
    }
    function getAllOtherinformation(){
    	$db = $this->getAdapter();
    	$sql=" SELECT id,title,description,`status` FROM ldc_otherinformation WHERE `status`=1 ";
    	return $db->fetchAll($sql);
    }
    function getOtherById($id){
    	$db = $this->getAdapter();
    	$sql="select id,title,description,status from $this->_name where id =$id";
    	return $db->fetchRow($sql);
    }
    function updateOtherinformation($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'description'=>$_data['note'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    function getAllInfomation(){
    	$db = $this->getAdapter();
    	$sql=" SELECT id,title,description,`status` FROM ldc_otherinformation WHERE `status`=1 ";
    	return $db->fetchAll($sql);
    }
}  
	  

