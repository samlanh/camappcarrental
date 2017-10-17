<?php

class Vehicle_Model_DbTable_DbVehicle extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_vehicle';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    function addVehicle($data){
//     	print_r($data);exit();
    	$adapter = new Zend_File_Transfer_Adapter_Http();
    	$part= PUBLIC_PATH.'/images/vehicle';
    	$adapter->setDestination($part);
    	$adapter->receive();
    	$photo = $adapter->getFileInfo();
    	
    	if(!empty($photo['front']['name'])){
    		$data['front']=$photo['front']['name'];
    	}else{
    		$data['front']='';
    	}
    	
    	if(!empty($photo['front_right']['name'])){
    		$data['front_right']=$photo['front_right']['name'];
    	}else{
    		$data['front_right']='';
    	}
    	
    	if(!empty($photo['side_right']['name'])){
    		$data['img_sr']=$photo['side_right']['name'];
    	}else{
    		$data['img_sr']='';
    	}
    	
    	if(!empty($photo['rear_right']['name'])){
    		$data['rear_right']=$photo['rear_right']['name'];
    	}else{
    		$data['rear_right']="";
    	}
    	
    	if(!empty($photo['img_rear']['name'])){
    		$data['img_rear']=$photo['img_rear']['name'];
    	}else{
    		$data['img_rear']="";
    	}
    	
    	if(!empty($photo['front_left']['name'])){
    		$data['front_left']=$photo['front_left']['name'];
    	}else{
    		$data['front_left']="";
    	}
    	
    	if(!empty($photo['rear_left']['name'])){
    		$data['rear_left']=$photo['rear_left']['name'];
    	}else{
    		$data['rear_left']="";
    	}
    	
    	if(!empty($photo['side_left']['name'])){
    		$data['side_left']=$photo['side_left']['name'];
    	}else{
    		$data['side_left']="";
    	}
    	
    	if(!empty($photo['seat']['name'])){
    		$data['seat']=$photo['seat']['name'];
    	}else{
    		$data['seat']="";
    	}
    	
    	if(!empty($photo['passenger']['name'])){
    		$data['passenger']=$photo['passenger']['name'];
    	}else{
    		$data['passenger']="";
    	}
    	 
    	if(!empty($photo['trunk']['name'])){
    		$data['trunk']=$photo['trunk']['name'];
    	}else{
    		$data['trunk']="";
    	}
    	
    	if(!empty($photo['luggage']['name'])){
    		$data['img_luggage']=$photo['luggage']['name'];
    	}else{
    		$data['img_luggage']="";
    	}
    	
    	
    	$_arr = array(
    			'reffer'=>$data['vehicle_ref_no'],
    			'frame_no'=>$data['frame_no'],
    			'licence_plate'=>$data['licence_piate_no'],
    			'max_weight'=>$data['maximum_weight'],
    			'seat_amount'=>$data['no_of_seats'],
    			'org_cost'=>$data['original'],
    			'color'=>$data['color'],
    			'date_buy'=>$data['date_buy'],
    			'chassis_no'=>$data['chassis_no'],
    			'make_id'=>$data['make'],
    			'model_id'=>$data['model'],
    			'sub_model'=>$data['submodel'],
    			'engine'=>$data['engine'],
    			'engine_number'=>$data['engine_number'],
    			'transmission'=>$data['transmission'],
    			'year'=>$data['year'],
    			'type'=>$data['type'],
    			'steering'=>$data['steering'],
    			'test_url'=>$data['test_url'],
    			
    			'of_axlex'=>$data['of_axlex'],
    			'of_cylinder'=>$data['of_cylinder'],
    			'cylinders_dip'=>$data['cylinders_dip'],
    			
    			'show_url'=>$data['show_url'],
    			'car_type'=>$data['vehicle_type'],
    			'status'=>$data['status'],
    			'discount'=>$data['discount_value'],
    			'description'=>$data['note'],
    			'is_promotion'=>empty($data['discount'])?0:1,
                        'discount_fromdate'=>$data['discount_fromdate'],
    			'discount_todate'=>$data['discount_todate'],
    			'ordering'=>$data['deposit'],
    			'root_side_ass'=>$data['root_side_ass'],
    			'of_axlex'=>$data['of_axlex'],
    			'of_cylinder'=>$data['of_cylinder'],
    			'cylinders_dip'=>$data['cylinders_dip'],
    			
    			'is_sale'=>!empty($data['is_sale'])?1:0,
    			'sale_price'=>$data['sale_price'],
    			'description_sale'=>$data['desc_sale'],
    			
    			'img_front'=>$data['front'],
    			'img_front_right'=>$data['front_right'],
    			'img_sr'=>$data['img_sr'],
    			'img_rear_right'=>$data['rear_right'],
    			'img_rear'=>$data['img_rear'],
    			'img_rear_left'=>$data['rear_left'],
    			'img_sl'=>$data['side_left'],
    			'img_front_left'=>$data['front_left'],
    			'img_seat'=>$data['seat'],
    			'img_passenger'=>$data['passenger'],
    			'img_trunk'=>$data['trunk'],
    			'img_luggage'=>$data['img_luggage'],
    			
    			'user_id'=>$this->getUserId(),
    			);
    	 $this->insert($_arr);
    	
    }
    public function updateVehicle($data){
    	
    	$adapter = new Zend_File_Transfer_Adapter_Http();
    	$part= PUBLIC_PATH.'/images/vehicle';
    	$adapter->setDestination($part);
    	$adapter->receive();
    	
    	$photo = $adapter->getFileInfo();
    	
    	
    	if(!empty($photo['front']['name'])){
    		$data['front']=$photo['front']['name'];
    	}else{
    		$data['front']=$data['old_front'];
    	}
    	 
    	if(!empty($photo['front_right']['name'])){
    		$data['front_right']=$photo['front_right']['name'];
    	}else{
    		$data['front_right']=$data['old_front_right'];
    	}
    	 
    	if(!empty($photo['side_right']['name'])){
    		$data['img_sr']=$photo['side_right']['name'];
    	}else{
    		$data['img_sr']=$data['old_side_right'];
    	}
    	 
    	if(!empty($photo['rear_right']['name'])){
    		$data['rear_right']=$photo['rear_right']['name'];
    	}else{
    		$data['rear_right']=$data['old_rear_right'];
    	}
    	 
    	if(!empty($photo['img_rear']['name'])){
    		$data['img_rear']=$photo['img_rear']['name'];
    	}else{
    		$data['img_rear']=$data['old_img_rear'];
    	}
    	 
    	if(!empty($photo['front_left']['name'])){
    		$data['front_left']=$photo['front_left']['name'];
    	}else{
    		$data['front_left']=$data['old_front_left'];
    	}
    	 
    	if(!empty($photo['rear_left']['name'])){
    		$data['rear_left']=$photo['rear_left']['name'];
    	}else{
    		$data['rear_left']=$data['old_rear_left'];
    	}
    	 
    	if(!empty($photo['side_left']['name'])){
    		$data['side_left']=$photo['side_left']['name'];
    	}else{
    		$data['side_left']=$data['old_side_left'];
    	}
    	 
    	if(!empty($photo['seat']['name'])){
    		$data['seat']=$photo['seat']['name'];
    	}else{
    		$data['seat']=$data['old_seat'];
    	}
    	 
    	if(!empty($photo['passenger']['name'])){
    		$data['passenger']=$photo['passenger']['name'];
    	}else{
    		$data['passenger']=$data['old_passenger'];
    	}
    	
    	if(!empty($photo['trunk']['name'])){
    		$data['trunk']=$photo['trunk']['name'];
    	}else{
    		$data['trunk']=$data['old_trunk'];
    	}
    	 
    	if(!empty($photo['luggage']['name'])){
    		$data['img_luggage']=$photo['luggage']['name'];
    	}else{
    		$data['img_luggage']=$data['old_luggage'];
    	}
    	
    	$_arr = array(
    			'reffer'=>$data['vehicle_ref_no'],
    			'frame_no'=>$data['frame_no'],
    			'licence_plate'=>$data['licence_piate_no'],
    			'max_weight'=>$data['maximum_weight'],
    			'seat_amount'=>$data['no_of_seats'],
    			'org_cost'=>$data['original'],
    			'color'=>$data['color'],
    			'date_buy'=>$data['date_buy'],
    			'chassis_no'=>$data['chassis_no'],
    			'make_id'=>$data['make'],
    			'model_id'=>$data['model'],
    			'sub_model'=>$data['submodel'],
    			'engine'=>$data['engine'],
    			'engine_number'=>$data['engine_number'],
    			'transmission'=>$data['transmission'],
    			'year'=>$data['year'],
    			'type'=>$data['type'],
    			'steering'=>$data['steering'],
    			'test_url'=>$data['test_url'],
    			'show_url'=>$data['show_url'],
    			'car_type'=>$data['vehicle_type'],
    			'status'=>$data['status'],
    			'discount'=>$data['discount_value'],
    			'description'=>$data['note'],
    			'is_promotion'=>empty($data['discount'])?0:1,
                        'discount_fromdate'=>$data['discount_fromdate'],
    			'discount_todate'=>$data['discount_todate'],
    			'ordering'=>$data['deposit'],
    			'root_side_ass'=>$data['root_side_ass'],
    			
    			'of_axlex'=>$data['of_axlex'],
    			'of_cylinder'=>$data['of_cylinder'],
    			'cylinders_dip'=>$data['cylinders_dip'],
    			
    			'is_sale'=>!empty($data['is_sale'])?1:0,
    			'sale_price'=>$data['sale_price'],
    			'description_sale'=>$data['desc_sale'],
    			
    			'img_front'=>$data['front'],
    			'img_front_right'=>$data['front_right'],
    			'img_sr'=>$data['img_sr'],
    			'img_rear_right'=>$data['rear_right'],
    			'img_rear'=>$data['img_rear'],
    			'img_rear_left'=>$data['rear_left'],
    			'img_sl'=>$data['side_left'],
    			'img_front_left'=>$data['front_left'],
    			'img_seat'=>$data['seat'],
    			'img_passenger'=>$data['passenger'],
    			'img_trunk'=>$data['trunk'],
    			'img_luggage'=>$data['img_luggage'],
    			
    			'user_id'=>$this->getUserId(),
    			);
    	$where='id='.$data['id'];
    	$this->update($_arr, $where);
    }
 function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
    function getAllMake(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,title FROM ldc_make WHERE `status`=1 ";
    return $db->fetchAll($sql);
    }
    function getAllEnGince(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,capacity FROM ldc_engince WHERE `status`=1 ";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getAllType(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,`type` FROM ldc_type WHERE `status`= 1 ";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getAllTransmisstion(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,tran_name FROM ldc_transmission WHERE `status`=1";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getAllVehicleType(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,title FROM ldc_vechicletye WHERE `status`= 1 ";
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function getAllSubModelById($model_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,title AS name FROM ldc_submodel WHERE model_id=".$model_id;
    	$order=' ORDER BY id DESC';
    	return $db->fetchAll($sql.$order);
    }
    function addSubModelajx($data){
    		$this->_name='ldc_submodel';
    		$db = $this->getAdapter();
    		$arr = array(
    				'make_id'=>$data['txt_make_id'],
    				'model_id'=>$data['txt_model_id'],
    				'title'=>$data['txt_submodel'],
    				'status'=>1
    	
    		);
    		return $this->insert($arr);
    }
    function getAllVehicle($search=null){
    	$db=$this->getAdapter();
    	$where ="WHERE 1";
    	$sql="SELECT id,reffer,`year`,
    	       (SELECT title FROM ldc_make WHERE id=v.make_id LIMIT 1) AS make_id,
		       (SELECT title FROM ldc_model WHERE id=v.model_id LIMIT 1) AS model_id,
		       (SELECT title FROM ldc_submodel WHERE id=v.sub_model LIMIT 1) AS sub_model,
    	       (SELECT `type` FROM ldc_type AS t WHERE t.id=v.type LIMIT 1) AS `type`,
                color,(SELECT capacity FROM ldc_engince WHERE id=`engine` LIMIT 1) AS `engine`,chassis_no,frame_no,licence_plate,date_buy,`status`
    	        FROM ldc_vehicle As v ";
    	if($search['search_status']>-1){
			$where.= " AND status = ".$search['search_status'];
		}
		if($search['make']>0){
			$where.=" AND make_id = ".$search['make'];
		}
		if($search['model']>0){
			$where.=" AND model_id = ".$search['model'];
		}
		if($search['submodel']>0){
			$where.=" AND sub_model = ".$search['submodel'];
		}
		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search=$search['adv_search'];
			$s_where[]= " reffer LIKE '%{$s_search}%'";
			$s_where[]=" year LIKE '%{$s_search}%'";
			$s_where[]= " chassis_no LIKE '%{$s_search}%'";
			$s_where[]= " frame_no LIKE '%{$s_search}%'";
			$s_where[]= " licence_plate LIKE '%{$s_search}%'";
			$s_where[]= " date_buy LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		$order = " ORDER BY id DESC";
		//echo $sql.$where;		
		return $db->fetchAll($sql.$where.$order);	
    }
    function getVehicleById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM $this->_name WHERE id=".$id." LIMIT 1";
    	return $db->fetchRow($sql);
    }
}  
	  

