<?php

class Other_Model_DbTable_DbCountries extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_country';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addcountry($_data){
    	$_arr = array(
    			'country_name'=>$_data['title'],
    			'date'=>date("Y-m-d"),
    			'user_id'=>$this->getUserId(),
    			'status'=>$_data['status'],
    			);
    	$this->insert($_arr);
    }
    public function updateCountry($_data){
    	$_arr = array(
    			'country_name'=>$_data['title'],
    			'date'=>date("Y-m-d"),
    			'user_id'=>$this->getUserId(),
    			'status'=>$_data['status'],
    			);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);
    }
    	
	function getAllCoutry($search=null){
    	$sql="SELECT id,country_name,`status` FROM $this->_name WHERE `status`=1";
    	if(!empty($search['title'])){
    		$title=trim(addslashes($search['title']));
    		$sql.=" AND country_name like '%".$title."%'";
    	}
    	$order=' ORDER BY id DESC';
    	return $this->getAdapter()->fetchAll($sql.$order);
    }
    function getCountryById($id){
    	$id = addslashes($id);
    	$sql="SELECT id,country_name,`status` FROM $this->_name WHERE `id`=$id";
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
	  

