<?php

class Location_Model_DbTable_DbLocationtype extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_locationtype';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('authcar');
    	return $session_user->user_id;
    	 
    }
    public function addLocationType($_data){
    	$db = $this->getAdapter();
	    	$_arr=array(
	    			'title' => $_data['loation_type'],
	    			'status'  => $_data['status'],
	    			'date'    => date("Y-m-d"),
	    			'create_date'=> date("Y-m-d H:i"),
	    			'user_id' => $this->getUserId()
	    	);
	    	$id =  $this->insert($_arr);
    }
    public function updateLocationType($_data){
    	$db = $this->getAdapter();
    	$_arr=array(
    			'title' => $_data['loation_type'],
    			'status'  => $_data['status'],
    			'date'    => date("Y-m-d"),
    			'user_id' => $this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
	public function getLocationTypeById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM $this->_name WHERE id = ".$id;
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
    function getAllLocationType($search=null){
    	$db = $this->getAdapter();
    	$dbgb = new Application_Model_DbTable_DbGlobal();
    	$lang= $dbgb->getCurrentLang();
    	$arrayview = array(1=>"name_en",2=>"name_kh");
    	$sql = " SELECT id,title,date,
    	(SELECT ".$arrayview[$lang]." FROM `ldc_view` WHERE TYPE=2 AND key_code =$this->_name.`status`) 
		AS status
    	FROM $this->_name
    	WHERE title!='' ";
    	$order=" order by id DESC";
    	$where = '';
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=addslashes($search['title']);
    		$s_where[]=" title LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	if($search['status']>-1){
    		$where.= " AND status = ".$db->quote($search['status']);
    	}
    	return $db->fetchAll($sql.$where.$order);
    }
   
}

