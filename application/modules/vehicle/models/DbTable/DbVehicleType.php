<?php

class Vehicle_Model_DbTable_DbVehicleType extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_vechicletye';
    function addVehicleType($data){
    	$_arr = array(
    			'title'=>$data['vehicle_type'],
    			'status'=>$data['status'],
    			);
    	$this->insert($_arr);//insert data
    }
    public function updateVehicleType($data){
    	//print_r($data);exit();
    	$_arr = array(
    			'title'=>$data['vehicle_type'],
    			'status'=>$data['status'],
    			);
    	$where='id='.$data['id'];
    	$this->update($_arr, $where);
    }
    	
    function getAllVehicleType($search=null){
    	$db = $this->getAdapter();
    	$sql="SELECT id,title,(SELECT name_en FROM ldc_view WHERE TYPE=2 AND key_code=status) AS status
    	FROM $this->_name WHERE status=1";
    	$order=' ORDER BY id DESC';
        return $db->fetchAll($sql.$order);
    }
    
 function getVehicleTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,title,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }

}  
	  

