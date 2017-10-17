<?php

class Other_Model_DbTable_DbPlayerList extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_playerlist';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addPlayerList($_data){
    	//print_r($_data);exit();
    	$_arr = array(
    			'title'=>$_data['title'],
    			'descript'=>$_data['description'],
    			'date'=>date("Y-m-d"),
    			'user_id'=>$this->getUserId(),
    			'status'=>$_data['status'],
    			);
    	$this->insert($_arr);
    }
    public function updatePlayerList($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'descript'=>$_data['description'],
    			'date'=>date("Y-m-d"),
    			'user_id'=>$this->getUserId(),
    			'status'=>$_data['status'],
    			);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    	
	function getAllPlayerList($search=null){
    	$sql="SELECT id,title,`status` FROM ldc_playerlist WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $this->getAdapter()->fetchAll($sql.$order);
    }
    function getPlayerListById($id){
    	$id = addslashes($id);
    	$sql="SELECT id,title,descript,`status` FROM ldc_playerlist WHERE `id`=$id";
    	return $this->getAdapter()->fetchRow($sql);
    }
    public function getGlobalDb($sql)
    {
    	$db=$this->getAdapter();
    	$row=$db->fetchAll($sql);
    	if(!$row) return NULL;
    	return $row;
    }
    static function getAllPlayerListNameForMenu(){
    	$db = new Other_Model_DbTable_DbPlayerList();
    	$sql="SELECT id,title FROM ldc_playerlist WHERE `status`=1 AND title!='' ";
    	$order=' ORDER BY id DESC';
    	$row = $db->getGlobalDb($sql.$order);
    	return $row;
    }

}  
	  

