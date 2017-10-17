<?php

class Vehicle_Model_DbTable_DbEngine extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_engince';
    function addEngine($data){
    	//print_r($data);exit();
    	$_arr = array(
    			'capacity'=>$data['capacity'],
    			'status'=>1,
    			);
    	$this->insert($_arr);//insert data
    }
    public function updateEngince($data){
    	$_arr = array(
    			'capacity'=>$data['capacity'],
    			'status'=>$data['status'],
    	);
    	$where='id='.$data['id'];
    	$this->update($_arr, $where);
    }
    	
    function getAllEngince($search=null){
    	$db = $this->getAdapter();
    	$sql="SELECT id,capacity,(SELECT name_en FROM ldc_view WHERE TYPE=2 AND key_code=status) AS status
    	FROM $this->_name WHERE status=1";
    	$order=' ORDER BY id DESC';
        return $db->fetchAll($sql.$order);
    }
    
 function getEnginceById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,capacity,status FROM ldc_engince  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }

}  
	  

