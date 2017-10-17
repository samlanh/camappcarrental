<?php

class Other_Model_DbTable_DbService extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_service';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addservice($_data){
    	$_arr = array(
    			'title'=>$_data['service_title'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId()
    			);
    	$this->insert($_arr);//insert data
    }
    function Updateservice($_data){
    	$_arr = array(
    			'title'=>$_data['service_title'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    function getAllService($search=null){
    	$db = $this->getAdapter();
    	$sql = "SELECT id ,title,
    	(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_service.`status`  ) AS status
    	FROM `ldc_service` WHERE 1
    	";
    	 
    	//     	if($search['status_search']>-1){
    	//     		$where.= " AND b.status = ".$search['status_search'];
    	//     	}
    	$where="";
    	 
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=addslashes($search['adv_search']);
    		$s_where[]=" title LIKE '%{$s_search}%'";
    		$s_where[]=" answer LIKE '%{$s_search}%'";
    		$s_where[]=" b.displayby LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ',$s_where).')';
    	}
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$where.$order);
    }
 function getServiceById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM
    	$this->_name ";
    	$where = " WHERE `id`= $id" ;
   		return $db->fetchRow($sql.$where);
    }
}  
	  

