<?php

class Vehicle_Model_DbTable_DbMake extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_make';
    function addMake($data){
    	//print_r($data);exit();
    	$_arr = array(
    			'title'=>$data['make'],
    			'status'=>1,
    			);
    	$this->insert($_arr);//insert data
    }
    public function updateMake($data){
    	$_arr = array(
    			'title'=>$data['make'],
    			'status'=>$data['status'],
    	);
    	$where='id='.$data['id'];
    	$this->update($_arr, $where);
    }
    	
    function getAllMake($search=null){
    	$db = $this->getAdapter();
    	$sql='SELECT id,title,(SELECT name_en FROM ldc_view WHERE TYPE=2 AND key_code=status) AS status
    	FROM ldc_make WHERE status=1 ';
    	$order=' ORDER BY id DESC';
        return $db->fetchAll($sql.$order);
    }
    
 function getMakeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,title ,status FROM  $this->_name WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
//     public static function getBranchCode(){
//     	$db = new Application_Model_DbTable_DbGlobal();
//     	$sql = "SELECT COUNT(br_id) AS amount FROM `ln_branch`";
//     	$acc_no= $db->getGlobalDbRow($sql);
//     	$acc_no=$acc_no['amount'];
//     	$new_acc_no= (int)$acc_no+1;
//     	$acc_no= strlen((int)$acc_no+1);
//     	$pre = "";
//     	for($i = $acc_no;$i<3;$i++){
//     		$pre.='0';
//     	}
//     	return "C-".$pre.$new_acc_no;
//     }
  
}  
	  

