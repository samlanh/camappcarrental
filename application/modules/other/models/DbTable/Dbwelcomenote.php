<?php

class Other_Model_DbTable_Dbwelcomenote extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_manytable';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addwelcomenote($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'description'=>$_data['note'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId(),
    			'type'=>1
    			);
    	$this->insert($_arr);//insert data
    }
	function getAllWelcome(){
    	$db = $this->getAdapter();
    	$sql="select id,title,description,
        (SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_manytable.`status`  ) AS status
		from ldc_manytable where type=1";
    	return $db->fetchAll($sql);
	}
	function getWalcomeById($id){
		$db = $this->getAdapter();
		$sql="select id,title,description,status from ldc_manytable where id =$id";
		return $db->fetchRow($sql);
	}
	function updatewelcomecont($_data){
		$_arr = array(
				'title'=>$_data['title'],
				'description'=>$_data['note'],
				'status'=>$_data['status'],
				'user_id'=>$this->getUserId()
		);
		$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
		$this->update($_arr, $where);
	}
}  
	  

