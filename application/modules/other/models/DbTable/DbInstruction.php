<?php

class Other_Model_DbTable_DbInstruction extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_instruction';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addInstruction($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'description'=>$_data['note'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId(),
    			'date'=>date("d-m-Y")
    			);
    	$this->insert($_arr);//insert data
    }
    function getAllInstruction(){
    	$db = $this->getAdapter();
    	$sql=" select id,title,
		(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_instruction.`status`  ) AS status
		from ldc_instruction where 1";
    	return $db->fetchAll($sql);
    }
    function getInstructionById($id){
    	$db = $this->getAdapter();
    	$sql="select id,title,description,status from ldc_instruction where id =$id";
    	return $db->fetchRow($sql);
    }
    function getInstructionDetailById($id){
    	$db = $this->getAdapter();
    	$sql="select id,title,description,status from ldc_instruction ";
    	if(empty($id)){
    		$sql.=" LIMIT 1";
    	}else{
    		$sql.=" where id =".$id;
    	}
    	return $db->fetchRow($sql);
    }
    function updateInstruction($_data){
    	$_arr = array(
    			'title'=>$_data['title'],
    			'description'=>$_data['note'],
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId(),
    			'date'=>date("d-m-Y")
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    function getAllInstructionMenu(){
    	$db = $this->getAdapter();
    	$sql=" select id,title,description,
    	(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_instruction.`status`  ) AS status
    	from ldc_instruction where status=1";
    	return $db->fetchAll($sql);
    }
}  
	  

