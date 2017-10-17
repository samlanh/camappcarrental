<?php
class Application_Model_DbTable_DbGlobal extends Zend_Db_Table_Abstract
{
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	}
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function getAllParameter($title){
		$db= $this->getAdapter();
		$this->_name="ldc_paramater";
		$sql = "SELECT p.`value` FROM $this->_name AS p WHERE p.`status`=1 AND p.`title`='$title'";
		return $db->fetchOne($sql);
	}
	public function getGlobalDb($sql)
  	{
  		$db=$this->getAdapter();
  		$row=$db->fetchAll($sql);  		
  		if(!$row) return NULL;
  		return $row;
  	}
  	public function getGlobalDbRow($sql)
  	{
  		$db=$this->getAdapter();  		
  		$row=$db->fetchRow($sql);
  		if(!$row) return NULL;
  		return $row;
  	}
  	public static function getActionAccess($action)
    {
    	$arr=explode('-', $action);
    	return $arr[0];    	
    }     
    public function isRecordExist($conditions,$tbl_name){
		$db=$this->getAdapter();		
		$sql="SELECT * FROM ".$tbl_name." WHERE ".$conditions." LIMIT 1"; 
		$row= count($db->fetchRow($sql));
		if(!$row) return NULL;
		return $row;	
    }
    /*for select 1 record by id of earch table by using params*/
    public function GetRecordByID($conditions,$tbl_name){
    	$db=$this->getAdapter();
    	$sql="SELECT * FROM ".$tbl_name." WHERE ".$conditions." LIMIT 1";
    	$row = $this->fetchRow($sql);
    	return $row;
    	$row= $db->fetchRow($sql);
    	return $row;
    }
    /**
     * insert record to table $tbl_name
     * @param array $data
     * @param string $tbl_name
     */
    public function addRecord($data,$tbl_name){
    	$this->setName($tbl_name);
    	return $this->insert($data);
    }
    public function updateRecord($data,$id,$tbl_name){
    	$this->setName($tbl_name);
    	$where=$this->getAdapter()->quoteInto('id=?',$id);
    	$this->update($data,$where);    	
    }   
    public function DeleteRecord($tbl_name,$id){
    	$db = $this->getAdapter();
		$sql = "UPDATE ".$tbl_name." SET status=0 WHERE id=".$id;
		return $db->query($sql);
    } 
     public function DeleteData($tbl_name,$where){
    	$db = $this->getAdapter();
		$sql = "DELETE FROM ".$tbl_name.$where;
		return $db->query($sql);
    } 
    public function getDayInkhmerBystr($str){
    	
    	$rs=array(
    			'Mon'=>'ច័ន្ទ',
    			'Tue'=>'អង្គារ',
    			'Wed'=>'ពុធ',
    			'Thu'=>"ព្រហ",
    			'Fri'=>"សុក្រ",
    			'Sat'=>"សៅរី",
    			'Sun'=>"អាទិត្យ");
    	if($str==null){
    		return $rs;
    	}else{
    	return $rs[$str];
    	}
    
    }
    public function convertStringToDate($date, $format = "Y-m-d H:i:s")
    {
    	if(empty($date)) return NULL;
    	$time = strtotime($date);
    	return date($format, $time);
    }   
    public static function getResultWarning(){
          return array('err'=>1,'msg'=>'មិន​ទាន់​មាន​ទន្និន័យ​នូវ​ឡើយ​ទេ!');	
    }
   /*@author Mok Channy
    * for use session navigetor 
    * */
