<?php

class Vehicle_Model_DbTable_DbType extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_type';
    function addType($data){
    	$_arr = array(
    			'type'=>$data['type'],
    			'status'=>$data['status'],
    			);
    	$this->insert($_arr);//insert data
    }
    public function updateType($data){
    	
    	$_arr = array(
    			'type'=>$data['type'],
    			'status'=>$data['status'],
    			);
    	$where='id='.$data['id'];
    	$this->update($_arr, $where);
    }
    	
    function getAllType($search=null){
    	$db = $this->getAdapter();
    	$sql="SELECT id,`type`,status
    	FROM $this->_name WHERE 1";
    	$order=' ORDER BY id DESC';
        return $db->fetchAll($sql.$order);
    }
    
 function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }

}  
	  

