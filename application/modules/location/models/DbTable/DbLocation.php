<?php

class Location_Model_DbTable_DbLocation extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_package_location';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function addPackage($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$adapter = new Zend_File_Transfer_Adapter_Http();
    		$adapter->setDestination(PUBLIC_PATH."/images/location");
    		$fileinfo=$adapter->getFileInfo();
    		$adapter->receive();
    		
	    	$_arr=array(
	    		    'location_name' => $_data['location_name'],
	    			'province_id' => $_data['province_name'],
	    			'status'  => $_data['status'],
	    			'note'    => $_data['note'],
	    			'date'    => date("Y-m-d"),
	    			'user_id' => $this->getUserId(),
	    			'service_type'=>$_data['service_type'],
	    			'locationtype_id'=>$_data['location_type'],
	    	);
	    	$id =  $this->insert($_arr);
	    	
	    	$this->_name='ldc_package_detail';
	    	$arr = array(
	    			'package_id'=>$id,
	    			'location_id'=>$id,
	    			'description'=>$_data['note'],
	    			'status'=>$_data['status'],
	    			'date'=>date("Y-m-d"),
	    			'user_id'=> $this->getUserId()
	    			);
	    	 $this->insert($arr);
	    	
	    	$this->_name='ldc_picture_location';
	    	$ids = explode(',', $_data['record_row']);
	    	$upload=new Zend_File_Transfer();
	    	foreach ($ids as $i){
	    		$photo_name  = ($fileinfo['photo'.$i]['name']);
	    		$arr = array(
	    				'location_id'=>$id,
	    				'pic_title'=>$photo_name,
	    				'status'=>$_data['status'],
	    				'date'=>date("Y-m-d")
	    		);
	    		$this->insert($arr);
	    	}
	    	$db->commit();
    	}catch(Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    	}
    	
    }
    public function updatePackage($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$adapter = new Zend_File_Transfer_Adapter_Http();
    		$adapter->setDestination(PUBLIC_PATH."/images/location");
    		$fileinfo=$adapter->getFileInfo();
    		$is_received = $adapter->receive();
    		$_arr=array(
    				'location_name' => $_data['location_name'],
    				'province_id' => $_data['province_name'],
    				'status'  => $_data['status'],
    				'note'    => $_data['note'],
    				'date'    => date("Y-m-d"),
    				'user_id' => $this->getUserId(),
    				'service_type'=>$_data['service_type'],
    				'locationtype_id'=>$_data['location_type'],
    		);
    		$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    		$this->update($_arr, $where);
    		
    
    		$this->_name='ldc_package_detail';
    		$arr = array(
//     				'package_id'=>$id,
//     				'location_id'=>$id,
    				'description'=>$_data['note'],
    				'status'=>$_data['status'],
    				'date'=>date("Y-m-d"),
    				'user_id'=> $this->getUserId()
    		);
    
    		$where=$this->getAdapter()->quoteInto("package_id=?", $_data['id']);
    		$this->update($arr, $where);
    		
    		$ids = explode(',', $_data['record_row']);
    		$s_where=array();
    		foreach ($ids as $i){
    			if(!empty($_data['old_photoid'.$i])){
    				$s_where[]=" id !=".$_data['old_photoid'.$i];
    			}
    		}
    		$where=" location_id = ".$_data['id'];
    		if(!empty($s_where)){
	    		$where.=' AND '.implode(' AND ', $s_where).'';
	    	}
    		$this->_name='ldc_picture_location';
    		$this->delete($where);
    		
    		$ids = explode(',', $_data['record_row']);
//     		print_r($fileinfo);exit();
    		if($is_received){
    			foreach ($ids as $i){
    				$photo_name  = ($fileinfo['photo'.$i]['name']);
    				if(!empty($photo_name)){
	    				$arr = array(
	    				    	'location_id'=> $_data['id'],
	    						'pic_title'=>$photo_name,
	    						'status'=>$_data['status'],
	    						'date'=>date("Y-m-d")
	    				);
	    				$this->insert($arr);
    				}
    			}
    		}

    		$db->commit();
    	}catch(Exception $e){
    		$db->rollBack();
    		
    	}
    	 
    }
	public function getLocationById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM ldc_package_location WHERE id = ".$id;
		$sql.=" LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getPhotoDetailById($id){
		$db = $this->getAdapter();
		$sql = "SELECT id,pic_title FROM `ldc_picture_location` WHERE location_id=".$id;
		$rows=$db->fetchAll($sql);
		return $rows;
	}
    public function updateProvince($_data,$id){
    	$_arr=array(
    			'province_name' => $_data['kh_province'],
    			'date'      => date("Y-d-m"),
    			'status'           => $_data['status'],
    			'user_id'	       => $this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $id);
    	$this->update($_arr, $where);
    }
    function getAllLocations($search=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT  id,location_name, (SELECT province_name FROM `ldc_province` WHERE id=province_id) AS province_name,
                 date,(SELECT name_en FROM `ldc_view` WHERE TYPE=2 AND key_code =$this->_name.`status`) AS status
                 FROM $this->_name WHERE is_package !=1 AND location_name!='' ";
    	$order=" order by id DESC";
    	$where = '';
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=addslashes($search['title']);
    		$s_where[]=" location_name LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	if($search['status']>-1){
    		$where.= " AND status = ".$db->quote($search['status']);
    	}
    	return $db->fetchAll($sql.$where.$order);
    }
    /*----------------------------package----------------------------*/
    function getAllPackages($search=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT  id,location_name, (SELECT province_name FROM `ldc_province` WHERE id=province_id) AS province_name,
    	date,(SELECT name_en FROM `ldc_view` WHERE TYPE=2 AND key_code =$this->_name.`status`) AS status
    	FROM $this->_name WHERE location_name!='' AND is_package=1 ";
    	$order=" order by id DESC";
    	$where = '';
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=addslashes($search['title']);
    		$s_where[]=" location_name LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	if($search['status']>-1){
    		$where.= " AND status = ".$db->quote($search['status']);
    	}
    	return $db->fetchAll($sql.$where.$order);
    }
    function getAllLocationByPackage($package_id){//using in index
    	$db = $this->getAdapter();
    	$sql = " SELECT location_id,province_id,
					(SELECT location_name FROM `ldc_package_location` WHERE STATUS=1 AND is_package!=1 AND id =location_id) AS location_name
 				FROM `ldc_package_detail` WHERE package_id=$package_id AND status=1";
    	return $db->fetchAll($sql);
    }
	public function addNewPackageLocation($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$adapter = new Zend_File_Transfer_Adapter_Http();
			$adapter->setDestination(PUBLIC_PATH."/images/location");
			$fileinfo=$adapter->getFileInfo();
			$adapter->receive();
	
			$_arr=array(
					'province_id' => $_data['province_name'],
					'location_name' => $_data['location_name'],
					'is_package' => 1,
					'service_type'=>1,
					'status'  => $_data['status'],
					'note'    => $_data['note'],
					'date'    => date("Y-m-d"),
					'user_id' => $this->getUserId()
			);
			$id =  $this->insert($_arr);
			
			$this->_name='ldc_package_detail';
			$ids = explode(',', $_data['record_row']);
			foreach ($ids as $i){
				$arr = array(
						'package_id'=>$id,
						'location_id'=>empty($_data['location_id'.$i])?0:$_data['location_id'.$i],
						'description'=>$_data['note'],
						'status'=>$_data['status'],
						'date'=>date("Y-m-d"),
						'user_id'=> $this->getUserId()
				);
				$this->insert($arr);
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo $e->getMessage();
		}
	}
	public function updateNewPackageLocation($_data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$adapter = new Zend_File_Transfer_Adapter_Http();
			$adapter->setDestination(PUBLIC_PATH."/images/location");
			$fileinfo=$adapter->getFileInfo();
			$adapter->receive();
	
			$_arr=array(
					'province_id' => $_data['province_name'],
					'location_name' => $_data['location_name'],
					'service_type'=>1,
					'is_package' => 1,
					'status'  => $_data['status'],
					'note'    => $_data['note'],
					'date'    => date("Y-m-d"),
					'user_id' => $this->getUserId()
			);
			$where=$this->getAdapter()->quoteInto("id=?",  $_data['id']);
			$this->update($_arr, $where);
			
			$this->_name='ldc_package_detail';
			$where=$this->getAdapter()->quoteInto("package_id=?",  $_data['id']);
			$this->delete($where);
				
			$this->_name='ldc_package_detail';
			$ids = explode(',', $_data['record_row']);
			foreach ($ids as $i){
				$arr = array(
						'package_id'=>$_data['id'],
						'location_id'=>empty($_data['location_id'.$i])?0:$_data['location_id'.$i],
						'description'=>$_data['note'],
						'status'=>$_data['status'],
						'date'=>date("Y-m-d"),
						'user_id'=> $this->getUserId()
				);
				$this->insert($arr);
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo $e->getMessage();
		}
			
	}
   
}