//    public static function SessionNavigetor($name_space,$array=null){
//    	$session_name = new Zend_Session_Namespace($name_space);
//    	return $session_name;   	
//    }
    function getAllClient($branch_id=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT c.`client_id` AS id  ,c.`branch_id`,
    	CONCAT(c.`name_en`,'-',c.`name_kh`) AS name , client_number
    	FROM `ln_client` AS c WHERE c.`name_en`!='' AND c.status=1  " ;
    	if($branch_id!=null){
    		$sql.=" AND c.`branch_id`= $branch_id ";
    
    	}
    	$sql.=" ORDER BY id DESC";
    	return $db->fetchAll($sql);
    }
    function getAllClientNumber($branch_id=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT c.`client_id` AS id  ,c.client_number AS name, c.`branch_id`
    	FROM `ln_client` AS c WHERE c.`name_en`!='' AND c.status=1 " ;
    	if($branch_id!=null){
    		$sql.=" AND c.`branch_id`= $branch_id ";
    	}
    	return $db->fetchAll($sql);
    }
    public  function getclientdtype(){
    	$db = $this->getAdapter();
    	$sql="SELECT key_code AS id,CONCAT(name_kh,'-',name_en) AS name ,displayby FROM `ldc_view` WHERE STATUS =1 AND type=6";
    	$rows = $db->fetchAll($sql);
    	return $rows;
    }
   public function getAllProvince($opt=null){
   	$this->_name='ldc_province';
   	$sql = " SELECT id,province_name FROM $this->_name WHERE status=1 AND province_name!='' ORDER BY id DESC";
   	$db = $this->getAdapter();
    $rows =$db->fetchAll($sql);
	   	if($opt!=null){
	   		$options="";
	   		if(!empty($rows))foreach($rows AS $row){
	   			$options[$row['id']]=$row['province_name'];
	   		}
	   		return $options;
	   	}else{
	   		return $rows;
	   	}
   }
   public function getAllPackageDay($opt=null){
   	$this->_name='ldc_rankday';
   	$sql = " SELECT id,CONCAT(day_title,'(',from_amountday,'-',to_amountday,')') as package FROM $this->_name WHERE status=1 AND day_title!='' ";
   	$db = $this->getAdapter();
   	$rows =$db->fetchAll($sql);
   	if($opt!=null){
   		$options="";
   		if(!empty($rows))foreach($rows AS $row){
   			$options[$row['id']]=$row['day_title'];
   		}
   		return $options;
   	}else{
   		return $rows;
   	}
   }
   public function getAllTax($opt=null){
   	$this->_name='ldc_tax';
   	$sql = " SELECT value as id ,CONCAT(title,'( ',value,'%)') AS title FROM $this->_name WHERE title!='' AND STATUS=1 ";
   	$db = $this->getAdapter();
   	$rows =$db->fetchAll($sql);
   	if($opt!=null){
   		$options="";
   		if(!empty($rows))foreach($rows AS $row){
   			$options[$row['id']]=$row['title'];
   		}
   		return $options;
   	}else{
   		return $rows;
   	}
   }
   public function getAllNameOwner($opt=null){
   	$sql="SELECT id,owner_name FROM ldc_owner WHERE 1";
   	return $this->getAdapter()->fetchAll($sql);
   }
   public function getAllCurrency($id,$opt = null){
	   	$sql = "SELECT * FROM ln_currency WHERE status = 1 ";
	   	if($id!=null){
	   		$sql.=" AND id = $id";
	   	}
	   	$rows = $this->getAdapter()->fetchAll($sql);
	   	if($opt!=null){
	   		$options="";
	   		if(!empty($rows))foreach($rows AS $row){
	   			$options[$row['id']]=($row['displayby']==1)?$row['displayby']:$row['curr_nameen'];
	   		}
	   		return $options;
	   	}else{
	   		return $rows;
	   	}
   	
   }
   
   public function getNewClientId(){
   	$this->_name='ldc_customer';
   	$db = $this->getAdapter();
   	$row = $this->getSystemSetting('customer_prefix');
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = ($row['value']);
   	for($i = $acc_no;$i<3;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getNewBookingCode(){
   	$this->_name='ldc_booking';
   	$db = $this->getAdapter();
   	$row = $this->getSystemSetting('booking_prefix');
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = ($row['value']);
   	for($i = $acc_no;$i<3;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getDriverCode(){
   	$this->_name='ldc_driver';
   	$db = $this->getAdapter();
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	
   	$row = $this->getSystemSetting('driver_prefix');
   	$pre = ($row['value']);
   	for($i = $acc_no;$i<3;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
     
   public static function getCurrencyType($curr_type){
   	$curr_option = array(
   			1=>'រៀល',
   			2=>'ដុល្លា'
   			);
   	return $curr_option[$curr_type];
   	
   }
   public function getAllSituation($id = null){
   	$_status = array(
   			1=>$this->tr->translate("Single"),
   			2=>$this->tr->translate("Married"),
   			3=>$this->tr->translate("Windowed"),
   			4=>$this->tr->translate("Mindowed")
   	);
   	if($id==null)return $_status;
   	else return $_status[$id];
   }
   public function GetAllIDType($id = null){
   	$_status = array(
   			1=>$this->tr->translate("National ID"),
   			2=>$this->tr->translate("Family Book"),
   			3=>$this->tr->translate("Resident Book"),
   			4=>$this->tr->translate("Other")
   	);
   	if($id==null)return $_status;
   	else return $_status[$id];
   }
   public function getAllDegree($id=null){
   	$tr= Application_Form_FrmLanguages::getCurrentlanguage();
   	$opt_degree = array(
   			''=>$this->tr->translate("----ជ្រើសរើស----"),
   			1=>$this->tr->translate("Diploma"),
   			2=>$this->tr->translate("Associate"),
   			3=>$this->tr->translate("Bechelor"),
   			4=>$this->tr->translate("Master"),
   			5=>$this->tr->translate("PhD")
   	);
   	if($id==null)return $opt_degree;
   	else return $opt_degree[$id]; 
  }

  function countDaysByDate($start,$end){
  	$first_date = strtotime($start);
  	$second_date = strtotime($end);
  	$offset = $second_date-$first_date;
  	return floor($offset/60/60/24);
  
  }

 public function returnAfterHoliday($holiday_option,$date){
	  $rs = $this->checkHolidayExist($holiday_option,$date);
	  if(is_array($rs)){
	  	$d = new DateTime($rs['start_date']);
	  	$d->modify( 'next day' );//here check for holiday_option
	  	$date =  $d->format( 'Y-m-d' );
	  	$this->returnAfterHoliday($holiday_option,$date);
	  }else{
	  	echo $date;
	  	return $date;
	  }
  }

  public function getVewOptoinTypeByType($type=null,$option = null,$limit =null,$first_option =null){
  	$db = $this->getAdapter();
  	$sql="SELECT id,key_code,CONCAT(name_kh,'-',name_en) AS name_en ,displayby FROM `ldc_view` WHERE status =1 ";//just concate
  	if($type!=null){
  		$sql.=" AND type = $type ";
  	}
  	if($limit!=null){
  		$sql.=" LIMIT $limit ";
  	}
  	$rows = $db->fetchAll($sql);
  	if($option!=null){
  		$options=array();
  		if($first_option==null){//if don't want to get first select
  			$options=array(''=>"-----ជ្រើសរើស-----",-1=>"Add New",);
  		}
  		if(!empty($rows))foreach($rows AS $row){
  			$options[$row['key_code']]=$row['name_en'];//($row['displayby']==1)?$row['name_kh']:$row['name_en'];
  		}
  		return $options;
  	}else{
  		return $rows;
  	}
  }
//   public function getCollteralType($option = null,$limit =null){
//   	$db = $this->getAdapter();
//   	$sql="SELECT id,title_en,title_kh,displayby FROM `ln_callecteral_type` WHERE status =1 ";
//   	if($limit!=null){
//   		$sql.=" LIMIT $limit ";
//   	}
//   	$rows = $db->fetchAll($sql);
//   	if($option!=null){
//   		$options=array(''=>"-----Select Callecteral Type-----",'-1'=>"Add New");
//   		if(!empty($rows))foreach($rows AS $row){
//   			$options[$row['id']]=($row['displayby']==1)?$row['title_kh']:$row['title_en'];
//   		}
//   		return $options;
//   	}else{
//   		return $rows;
//   	}
//   }
  
  public function checkHolidayExist($date_next,$holiday_option){//for check collect payment in holiday or not
  	$db = $this->getAdapter();
  	$sql="SELECT start_date FROM `ln_holiday` WHERE start_date='".$date_next."'";
  	$rs =  $db->fetchRow($sql);
  	
  	$db = new Setting_Model_DbTable_DbLabel();
  	$array = $db->getAllSystemSetting();
  	if($rs){
  		$d = new DateTime($rs['start_date']);
  		if($holiday_option==1){
  			$str_option = 'previous day';
  		}elseif($holiday_option==2){
  			$str_option = 'next day';
  		}else{
  			return  $d->format( 'Y-m-d' );
  		}
  		$d->modify($str_option); //here check for holiday option //can next day,next week,next month
  		$date_next =  $d->format( 'Y-m-d' );
//   		return $date_next;
  		
  		$d = new DateTime($date_next);
  		$day_work = date("D",strtotime($date_next));
  		
  		if($day_work=='Sat' OR $day_work=='Sun' ){
  			if(($day_work=='Sat' AND $array['work_saturday']==1) OR ($day_work=='Sun' AND $array['work_sunday']==1)){//sat working
  				return $date_next;
  			}else if($day_work=='Sat' AND $array['work_saturday']==0){//sat not working
  				if($holiday_option==1){//after 
  					$str_next = '+2 day';
  				}else{//before
  					$str_next = '-1 day';//thu
  				}
  				
  				$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  				$date_next =  $d->format( 'Y-m-d' );
  				return $date_next;
  			}else{//sun not working continue to monday // but not check if mon day not working
  				if($holiday_option==1){//after
  					$str_next = '+1 day';
  				}else{//before
  					$str_next = '-1 day';//thu
  				}
  				$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  				$date_next =  $d->format( 'Y-m-d' );
  				return $date_next;
  			}
  		}else{
  			return $date_next;
  		}
  		
  	}
  	else{
  		$d = new DateTime($date_next);
  		$day_work = date("D",strtotime($date_next));
  	    if($day_work=='Sat' OR $day_work=='Sun' ){
  	    	if(($day_work=='Sat' AND $array['work_saturday']==1) OR ($day_work=='Sun' AND $array['work_sunday']==1)){//sat working
  	    		return $date_next;
  	    	}else if($day_work=='Sat' AND $array['work_saturday']==0){//sat not working
  	    		$str_next = '+2 day';
  	    		$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  	    		$date_next =  $d->format( 'Y-m-d' );
  	    		return $date_next;
  	    	}else{//sun not working continue to monday // but not check if mon day not working
  	    		$str_next = '+1 day';
  	    		$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  	    		$date_next =  $d->format( 'Y-m-d' );
  	    		return $date_next;
  	    	}
  	    }else{
  	    	return $date_next;
  	    }
  	}
  }
  public function CountDayByDate($start,$end){
  	$db = new Application_Model_DbTable_DbGlobal();
  	return ($db->countDaysByDate($start,$end));
  }
  public function CurruncyTypeOption(){
  	$db = $this->getAdapter();
  	$rows=array(2=>"ដុល្លា",3=>"បាត",1=>"រៀល");
  	$option='';
  	if(!empty($rows))foreach($rows as $key=>$value){
  		$option .= '<option value="'.$key.'" >'.htmlspecialchars($value, ENT_QUOTES).'</option>';
  	}
  	return $option;
  }
  public function getSystemSetting($keycode){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ln_system_setting` WHERE keycode ='".$keycode."'";
  	return $db->fetchRow($sql);
  }
  static function getPaymentTermById($id=null){
  	$arr = array(
  			1=>"ថ្ងៃ",
  			2=>"អាទិត្យ",
  			3=>"ខែ");
  	if($id!=null){
		return $arr[$id];
  	}
  	return $arr;
  	
  }
  function getVehicle(){
  	$db = $this->getAdapter();
  	$sql = "SELECT 
			  v.`reffer`,
			  v.`frame_no`,
			  m.`title` AS make,
			  md.`title` AS model,
			  sm.`title` AS sub_model,
  				v.`img_front_right`,
  			 v.`img_seat`,
  			 v.`id`
			FROM
			  `ldc_vehicle` AS v,
			  `ldc_make` AS m,
			  `ldc_model` AS md,
			  `ldc_submodel` AS sm 
			WHERE v.`make_id` = m.`id` 
			  AND v.`sub_model` = sm.`id` 
			  AND v.`model_id` = md.`id` 
  			  AND v.`status`=1 ";
  	$order=' ORDER BY v.`date` DESC';
  	return $db->fetchAll($sql.$order);
  }
  function getVehicleDetail($id){
  	$db = $this->getAdapter();
  	$sql = "SELECT
			  v.*,
			  m.`title` AS make,
			  md.`title` AS model,
			  sm.`title` AS sub_model
			FROM
			  `ldc_vehicle` AS v,
			  `ldc_make` AS m,
			  `ldc_model` AS md,
			  `ldc_submodel` AS sm
			WHERE v.`make_id` = m.`id`
			  AND v.`sub_model` = sm.`id`
			  AND v.`model_id` = md.`id` 
  			  AND v.`id`=$id ";
  	return $db->fetchRow($sql);
  }
  function getAllMake(){
  	$db = $this->getAdapter();
  	$sql = " SELECT id ,title AS name FROM ldc_make WHERE status = 1";
  	$order=' ORDER BY id DESC';
  	return $db->fetchAll($sql.$order);
  }
  function getAllModels(){
  	$db = $this->getAdapter();
  	$sql = " SELECT id,title AS name FROM ldc_model WHERE status = 1";
  	return  $db->fetchAll($sql);
  }
  function ajaxaddMake($data){
  	$this->_name='ldc_make';
  	$db = $this->getAdapter();
	  	$arr = array(
	  			'title'=>$data['txt_make'],
	  			'status'=>1
	  			
	  	);
  	return $this->insert($arr);
  }
  function ajaxaddModel($data){
  	$this->_name='ldc_model';
  	$db = $this->getAdapter();
  	$arr = array(
  			'brand_id'=>$data['txt_makeid'],
  			'title'=>$data['txt_model'],
  			'status'=>1
  
  	);
  	return $this->insert($arr);
  }
  function getViews($type=2){
  	$db=$this->getAdapter();
  	$sql="SELECT key_code,name_en FROM ldc_view WHERE `type`=$type";
  	return $db->fetchAll($sql);
  }
  function getAllLocationByProvince($province,$opt=null){
  	$db = $this->getAdapter();
  	$sql = "SELECT id ,location_name FROM `ldc_package_location` WHERE STATUS=1 AND province_id=$province AND is_package!=1 ";
  	$row =  $db->fetchAll($sql);
  	if($opt!=null){
  		$option='';
  		foreach($row as $r){
  			$option .= '<option value="'.$r['id'].'">'.htmlspecialchars($r['location_name'], ENT_QUOTES).'</option>';
  		}
  		return $option;
  	}else {
  		return $row;
  	}
  }
  function getmenucontact(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE `type`= 3";
  	return $db->fetchAll($sql);
  }
  function getmenucontactFooter(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE id=3 AND `type`= 3";
  	return $db->fetchAll($sql);
  }
  function getAllGuestBookFooter($limit=null){
  	$db = $this->getAdapter();
  	$sql = "SELECT id,name,subject,email,website,comment,date FROM `ldc_guestbook` WHERE subject !='' ";
  	if($limit !=null){
  		$sql.=" AND status =1 ORDER BY id DESC ";
  	}else{
  		$sql.=" AND status =1 ORDER BY id DESC LIMIT 2";
  	}
  	
  	return $db->fetchAll($sql);
  }
  function getmenuabout(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE `type`= 2";
  	return $db->fetchAll($sql);
  }
  function getmenuService(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE `type`= 2";
  	return $db->fetchAll($sql);
  }
  function getmenuaboutFooter(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE id=4 AND `type`= 2";
  	return $db->fetchAll($sql);
  }
  function getAllJob(){
  	$db = $this->getAdapter();
  	$sql = "SELECT j.`id`,j.`job_title`,j.`salary`,j.`hiring`,j.`closting_date`,j.`job_type` FROM `ldc_job` AS j WHERE j.`status`=1 ORDER BY j.`posting_date` DESC";
  	return $db->fetchAll($sql);
  }
  function getJobById($id){
  	$id= addslashes($id);
  	$db = $this->getAdapter();
  	$sql = "SELECT j.* FROM `ldc_job` AS j WHERE j.id= $id ";
  	return $db->fetchRow($sql);
  }
  public function getAvailableVehicle($data){
  	$db = $this->getAdapter();
  	$pickup_date = $data["pickup_date"];
  	$return_date = $data["return_date"];
  	$return_time = $data["return_time"];
  	$sql = "SELECT v.id FROM `ldc_vehicle`AS v WHERE v.`id` NOT IN(SELECT bd.`item_id` FROM ldc_booking AS b , `ldc_booking_detail` AS bd WHERE b.id=bd.`book_id` AND '$pickupdate' BETWEEN b.`pickup_date` AND b.`return_date` AND '$returndate ' BETWEEN b.`pickup_date` AND b.`return_date` AND bd.item_type=1 AND b.status!=3) ";
  	return $db->fetchRow($sql);
  }
  public function getAllAvailableVehicle($data){
  	$db = $this->getAdapter();
  	$pickup_date = new DateTime($data["pickup_date"]);
  	$return_date = new DateTime($data["return_date"]);
  	
  	$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
  	$returndate = $return_date->format('Y-m-d');
  	
  	$pickuptime = $data["pickup_time"];
  	$returntime = $data["return_time"];
  	$sql = "SELECT v.id,v.`reffer`,v.`frame_no`,v.`max_weight`,
  		v.`seat_amount`,v.`color`,v.`year`,v.`steering`,v.`test_url`,v.`show_url`,
  		v.`img_front_right`,v.img_seat,
  		v.`is_promotion`,v.`discount`,(SELECT m.title FROM `ldc_make` AS m WHERE m.id=v.`make_id`)
  		AS make,(SELECT md.title FROM `ldc_model` AS md WHERE md.id=v.`model_id`) AS model,
  		(SELECT sm.title FROM `ldc_submodel` AS sm WHERE sm.id=v.`sub_model`) AS sub_model,
  		(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transmission,
  		(SELECT vt.`title` FROM `ldc_vechicletye` AS vt WHERE vt.id=v.`car_type` LIMIT 1) AS `type`,
  		(SELECT e.`capacity` FROM `ldc_engince` AS e WHERE e.id=v.`engine`) AS `engine` FROM `ldc_vehicle` AS v WHERE v.is_sale !=1 AND v.status=1 AND v.id 
  		NOT IN(SELECT bd.`item_id` FROM ldc_booking AS b , `ldc_booking_detail` AS bd WHERE b.id=bd.`book_id` AND '$pickupdate' BETWEEN b.`pickup_date` AND b.`return_date` AND '$returndate ' BETWEEN b.`pickup_date` AND b.`return_date` AND bd.item_type=1 AND b.status!=3)"; // it wiil include with new version AND b.`return_time` >= '$returntime'
  	$row = $db->fetchAll($sql);
  	if(!empty($row)){
  		return $db->fetchAll($sql);
  	}
  }
  public function getAllSaleVehicle(){
  	$db = $this->getAdapter();
  	$sql="
  	SELECT 
             v.id,v.`reffer`,v.`frame_no`,
             licence_plate,max_weight,seat_amount,org_cost,chassis_no,
             (SELECT e.capacity FROM `ldc_engince` AS e WHERE e.id=v.engine) AS engine
             ,transmission,of_axlex,of_cylinder,cylinders_dip,
             (SELECT type FROM `ldc_type` WHERE id = v.type AND STATUS=1) AS type,
             (SELECT t.title FROM `ldc_vechicletye` AS t WHERE t.id=v.car_type) AS car_type,
             steering,test_url,show_url,description,
             img_front_left,img_front_right,img_rear_left,img_rear_right,img_passenger,img_trunk,img_seat,
             img_sr,img_luggage,img_sl,img_sr,img_rear,img_front,engine_number,
  			 v.`color`,v.`year`,v.`steering`,
  			(SELECT m.title FROM `ldc_make` AS m WHERE m.id=v.`make_id`) AS make,
  			(SELECT md.title FROM `ldc_model` AS md WHERE md.id=v.`model_id`) AS model,
  			(SELECT sm.title FROM `ldc_submodel` AS sm WHERE sm.id=v.`sub_model`) AS sub_model,
  			(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transmission,
  			sale_price,description_sale
  		    FROM `ldc_vehicle` AS v WHERE is_sale =1 AND v.status=1 ";
//   	$sql = "SELECT v.id,v.`reffer`,v.`frame_no`,v.`max_weight`,
//   	v.`seat_amount`,v.`color`,v.`year`,v.`steering`,v.`test_url`,v.`show_url`,
//   	v.`img_front_right`,v.img_seat,
//   	v.`is_promotion`,v.`discount`,(SELECT m.title FROM `ldc_make` AS m WHERE m.id=v.`make_id`)
//   	AS make,(SELECT md.title FROM `ldc_model` AS md WHERE md.id=v.`model_id`) AS model,
//   	(SELECT sm.title FROM `ldc_submodel` AS sm WHERE sm.id=v.`sub_model`) AS sub_model,
//   	(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transmission,
//   	(SELECT vt.`title` FROM `ldc_vechicletye` AS vt WHERE vt.id=v.`type`) AS `type`,
//   	(SELECT e.`capacity` FROM `ldc_engince` AS e WHERE e.id=v.`engine`) AS `engine` FROM `ldc_vehicle` AS v WHERE v.is_sale =1 AND v.status=1 ";
  	$row = $db->fetchAll($sql);
  	if(!empty($row)){
  		return $db->fetchAll($sql);
  	}
  }
  public function getallSemilarVehicle(){
  	$db = $this->getAdapter();
  	$sql="SELECT v.id,v.`reffer`,v.`frame_no`,img_front_right,
	  	v.`color`,v.`steering`,v.year,seat_amount ,
	  	(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transmission,
	  	(SELECT t.title FROM `ldc_vechicletye` AS t WHERE t.id=v.car_type) AS car_type,
	  	(SELECT m.title FROM `ldc_make` AS m WHERE m.id=v.`make_id`) AS make,
	  	(SELECT md.title FROM `ldc_model` AS md WHERE md.id=v.`model_id`) AS model,
	  	(SELECT sm.title FROM `ldc_submodel` AS sm WHERE sm.id=v.`sub_model`) AS sub_model
	  	FROM `ldc_vehicle` AS v WHERE is_sale != 1 AND v.status=1 ";
  	return $db->fetchAll($sql);
  }
  public function geVehicleById($vehicle_id){
  	$db = $this->getAdapter();
  	$sql = "SELECT 
             v.id,v.`reffer`,v.`frame_no`,v.ordering,
             licence_plate,max_weight,seat_amount,org_cost,chassis_no,
             (SELECT e.capacity FROM `ldc_engince` AS e WHERE e.id=v.engine) AS engine
             ,transmission,
             (SELECT type FROM `ldc_type` WHERE id = v.type AND STATUS=1) AS type,
             (SELECT t.title FROM `ldc_vechicletye` AS t WHERE t.id=v.car_type) AS car_type,
             steering,test_url,show_url,description,
             img_front_left,img_front_right,img_rear_left,img_rear_right,img_passenger,img_trunk,img_seat,
             img_sr,img_luggage,img_sl,img_sr,img_rear,img_front,engine_number,
  			 v.`color`,v.`year`,v.`steering`,of_axlex,of_cylinder,cylinders_dip,
  			(SELECT m.title FROM `ldc_make` AS m WHERE m.id=v.`make_id`) AS make,
  			(SELECT md.title FROM `ldc_model` AS md WHERE md.id=v.`model_id`) AS model,
  			(SELECT sm.title FROM `ldc_submodel` AS sm WHERE sm.id=v.`sub_model`) AS sub_model,
  			(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transmission
  		    FROM `ldc_vehicle` AS v WHERE v.id =$vehicle_id LIMIT 1";
  	return $row = $db->fetchRow($sql);
  }
  public function getVehiclePrice($day,$vehicle_id){
  	$db = $this->getAdapter();
  	$sql="SELECT v.`price`,v.`extraprice`,v.`vat_value`  FROM `ldc_vehiclefee_detail` AS v WHERE v.`packageday_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$day AND r.`to_amountday`>=$day LIMIT 1) AND v.`vehicle_id` = $vehicle_id";
  	$row = $db->fetchRow($sql);
  	if(empty($row)){
  		$sql="SELECT v.`price`,v.`extraprice`,v.`vat_value`  FROM `ldc_vehiclefee_detail` AS v WHERE v.`packageday_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`is_morethen`=1 LIMIT 1) AND v.`vehicle_id` = $vehicle_id";
  		return $db->fetchRow($sql);
  	}else{
  		return $row;
  	}
  }
  
  public function getAvailableDriver($data){
  	$db= $this->getAdapter();
  	$pickup_date = new DateTime($data["pickup_date"]);
  	$return_date = new DateTime($data["return_date"]);
  	
  	$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
  	$returndate = $return_date->format('Y-m-d');
  	
  	$returntime = $data["return_time"];
  	$sql="SELECT d.`id`,d.`driver_id`,CONCAT(d.`first_name`,' ',d.`last_name`) AS `name`,d.`experience_desc`,d.`sex`,d.`nationality`,d.`lang_note`,d.`tel`,d.`email`,d.`photo`,d.`c_holidayprice`,d.`c_normalprice`,d.`c_otprice`,d.`c_weekendprice`,d.`p_holidayprice`,d.`p_normalprice`,d.`p_otprice`,d.`p_weekendprice` 
  	FROM `ldc_driver` AS d WHERE d.id NOT IN(SELECT bd.`item_id` FROM ldc_booking AS b , `ldc_booking_detail` AS bd WHERE b.id=bd.`book_id` AND '$pickupdate' BETWEEN b.`pickup_date` AND b.`return_date` AND '$returndate ' BETWEEN b.`pickup_date` AND b.`return_date` AND bd.item_type=2 AND b.status!=3) AND d.`status`=1"; // AND b.`return_time` >= '$returntime'
  	return $db->fetchAll($sql);
  }
  public function getEquipment($data){
  	$db= $this->getAdapter();
  	$pickup_date = new DateTime($data["pickup_date"]);
  	$return_date = new DateTime($data["return_date"]);
  	
  	$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
  	$returndate = $return_date->format('Y-m-d');
  	$returntime = $data["return_time"];
  	
  	$pickupdates=date_create($data["pickup_date"]);
  	$returndates =date_create($data["return_date"]);
  	$diff=date_diff($pickupdates,$returndates);
  	$day = $diff->format("%a%")+1;
  	$sql_rankday = "SELECT r.`id` FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$day AND r.`to_amountday`>=$day LIMIT 1";
  	$row_randay = $db->fetchOne($sql_rankday);
  	if($row_randay){
  		$row_randay;
  	}else{
  		$sql_rankday = "SELECT r.`id` FROM `ldc_rankday` AS r WHERE r.`is_morethen`=1 LIMIT 1";
  		$row_randay=$db->fetchOne($sql_rankday);
  	}
        $sql="SELECT d.`id`,d.`equipment_name`,d.`reference_no`,d.`photo_front`,d.`url`,(SELECT st.price FROM `ldc_stuff_details` AS st WHERE st.`stuff_id`=d.`id` AND st.`package_id`=$row_randay limit 1) AS price,(SELECT st.`extra_price` FROM `ldc_stuff_details` AS st WHERE st.`stuff_id`=d.`id` AND st.`package_id`=$row_randay limit 1) AS extra_price  FROM `ldc_stuff` AS d WHERE d.`status`=1";
  	
  	return $db->fetchAll($sql);
  }
  function getAllSlider(){
  	$db = $this->getAdapter();
  	$sql = "SELECT id ,title,caption,ordering,img,
  	(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_slider.`status` limit 1 ) AS status,is_showcaption
  	FROM `ldc_slider` WHERE status=1 ";
  	return $db->fetchAll($sql);
  }
  function getAllPrayerList(){
  	$db = $this->getAdapter();
  	$sql = "SELECT id,title,descript FROM `ldc_playerlist` WHERE STATUS=1 ";
  }
  function getLocatioNameById($id){
  	$db = $this->getAdapter();
  	$sql="SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=$id";
  	return $db->fetchOne($sql);
  	
  }
  function addVisualBookingStepOne($data){
  	$db = $this->getAdapter();
  	$pickup_date = new DateTime($data["pickup_date"]);
  	$return_date = new DateTime($data["return_date"]);
  	$arr = array(
		'pickup_location'	=>	$data["pickup_location"],
	  		'dropoff_location'	=>	$data["return_location"],
	  		'pickup_date'		=>	$pickup_date->format('Y-m-d'),
	  		'pickup_time'		=>	$data["pickup_time"].":".$data["pickup_minute"].":00",
	  		'return_date'		=>	$return_date->format('Y-m-d'),
	  		'return_time'		=>	$data["return_time"].":".$data["return_minute"].":00",
	  		'date_book'			=>	new Zend_Date(),
  		'user_ip'			=>	$data["public_ip"]."-".$data["ip_address"]		
  	);
  	$this->_name="ldc_visurl_booking";
  	$this->insert($arr);
  }
  function updateVisualBookingStepOne($data,$user_ip){
  	$db = $this->getAdapter();
  	$pickup_date = new DateTime($data["pickup_date"]);
  	$return_date = new DateTime($data["return_date"]);
  	
  	// $user_ip = $data["public_ip"]."-".$data["ip_address"];
  	$arr = array(
  			'pickup_location'	=>	$data["pickup_location"],
	  		'dropoff_location'	=>	$data["return_location"],
	  		'pickup_date'		=>	$pickup_date->format('Y-m-d'),
	  		'pickup_time'		=>	$data["pickup_time"].":".$data["pickup_minute"].":00",
	  		'return_date'		=>	$return_date->format('Y-m-d'),
	  		'return_time'		=>	$data["return_time"].":".$data["return_minute"].":00",
	  		'date_book'			=>	new Zend_Date(),
  			//'user_ip'			=>	$data["public_ip"]."-".$data["ip_address"]
  	);
  	$this->_name="ldc_visurl_booking";
  	$where = $db->quoteInto("user_ip=?", $user_ip);
  	$this->update($arr, $where);
  }
  
  function getFirstStepData($user_ip){
  	//$user_ip = $post["public_ip"]."-".$post["ip_address"];
  	$db = $this->getAdapter();
  	$sql = "SELECT vb.`pickup_date`,vb.`return_date`,(SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=vb.`pickup_location`) AS pickup_location,vb.`pickup_time`,vb.`return_time`,(SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=vb.`dropoff_location`) AS return_location FROM `ldc_visurl_booking` AS vb where vb.`user_ip`='$user_ip'";
  	return $db->fetchRow($sql);
  }
  
  
  public function addVisualBookingStepTwo($user_ip,$vehicle_id){
  	$db = $this->getAdapter();
  	$db_globle = new Application_Model_DbTable_DbGlobal();
  	$data_first_step = $this->getFirstStepData($user_ip);
  	$pickup_date = date_create($data_first_step["pickup_date"]);
  	$return_date = date_create($data_first_step["return_date"]);
  	
  	$diff=date_diff($pickup_date,$return_date);
	$total_day = $diff->format("%a%")+1;
  	$sql="SELECT v.`price`,v.`extraprice`,v.`vat_value`  FROM `ldc_vehiclefee_detail` AS v WHERE v.`packageday_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$total_day AND r.`to_amountday`>=$total_day LIMIT 1) AND v.`vehicle_id` = $vehicle_id";
  	$row = $db->fetchRow($sql);
  	if(empty($row)){
  		$sql="SELECT v.`price`,v.`extraprice`,v.`vat_value`  FROM `ldc_vehiclefee_detail` AS v WHERE v.`packageday_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`is_morethen`=1 limit 1) AND v.`vehicle_id` = $vehicle_id";
  		$row_data = $db->fetchRow($sql);
  	}else{
  		$row_data = $row;
  	}
  	$total_price = $row_data["price"]*$total_day;
  	$total_vat = $total_price*($row_data["vat_value"]/100);
  	$deposit = (($total_price+$total_vat)*(50/100));
    $bank_charge = $deposit*(3/100);
  	$deposite = (($total_price+$total_vat)*(50/100))+ $bank_charge;
  	
  	$sqlv ="SELECT (SELECT m.`title` FROM `ldc_make` AS m WHERE m.id = v.`make_id`) AS make,(SELECT m.`title` FROM `ldc_model` AS m WHERE m.id = v.`model_id`) AS model,(SELECT s.`title` FROM `ldc_submodel`AS s WHERE s.id = v.`sub_model`) AS sub_model,(SELECT v.`title` FROM `ldc_vechicletye` AS v WHERE v.`id`=v.`car_type`) AS car_type,(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transation,v.`reffer`,v.ordering FROM `ldc_vehicle` AS v WHERE v.`id`=$vehicle_id";
  	$row_vehicle = $db->fetchRow($sqlv);
  	//print_r($row_data);
  	$arr = array(
  		'user_id'			=>	$user_ip,
  		'item_id'			=>	$vehicle_id,
  		'item_name'			=>	$row_vehicle["make"]." ".$row_vehicle["model"]." ".$row_vehicle["sub_model"]." ".$row_vehicle["car_type"]." ".$row_vehicle["transation"]."(".$row_vehicle["reffer"].")",
  		'price'				=>	$row_data["price"],
  		'VAT'				=>	$row_data["vat_value"],
  		'item_type'			=>	1,
  		'number_of_rent'	=>	1,
  		'deposite'			=>	$deposite,
  		'refund_deposit'	=>	$row_vehicle["ordering"],
  		'total'				=>	$total_price+$total_vat,
  		'total_paymented'	=>	$deposite,
  	);
  	$this->_name = "ldc_visurl_booking_detail";
  	$this->insert($arr);
  }
  
  public function updateVisualBookingStepTwo($user_ip,$vehicle_id){
  	$db = $this->getAdapter();
  	$db_globle = new Application_Model_DbTable_DbGlobal();
  	$data_first_step = $this->getFirstStepData($user_ip);
  	$pickup_date = date_create($data_first_step["pickup_date"]);
  	$return_date = date_create($data_first_step["return_date"]);
  	$row_vehicle= $db_globle->getVehiclePriceSelected($user_ip);
  	 
  	$diff=date_diff($pickup_date,$return_date);
  	$total_day = $diff->format("%a%")+1;
  	$sql="SELECT v.`price`,v.`extraprice`,v.`vat_value`  FROM `ldc_vehiclefee_detail` AS v WHERE v.`packageday_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$total_day AND r.`to_amountday`>=$total_day LIMIT 1) AND v.`vehicle_id` = $vehicle_id";
  	$row = $db->fetchRow($sql);
  	if(empty($row)){
  		$sql="SELECT v.`price`,v.`extraprice`,v.`vat_value`  FROM `ldc_vehiclefee_detail` AS v WHERE v.`packageday_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`is_morethen`=1 LIMIT 1) AND v.`vehicle_id` = $vehicle_id";
  		$row_data = $db->fetchRow($sql);
  	}else{
  		$row_data = $row;
  	}
  	$sqlv ="SELECT (SELECT m.`title` FROM `ldc_make` AS m WHERE m.id = v.`make_id`) AS make,(SELECT m.`title` FROM `ldc_model` AS m WHERE m.id = v.`model_id`) AS model,(SELECT s.`title` FROM `ldc_submodel`AS s WHERE s.id = v.`sub_model`) AS sub_model,(SELECT v.`title` FROM `ldc_vechicletye` AS v WHERE v.`id`=v.`car_type`) AS car_type,(SELECT t.`tran_name` FROM `ldc_transmission` AS t WHERE t.`id`=v.`transmission`) AS transation,v.`reffer`,v.ordering FROM `ldc_vehicle` AS v WHERE v.`id`=$vehicle_id";
  	$row_vehicle = $db->fetchRow($sqlv);
  	//print_r($sql);
  	$total_price = $row_data["price"]*$total_day;
 	$total_vat = $total_price*($row_data["vat_value"]/100);
        $bank_charge = ($total_price+$total_vat)*(3/100);
 	$deposite = (($total_price+$total_vat)*(50/100))+$bank_charge ;
  	$arr = array(
  			//'user_id'	=>	$user_ip,
  			'item_id'	=>	$vehicle_id,
  			'item_name'	=>	$row_vehicle["make"]." ".$row_vehicle["model"]." ".$row_vehicle["sub_model"]." ".$row_vehicle["car_type"]." ".$row_vehicle["transation"]."(".$row_vehicle["reffer"].")",
  			'price'		=>	$row_data["price"],
  			'VAT'		=>	$row_data["vat_value"],
  			'item_type'	=>	1,
  			'number_of_rent'	=>	1,
  			'deposite'	=>	$deposite,
  			'refund_deposit'	=>	$row_vehicle["ordering"],
  			'total'	=>	$total_price+$total_vat,
  			'total_paymented'	=>	$deposite,
  	);
  	$this->_name = "ldc_visurl_booking_detail";
  	$where = array("user_id=?"=>$user_ip,"item_type=?"=>1);
  	$this->update($arr, $where);
  }
  
  public function getVehiclePriceSelected($user_ip){
  	$db = $this->getAdapter();
  	$sql="SELECT vb.`price`,vb.`VAT`,vb.`number_of_rent`,vb.refund_deposit,(SELECT CONCAT((SELECT m.`title` FROM `ldc_model` AS m WHERE m.`id`=v.`model_id`),' ',(SELECT sm.`title` FROM `ldc_submodel` AS sm WHERE sm.`id`=v.`sub_model`),' ', v.`reffer`) FROM `ldc_vehicle` AS v WHERE v.`id`=vb.`item_id` LIMIT 1) AS vehicle_name FROM `ldc_visurl_booking_detail` AS vb WHERE vb.`item_type`=1 AND vb.`user_id`='$user_ip'";
  	$row = $db->fetchRow($sql);
  	if(!empty($row)){
  		return $row;
  	}
  }
  
  public function getProductAndServicesSelected($user_ip,$type){
  	$db = $this->getAdapter();
  	if ($type==2){
  		$sql="SELECT vb.`price`,vb.`VAT`,vb.`number_of_rent`,vb.refund_deposit,(SELECT CONCAT(d.`first_name`,' ',d.`last_name`) FROM `ldc_driver` AS d WHERE d.`id`=vb.`item_id` LIMIT 1) AS driver_name FROM `ldc_visurl_booking_detail` AS vb WHERE vb.`item_type`=2 AND vb.`user_id`='$user_ip'";
  	}else{
  		$sql="SELECT vb.`price`,vb.`VAT`,vb.`number_of_rent`,vb.refund_deposit,(SELECT d.`equipment_name` FROM `ldc_stuff` AS d WHERE d.`id`=vb.`item_id` LIMIT 1) AS euipment_name FROM `ldc_visurl_booking_detail` AS vb WHERE vb.`item_type`=3 AND vb.`user_id`='$user_ip'";
  	}
  	$row = $db->fetchAll($sql);
  	if(!empty($row)){
  		return $row;
  	}
  }
  
  public function addVisualBookingStebthree($user_ip,$data){
  	$db = $this->getAdapter();
  	$data_first_step = $this->getFirstStepData($user_ip);
  	$pickup_date = date_create($data_first_step["pickup_date"]);
  	$return_date = date_create($data_first_step["return_date"]);
  	 
  	$diff=date_diff($pickup_date,$return_date);
  	$total_day = $diff->format("%a%")+1;
  	
  	$identity_equipment = explode(',',$data['identity_equipment']);
  	$identity_driver = explode(',',$data['identity_driver']);
  	if(!empty($data['identity_equipment']) OR $data['identity_equipment']!=""){
  		foreach ($identity_equipment as $i){
  			$equipment_id = $data["equipmentid_".$i];
		  	$sql="SELECT (SELECT s.`equipment_name` FROM `ldc_stuff` AS s WHERE s.`id`= v.`stuff_id`) AS epment_name ,v.`price`,v.`extra_price`  FROM `ldc_stuff_details` AS v WHERE v.`package_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$total_day AND r.`to_amountday`>=$total_day LIMIT 1) AND v.`stuff_id` =$equipment_id";
		  	$row = $db->fetchRow($sql);
		  	if(empty($row)){
		  		$sql="SELECT (SELECT s.`equipment_name` FROM `ldc_stuff` AS s WHERE s.`id`= v.`stuff_id`) AS epment_name ,v.`price`,v.`extra_price`  FROM `ldc_stuff_details` AS v WHERE v.`package_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$total_day AND r.`to_amountday`>=$total_day LIMIT 1) AND v.`stuff_id` =$equipment_id";
		  		$row_data = $db->fetchRow($sql);
		  	}else{
		  		$row_data = $row;
		  	}
		  	//print_r($row_data);
            $total_price = $row_data["price"]*$total_day;
            $deposit = $total_price*(50/100);
            $bank_charge = $deposit*(3/100);
  			$deposite = ($total_price*(50/100))+ $bank_charge;
		  	
		  	$arr = array(
		  			'user_id'			=>	$user_ip,
		  			'item_id'			=>	$equipment_id,
		  			'price'				=>	$row_data["price"],
		  			'item_name'			=>	$row_data["epment_name"],
		  			'item_type'			=>	3,
		  			'number_of_rent'	=>	$data["number_equipment_".$i],
		  			'deposite'			=>	$deposite,
		  			'total'				=>	$total_price,
		  			'total_paymented'	=>	$deposite,
		  	);
		  	$this->_name = "ldc_visurl_booking_detail";
		  	$this->insert($arr);
  		}
  	}
  	
  	if(!empty($data['identity_driver']) OR $data['identity_driver']!=""){
  		foreach ($identity_driver as $i){
  			$driver_id = $data["driverid_".$i];
  			$sql="SELECT CONCAT(d.`first_name`,' ',d.`last_name`) AS driver_name ,d.`p_normalprice` FROM ldc_driver AS d WHERE d.id=$driver_id";
  			$row = $db->fetchRow($sql);

  			$total_price = $row["p_normalprice"]*$total_day;
            $deposit = $total_price*(50/100);
            $bank_charge = $deposit*(3/100);
  			$deposite = ($total_price*(50/100))+ $bank_charge;
  			
  			$arr = array(
  					'user_id'			=>	$user_ip,
  					'item_id'			=>	$driver_id,
  					'item_name'			=>	"Chauffeur Rental (".$row["driver_name"].")",
  					'price'				=>	$row["p_normalprice"],
  					//'VAT'		=>	$row_data["vat_value"],
  					'item_type'			=>	2,
  					'number_of_rent'	=>	1,
  					'deposite'			=>	$deposite,
  					'total'				=>	$total_price,
  					'total_paymented'	=>	$deposite,
  			);
  			$this->_name = "ldc_visurl_booking_detail";
  			$this->insert($arr);
  		}
  	}
  	
  }
  
  public function updateVisualBookingStebthree($user_ip,$data){
  	$db = $this->getAdapter();
  	$db->beginTransaction();
  	try {
  	$data_first_step = $this->getFirstStepData($user_ip);
  	$pickup_date = date_create($data_first_step["pickup_date"]);
  	$return_date = date_create($data_first_step["return_date"]);
  
  	$diff=date_diff($pickup_date,$return_date);
  	$total_day = $diff->format("%a%")+1;
  	
  	$sqls = "DELETE FROM ldc_visurl_booking_detail WHERE user_id = '$user_ip' AND item_type IN(2,3)";
  	$db->query($sqls);
  	$identity_equipment = explode(',',$data['identity_equipment']);
  	$identity_driver = explode(',',$data['identity_driver']);
  	if(!empty($data['identity_equipment']) OR $data['identity_equipment']!="" OR $data['identity_equipment']!=null){
  		foreach ($identity_equipment as $i){
  			$equipment_id = $data["equipmentid_".$i];
  			$sql="SELECT (SELECT s.`equipment_name` FROM `ldc_stuff` AS s WHERE s.`id`= v.`stuff_id`) AS epment_name ,v.`price`,v.`extra_price`  FROM `ldc_stuff_details` AS v WHERE v.`package_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$total_day AND r.`to_amountday`>=$total_day LIMIT 1) AND v.`stuff_id` =$equipment_id";
  			$row = $db->fetchRow($sql);
  			if(empty($row)){
  				$sql="(SELECT s.`equipment_name` FROM `ldc_stuff` AS s WHERE s.`id`= v.`stuff_id`) AS epment_name ,SELECT v.`price`,v.`extra_price`  FROM `ldc_stuff_details` AS v WHERE v.`package_id`=(SELECT r.id FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$total_day AND r.`to_amountday`>=$total_day LIMIT 1) AND v.`stuff_id` =$equipment_id";
  				$row_data = $db->fetchRow($sql);
  			}else{
  				$row_data = $row;
  			}
  			$total_price = $row_data["price"]*$total_day;
  			$deposit = $total_price*(50/100);
  			$bank_charge = $deposit*(3/100);
  			$deposite = ($total_price*(50/100))+ $bank_charge;
  			
  			$arr = array(
  					'user_id'			=>	$user_ip,
  					'item_id'			=>	$equipment_id,
  					'item_name'			=>	$row_data["epment_name"],
  					'price'				=>	$row_data["price"],
  					'item_type'			=>	3,
  					'number_of_rent'	=>	$data["number_equipment_".$i],
  					'deposite'			=>	$deposite,
  					'total'				=>	$total_price,
  					'total_paymented'	=>	$deposite,
  			);
  			$this->_name = "ldc_visurl_booking_detail";
  			$this->insert($arr);
  		}
  	}
  	if(!empty($data['identity_driver']) OR $data['identity_driver']!=""){
  		foreach ($identity_driver as $i){
  			$driver_id = $data["driverid_".$i];
  			$sql="SELECT CONCAT(d.first_name,' ',d.last_name) AS driver_name,d.`p_normalprice` FROM ldc_driver AS d WHERE d.id=$driver_id";
  			$row = $db->fetchRow($sql);
  			$total_price = $row["p_normalprice"]*$total_day;
  			$deposit = $total_price*(50/100);
            $bank_charge = $deposit*(3/100);
  			$deposite = ($total_price*(50/100))+ $bank_charge;
  			$arr = array(
  					'user_id'		=>	$user_ip,
  					'item_id'		=>	$driver_id,
  					'item_name'		=>	"Chauffeur Rental (".$row["driver_name"].")",
  					'price'			=>	$row["p_normalprice"],
  					'item_type'		=>	2,
  					'number_of_rent'	=>	1,
  					'deposite'		=>	$deposite,
  					'total'			=>	$total_price,
  					'total_paymented'	=>	$deposite,
  			);
  			$this->_name = "ldc_visurl_booking_detail";
  			$this->insert($arr);
  		}
  	}
  	 $db->commit();
  }catch (Exception $e){
  	$db->rollBack();
  	$e->getMessage();
  	//exit();
  }
}
public function updateVisualBookingStebSix($user_ip,$data){
	$db = $this->getAdapter();
	$db->beginTransaction();
	$payment_type = $data["payment_type"];
	try {
		if($payment_type==1){
			$arr = array(
				'visa_name'		=>	$data["card_name"],
				'visa_number'	=>	$data["card_id"],
				'secu_code'		=>	$data["secu_code"],
				'exp_date'		=>	$data["card_exp_date"],
				'payment_type'	=>	1,
				'status'		=>	1
			);
		}elseif($payment_type==2){
			$arr = array(
					'visa_number'	=>	$data["code_num"],
					'payment_type'	=>	2,
					'status'		=>	2
			);
		}else{
			$arr = array(
					'payment_type'	=>	3,
					'status'		=>	2
			);
		}
		$where = $db->quoteInto("user_ip=?", $user_ip);
		$this->_name="ldc_visurl_booking";
		$this->update($arr, $where);
		$db->commit();
	}catch (Exception $e){
		$db->rollBack();
		echo $e->getMessage();
		//exit();
	}
}
public function addCuFlight($data,$user_ip){
	$db = $this->getAdapter();
	$arr = array(
			'fly_no'				=> 	$data["fly_no"],
			'fly_date'				=>	$data["fly_date"],
			'fly_time_of_arrival'	=>	$data["fly_time"],
			'fly_destination'		=>	$data["fly_destination"]
	);
	$this->_name="ldc_visurl_booking";
	$where = $db->quoteInto("user_ip=?", $user_ip);
	$this->update($arr, $where);
}
function getAllVisualBooking($user_ip,$type){
	$db = $this->getAdapter();
	if($type==1){//get Visualbooking
		$sql="SELECT v.* FROM `ldc_visurl_booking` AS v WHERE v.`user_ip`='$user_ip'";
		$row = $db->fetchRow($sql);
	}else{ //get Visualbooking detail
		$sql="SELECT v.* FROM `ldc_visurl_booking_detail` AS v WHERE v.`user_id`='$user_ip'";
		$row = $db->fetchAll($sql);
	}
	
	if(!empty($row)){
		return $row;
	}
}

function addBooking($user_ip){
	$db = $this->getAdapter();
	$db->beginTransaction();
	try{
	$row_booking = $this->getAllVisualBooking($user_ip, 1);
	$row_booking_detail = $this->getAllVisualBooking($user_ip, 2);
	
	$db_globle = new Application_Model_DbTable_DbGlobal();
	$booking_code = $db_globle->getNewBookingCode();
	$customer_session  = new Zend_Session_Namespace('customer');
	$customer_id = $customer_session->customer_id;
	$pickup_date = date_create($row_booking["pickup_date"]);
	$return_date = date_create($row_booking["return_date"]);
	
	$diff=date_diff($pickup_date,$return_date);
	$total_day = $diff->format("%a%")+1;
	$total =0;
	$total_payment=0;
	$deposit = 0;
	$total_vat = 0;
	foreach ($row_booking_detail as $rs){
		$total += ($rs["price"]*$total_day);
		$total_vat+=((($rs["price"]*$total_day)*$rs["VAT"])/100);
		$deposit += $rs["deposite"]; 
	}
	
	$arr_booking = array(
		'customer_id'	=>	$customer_id,
		'booking_no'	=>	$booking_code,
		'pickup_location'	=> $row_booking["pickup_location"],
		'dropoff_location'	=>	$row_booking["dropoff_location"],
		'date_book'			=>	new Zend_Date(),
		'pickup_date'		=>	$row_booking["pickup_date"],
		'pickup_time'		=>	$row_booking["pickup_time"],
		'return_date'		=>	$row_booking["return_date"],
		'return_time'		=>	$row_booking["return_time"],
		'total_fee'			=>	$total,
		'deposite_fee'		=>	$deposit,
		'total_vat'			=>	$total_vat,
		'total_paymented'	=>	$deposit,
		'status'			=>	1,
		'visa_name'			=>	$row_booking["visa_name"],
		'card_id'			=>	$row_booking["visa_number"],
		'secu_code'			=>	$row_booking["secu_code"],
		'card_exp_date'		=>	$row_booking["exp_date"],
		'payment_type'		=>	$row_booking["payment_type"],
		'status'			=>	$row_booking["status"],
		'item_type'			=>	1,
		'fly_no'				=> 	$row_booking["fly_no"],
		'fly_date'				=>	$row_booking["fly_date"],
		'fly_time_of_arrival'	=>	$row_booking["fly_time_of_arrival"],
		'fly_destination'		=>	$row_booking["fly_destination"]
	);
	$this->_name = "ldc_booking";
	$book_id = $this->insert($arr_booking);
// 	print_r($book_id);exit();
	
	foreach ($row_booking_detail as $rs){
		$arr_booking_detail = array(
				'book_id'			=>	$book_id,
				'item_id'			=> 	$rs["item_id"],
				'item_name'			=> 	$rs["item_name"],
				'rent_num'			=>	$rs["number_of_rent"],
				'price'				=>	$rs["price"],
				'VAT'				=>	$rs["VAT"],
				'discount'			=>	$rs["discount"],
				'deposite'			=>	$rs["deposite"],
				'refund_deposit'	=>	$rs["refund_deposit"],
				'total'				=>	$rs["total"],
				'total_paymented'	=>	$rs["deposite"],
				'item_type'			=>	$rs["item_type"],
				'status'			=>	1,
		);
		$this->_name = "ldc_booking_detail";
		$this->insert($arr_booking_detail);
	}
	$sql = "DELETE FROM ldc_visurl_booking WHERE user_ip='$user_ip'";
	$db->query($sql);
	
	$sqls = "DELETE FROM ldc_visurl_booking_detail WHERE user_id='$user_ip'";
	$db->query($sqls);
	$db->commit();
	return $book_id;
	}catch (Exception $e){
		$db->rollBack();
		echo $e->getMessage();
	}
}
function checkAvailableUserName($data){
	$db = $this->getAdapter();
	$user_name = $data["user_name"];
	$type = $data["type"];
	if($type==1){
		$sql = "SELECT c.`user_account` FROM `ldc_customer` AS c WHERE c.`user_account`='$user_name'";
		$row = $db->fetchOne($sql);
	}else{
		$sql = "SELECT c.`email` FROM `ldc_customer` AS c WHERE c.`email`='$user_name'";
		$row = $db->fetchOne($sql);
	}
	if(!empty($row)){
		return 1;
	}else{
		return 0;
	}
}
function deleteVisaulBooking($user_ip,$type){
	$db = $this->getAdapter();
	if($type==1){
		$sql = "DELETE FROM ldc_visurl_booking WHERE user_ip='$user_ip'";
		$db->query($sql);
	}else{
		$sqls = "DELETE FROM ldc_visurl_booking_detail WHERE user_id='$user_ip'";
		$db->query($sqls);
	}
}
function sumitComment($data){
		$this->_name='ldc_guestbook';
		$db = $this->getAdapter();
	// 	print_r($data);exit();
		$arr = array(
				'name'=>$data['name'],
				'subject'=>$data['subject'],
				'email'=>$data['email'],
				'website'=>$data['website'],
				'comment'=>$data['comments'],
				'date'=>date("d/m/Y"),
				);
		$this->insert($arr);
	}
	
public function getBookingById($id,$type){
	$db = $this->getAdapter();
	if($type==1){
		$sql = 'SELECT b.`booking_no`,DATE_FORMAT(b.`date_book`,"%e/%b/%Y") AS date_book,b.`pickup_date`,b.`return_date`,b.`pickup_time`,b.`return_time`,b.`item_type`,b.`payment_type`,b.total_fee,b.deposite_fee,b.`visa_name`,b.`card_id`,b.`secu_code`,b.`card_exp_date`,
				fly_no,fly_date,fly_time_of_arrival,fly_destination,b.`total_duration`,b.`rental_type`,b.trip_type,
						(SELECT CONCAT(c.`first_name`," ",c.`last_name`) FROM `ldc_customer` AS c WHERE c.`id`= b.`customer_id` LIMIT 1) AS customer_name,
						(SELECT c.`email` FROM `ldc_customer` AS c WHERE c.`id`= b.`customer_id` LIMIT 1) AS customer_email,
						(SELECT c.`phone` FROM `ldc_customer` AS c WHERE c.`id`= b.`customer_id` LIMIT 1) AS customer_phone,
						(SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=b.`pickup_location`) AS pickup_location,
						(SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=b.`dropoff_location`) AS return_location,
						(SELECT p.`location_name` FROM `ldc_package_location` AS p WHERE p.`id`=b.`package_id`) AS package_location
					FROM `ldc_booking` AS b WHERE b.`id` = '.$id;
		return $db->fetchRow($sql);
	}else{
		$sql = "SELECT * FROM `ldc_booking_detail` AS d WHERE d.`book_id`=$id";
		return $db->fetchAll($sql);
	}
}
static function getVehiceRankingDay($vehicle_id){
	        $sql ="SELECT (SELECT CONCAT(from_amountday,'-',to_amountday,' Day') FROM `ldc_rankday` WHERE id=packageday_id) AS package_name,price 
			FROM `ldc_vehiclefee_detail` AS v ,ldc_rankday AS r WHERE v.vehicle_id=$vehicle_id AND v.status=1 AND r.id=v.packageday_id ORDER BY r.ordering ASC";
	        $db = new Application_Model_DbTable_DbGlobal();
	        return $db->getGlobalDb($sql);
}
function getAllCountry(){
	$sql="SELECT * FROM ldc_country WHERE status=1 AND country_name!='' ";
	$db = $this->getAdapter();
	return $db->fetchAll($sql);
}
function OtherBooking($data){
		$this->_name='ldc_other_booking';
		$db = $this->getAdapter();
		// 	print_r($data);exit();
		$arr = array(
				'first_name'		=>	$data["first_name"],
				'last_name'			=>	$data["last_name"],
				'sex'				=>	$data["gender"],
				'occupation'		=>	$data["occupation"],
				'tel'				=>	$data["phone"],
				'email'				=>	$data["email"],
				//'dob'				=>	$data["dob"],
				'card_name'			=>	$data["card_name"],
				'card_num'			=>	$data["card_id"],
				'secu_code'			=>	$data["secu_code"],
				'card_exp'			=>	$data["card_exp_date"],
				'book_fee'			=>	$data["amount_book"],
				'amount'			=>	$data["amount"],
				'service_charge'	=>	$data["service_charge"],
				'total'				=>	$data["total"],
				'purpose'			=>	$data["purpose"],
				'pay_type'		=>	1,
		);
		$this->insert($arr);
	}
}