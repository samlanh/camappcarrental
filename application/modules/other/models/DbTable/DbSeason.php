<?php

class Other_Model_DbTable_DbSeason extends Zend_Db_Table_Abstract
{
	protected $_name = 'ldc_season';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	}
	function addseason($_data){
		$photo = str_replace(" ","",$_data['season_title']);
		$photo = str_replace("%","",$_data['season_title']);
		$photo_name=str_replace("", "_", $photo) . '.jpg';
		$upload=new Zend_File_Transfer();
		$a=$upload->addFilter('Rename',
				array('target'=>PUBLIC_PATH .'/images/'.$photo_name,'overwrite'=>true));
		$recieve=$upload->receive();
		if($recieve){
			$img=$photo_name;
		}
		else{
			$img="";
		}
		
		$_arr = array(
				'season_title'=>$_data['season_title'],
				'start_date'=>$_data['start_date'],
				'end_date'=>$_data['end_date'],
				'value'=>$_data['value'],
				'season_type'=>$_data['season_type'],
				'status'=>$_data['status'],
				'date'=>date("Y-m-d"),
				'user_id'=>$this->getUserId(),
				'photo_name'=>$img,
				'note'=>$_data['note']
		);
		$this->insert($_arr);//insert data
	}
	function updateSeason($_data){
		    $photo = str_replace(" ","",$_data['season_title']);
		    $photo = str_replace("%","",$_data['season_title']);
			$photo_name=str_replace("", "_", $photo) . '.jpg';
			$upload=new Zend_File_Transfer();
			$a=$upload->addFilter('Rename',
					array('target'=>PUBLIC_PATH .'/images/'.$photo_name,'overwrite'=>true));
			$recieve=$upload->receive();
			if($recieve){
				$img=$photo_name;
			}
			else{
				$img=$_data['oldphoto'];
			}
			$_arr = array(
				'season_title'=>$_data['season_title'],
				'start_date'=>$_data['start_date'],
				'end_date'=>$_data['end_date'],
				'value'=>$_data['value'],
				'season_type'=>$_data['season_type'],
				'status'=>$_data['status'],
				'date'=>date("Y-m-d"),
				'user_id'=>$this->getUserId(),
				'photo_name'=>$img,
				'note'=>$_data['note']
		);
			$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
	    	$this->update($_arr, $where);
	}
	 
	function getAllSeason($search=null){
		$db = $this->getAdapter();
		$sql = "SELECT id,season_title,`value`,
		(SELECT name_en FROM `ldc_view` WHERE TYPE=5 AND key_code =`season_type`) AS season_type
		,start_date,end_date,
		(SELECT name_en FROM `ldc_view` WHERE TYPE=2 AND key_code =`ldc_season`.`status`)
		AS status FROM ldc_season WHERE 1 ";
		 
		$where="";
		 
		if(!empty($search['title'])){
			$s_where=array();
			$s_search=addslashes($search['adv_search']);
			$s_where[]=" title LIKE '%{$s_search}%'";
			$s_where[]=" start_date LIKE '%{$s_search}%'";
			$s_where[]=" end_date LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ',$s_where).')';
		}
		$order=' ORDER BY id DESC';
		return $db->fetchAll($sql.$where.$order);
	}
	function getSeasonById($id){
		$db = $this->getAdapter();
		$sql=" SELECT * FROM $this->_name WHERE id = $id LIMIT 1";
		return $db->fetchRow($sql);
		
	}
	function getAllPromotionName(){//front
		$db = $this->getAdapter();
		$date=date("Y-m-d");
		$sql = " SELECT * FROM ldc_season WHERE status=1 AND season_title!='' AND end_date >= '".$date."'";
		return $db->fetchAll($sql);
	}
	function getAllPrePromotionName(){//front
		$db = $this->getAdapter();
		$date=date("Y-m-d");
		$sql = " SELECT * FROM ldc_season WHERE status=1 AND season_title!='' AND end_date < '".$date."'";
		return $db->fetchAll($sql);
	}
	
}  





