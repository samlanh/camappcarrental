<?php

class IndexController extends Zend_Controller_Action
{

	const REDIRECT_URL = '/transfer';
	
    public function init()
    {
        /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');  
    	
    }

    public function adminloginAction()
    {
        // action body
    	$this->_helper->layout()->disableLayout();
    	
        /* set this to login page to change the character charset of browsers to Utf-8  ...*/    	  	
		
    	//$this->_helper->layout()->disableLayout();
		$form=new Application_Form_FrmLogin();				
		$form->setAction('index');		
		$form->setMethod('post');
		$form->setAttrib('accept-charset', 'utf-8');
		$this->view->form=$form;
		$key = new Application_Model_DbTable_DbKeycode();
		$this->view->data=$key->getKeyCodeMiniInv(TRUE);		
		
		if($this->getRequest()->isPost())		
		{
			$formdata=$this->getRequest()->getPost();
			if($form->isValid($formdata))
			{
				$session_lang=new Zend_Session_Namespace('lang');
				$session_lang->lang_id=$formdata["lang"];//for creat session
				Application_Form_FrmLanguages::getCurrentlanguage($session_lang->lang_id);//for choose lang for when login
				$user_name=$form->getValue('txt_user_name');
				$password=$form->getValue('txt_password');
				$db_user=new Application_Model_DbTable_DbUsers();
				if($db_user->userAuthenticate($user_name,$password)){					
// 					$this->view->msg = 'Authentication Sucessful!';
// 					$this->view->err="0";
					
					$session_user=new Zend_Session_Namespace('auth');
					$user_id=$db_user->getUserID($user_name);
					$user_info = $db_user->getUserInfo($user_id);
					
					$arr_acl=$db_user->getArrAcl($user_info['user_type']);
					
					
					$session_user->user_id=$user_id;
					$session_user->user_name=$user_name;
					$session_user->pwd=$password;		
					$session_user->level= $user_info['user_type'];
					$session_user->last_name= $user_info['last_name'];
					$session_user->first_name= $user_info['first_name'];
					$session_user->theme_style=$db_user->getThemeByUserId($user_id);
					
					
					$a_i = 0;
					$arr_actin = array();	
// 					print_r($arr_acl);
					for($i=0; $i<count($arr_acl);$i++){
						$arr_module[$i]=$arr_acl[$i]['module'];
// 						if($arr_acl[$i]['module'] == 'exchange'){
// 							if($arr_acl[$i]['action'] == "index" || $arr_acl[$i]['action'] == "add" || $arr_acl[$i]['action'] == "edit" ) {
// 								continue;
// 							}
							$arr_actin[$a_i++] = $arr_acl[$i]['module'].'/'.$arr_acl[$i]['controller'].'/'.$arr_acl[$i]['action'];
// 						}
					}	
// 					print_r($arr_actin);exit();
					$arr_module=(array_unique($arr_module));
					$arr_actin=(array_unique($arr_actin));
// 					print_r($arr_module);	echo "<br />============<br />";
					$arr_module=$this->sortMenu($arr_module);
// 					print_r($arr_module);exit();
// 					print_r($arr_module); exit;
					$session_user->arr_acl = $arr_acl;
					$session_user->arr_module = $arr_module;
					$session_user->arr_actin = $arr_actin;
						
					$session_user->lock();
					
					$log=new Application_Model_DbTable_DbUserLog();
					$log->insertLogin($user_id);
					foreach ($arr_module AS $i => $d){
						if($d !== 'user'){
							$url = '/' . @$arr_module[2];
						}
						else{
							$url = self::REDIRECT_URL;
							break;
						}
					}	
					Application_Form_FrmMessage::redirectUrl("/home");	
				}
				else{					
					$this->view->msg = 'ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ';
				}
					
			}
			else{				
				$this->view->msg = 'លោកអ្នកមិនមានសិទ្ធិប្រើប្រាស់ទេ!';
			}			
		}		
    }
    
    protected function sortMenu($menus){
    	$menus_order = Array ( 'home','other','group','loan','tellerandexchange','accounting','report','setting','backup','rsvAcl');
    	$temp_menu = Array();
    	$menus=array_unique($menus);
    	foreach ($menus_order as $i => $val){
    		foreach ($menus as $k => $v){
    			if($val == $v){
    				$temp_menu[] = $val;
    				unset($menus[$k]);
    				break;
    			}
    		}
    	}
    	return $temp_menu;    	
    }

    public function logoutAction()
    {
        // action body
        $this->_redirect("/index");
        if($this->getRequest()->getParam('value')==1){        	
        	$aut=Zend_Auth::getInstance();
        	$aut->clearIdentity();        	
        	$session_user=new Zend_Session_Namespace('auth');
        	
        	$log=new Application_Model_DbTable_DbUserLog();
			$log->insertLogout($session_user->user_id);
			
        	$session_user->unsetAll();       	
	           	         	 
        	Application_Form_FrmMessage::redirectUrl("/");
        	exit();
        } 
    }

    public function changepasswordAction()
    {
        // action body
        if ($this->getRequest()->isPost()){ 
			$session_user=new Zend_Session_Namespace('auth');    		
    		$pass_data=$this->getRequest()->getPost();
    		if ($pass_data['password'] == $session_user->pwd){
    			    			 
				$db_user = new Application_Model_DbTable_DbUsers();				
				try {
					$db_user->changePassword($pass_data['new_password'], $session_user->user_id);
					$session_user->unlock();	
					$session_user->pwd=$pass_data['new_password'];
					$session_user->lock();
					Application_Form_FrmMessage::Sucessfull('ការផ្លាស់ប្តូរដោយជោគជ័យ', self::REDIRECT_URL);
				} catch (Exception $e) {
					Application_Form_FrmMessage::message('ការផ្លាស់ប្តូរត្រូវបរាជ័យ');
				}				
    		}
    		else{
    			Application_Form_FrmMessage::message('ការផ្លាស់ប្តូរត្រូវបរាជ័យ');
    		}
        }   
    }

    public function errorAction()
    {
        // action body
        
    }
    public function  dashboardAction(){
    	$this->_helper->layout()->disableLayout();
    }
    public static function start(){
    	return ($this->getRequest()->getParam('limit_satrt',0));
    }
    function changelangeAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$session_lang=new Zend_Session_Namespace('lang');
    		$session_lang->lang_id=$data['lange'];
    		Application_Form_FrmLanguages::getCurrentlanguage($data['lange']);
    		print_r(Zend_Json::encode(2));
    		exit();
    	}
    }
    function indexAction(){
    	$db = new Application_Model_DbTable_DbFrontend();
//     	$data = $db->getmenucontact();
//     	$this->view->showdata= $data;
    	 
    	$this->view->vehicle = $db->getVehicleDiscount();
    	$this->view->welcome = $db->getAllWelcomenote();
    	
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		if(isset($post["search_vehicle"])){
    			$row = $db->getAvailableVehicle($post);
    			if($row){
    				$session_booking=new Zend_Session_Namespace('booking');
    				$session_booking->step_one=1;
    				$session_booking->pickup_date=$post["pickup_date"];
    				$session_booking->return_date=$post["return_date"];
    				$session_booking->pickup_time=$post["pickup_time"];
    				$session_booking->return_time=$post["return_time"];
    				$session_booking->pickup_location=$post["pickup_location"];
    				$session_booking->return_location=$post["return_location"];
    				$diff=date_diff($post["pickup_date"],$post["return_date"]);
    				$session_booking->total_day=$diff->format("%a%");
    				Application_Form_FrmMessage::redirectUrl("/index/booking");
    			}else{
    				Application_Form_FrmMessage::message("No Vehicle Available!");
    			}
    		}
    	}
    	 
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
    	
    	$db = new Stuff_Model_DbTable_DbStuff();
    	$this->view->stuffrow = $db->getAllStuffInFrontend();
    	
    	$db = new Application_Model_DbTable_DbGlobal();
    	$this->view->vehiclerows=$db->getallSemilarVehicle();
    }
    public function contactAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenucontact();
    	$this->view->showdata= $data;
    }
    public function bookingAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$step_one =0;
    	$step_two=0;
    	$step_three=0;
    	$step_five =0;
    	//$step_four=0;
    	$customer_user_session="";
    	
    	$session_step_one =new Zend_Session_Namespace('booking');
    	$step_booking = $session_step_one->step_one;
    	$user_ip = $session_step_one->user_ip;
    	
    	$step_4 = new Zend_Session_Namespace('step_four');
    	$session_step_four = $step_4->step4;
    	
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$user_session = $customer_session->customer_session;
    	if(!empty($session_step_four)){
    		$step_four = $session_step_four;
    	}else{
    		 $step_four= 0;
    	}
    	if(!empty($user_session)){
    		$customer_user_session = $user_session;
    		$customer_user = $customer_session->customer_id;
    		$this->view->user_name = $customer_session->customer_name;
    		$this->view->user_info = $customer_session->user_info;
    	}else{
    		$customer_user_session=0;
    	}
    	if($step_booking==""){
    		$step_one =0;
    	}else $step_one =$step_booking;
    	
    	$data_first_step = array();
    	$row_vehicle = array();
    	$row_vehicle_price = array();
    	$row_equipment_price = array();
    	$row_driver_price = array();
    	$row_equipment = array();
    	$row_driver = array();
    	if($step_one!=0){
    		$post = $this->getRequest()->getPost();
    		if(isset($post["search_vehicle"])){
    			$db->deleteVisaulBooking($user_ip,2);
    			$row = $db->getAllAvailableVehicle($post);
    			if(!empty($row)){
    				$db->updateVisualBookingStepOne($post,$user_ip);
    			}
    			$session_step_two = new Zend_Session_Namespace('step_two');
    			$session_step_two->unsetAll();
    			
    			$session_step_three = new Zend_Session_Namespace('step_three');
    			$session_step_three->unsetAll();
    			
    			$session_step_four = new Zend_Session_Namespace('step_four');
    			$session_step_four->unsetAll();
    			
    			$session_step_five = new Zend_Session_Namespace('step_five');
    			$session_step_five->unsetAll();
    			//Application_Form_FrmMessage::redirectUrl("/index/booking");
    		}
    		
    		$data_first_step = $db->getFirstStepData($user_ip);
    		$row_driver = $db->getAvailableDriver($data_first_step);
    		$row_equipment = $db->getEquipment($data_first_step);
    		$row_vehicle= $db->getAllAvailableVehicle($data_first_step);
    		
    		$session_step_two = new Zend_Session_Namespace('step_two');
    		if($session_step_two->step_two == ""){
    			$step_two =0;
    		}else{
    			$step_two = $session_step_two->step_two;
    		}
    		if($step_two!=0){
    			$row_vehicle_price = $db->getVehiclePriceSelected($user_ip);
    			
    			$session_step_three = new Zend_Session_Namespace('step_three');
    			if($session_step_three->step_three ==""){
    				$step_three =0;
    			}else{
    				$step_three = $session_step_three->step_three;
    			}
    			if($step_three!=0){
    				if($this->getRequest()->isPost()){
    					$data = $this->getRequest()->getPost();
    					if(isset($data["pro_equip"])){
    						if($data["identity_equipment"] !="" or $data["identity_driver"] !=""){
    							$db->updateVisualBookingStebthree($user_ip,$data);
    						}
    					}
    				}
    				$row_equipment_price = $db->getProductAndServicesSelected($user_ip, 3);
    				$row_driver_price = $db->getProductAndServicesSelected($user_ip, 2);
    				if($step_four!=0){
    					if($this->getRequest()->isPost()){
    						$data = $this->getRequest()->getPost();
    						if(isset($data["confirm_book"])){
    							$session_step_five = new Zend_Session_Namespace('step_five');
    							$step_five = $session_step_five->step_five =1;
    						}elseif(isset($data["term_condiction"])){
    							$db->addCuFlight($data,$user_ip);
    						}
    					}
    				}else{
    					if($this->getRequest()->isPost()){
    						$data = $this->getRequest()->getPost();
    						if(isset($data["term_condiction"])){
    							$db->addCuFlight($data,$user_ip);
    							$session_step_four = new Zend_Session_Namespace('step_four');
    							$step_four = $session_step_four->step4 =1;
    						}
    					}
    				}
    			}else{
    				if($this->getRequest()->isPost()){
    					$data = $this->getRequest()->getPost();
	    				if(isset($data["pro_equip"])){
	    					$session_step_three = new Zend_Session_Namespace('step_three');
	    					$session_step_three->step_three=1;
	    					if($data["identity_equipment"] !="" or $data["identity_driver"] !=""){
	    						
	    						$db->addVisualBookingStebthree($user_ip,$data);
	    					}
	    					Application_Form_FrmMessage::redirectUrl("/index/booking");
	    				}
    				}
    				$row_equipment_price = $db->getProductAndServicesSelected($user_ip, 3);
    				$row_driver_price = $db->getProductAndServicesSelected($user_ip, 2);
    			}
    		}
    	}else{
    		if($this->getRequest()->isPost()){
    			$post = $this->getRequest()->getPost();
    			if(isset($post["search_vehicle"])){
    				$row = $db->getAllAvailableVehicle($post);
    				if(!empty($row)){
    					$session_booking=new Zend_Session_Namespace('booking');
    					$session_booking->step_one=1;
    					$session_booking->user_ip=$post["public_ip"]."-".$post["ip_address"];
    					$user_ip = $session_booking->user_ip;
    					$db->addVisualBookingStepOne($post);
    					$data_first_step = $db->getFirstStepData($user_ip);
    					$row_driver = $db->getAvailableDriver($data_first_step);
    					$row_equipment = $db->getEquipment($data_first_step);
    					$row_vehicle= $db->getAllAvailableVehicle($data_first_step);
    					Application_Form_FrmMessage::redirectUrl("/index/booking");
    				}else{
    					$session_booking=new Zend_Session_Namespace('booking');
    					$session_booking->step_one=0;
    				}
    			}
    		}
    	}
    	$this->view->user_session = $customer_user_session;
    	$this->view->step_five = $step_five;
    	$this->view->step_four = $step_four;
    	$this->view->step_one = $step_one;
    	$this->view->step_two = $step_two;
    	$this->view->step_three = $step_three;
    	
    	$this->view->equipment_price = $row_equipment_price;
    	$this->view->driver_price = $row_driver_price;
    	$this->view->vehicle_price = $row_vehicle_price;
    	$this->view->equipment = $row_equipment;
    	$this->view->vehicle = $row_vehicle;
    	$this->view->driver = $row_driver;
    	$this->view->data_first_step = $data_first_step;
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
    }
    public function getvehicleidAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$id = $this->getRequest()->getParam("id");
    	//print_r($id);
    	$session_step_one =new Zend_Session_Namespace('booking');
    	$user_ip = $session_step_one->user_ip;
    	$session_step_two = new Zend_Session_Namespace('step_two');
    	$step_two = $session_step_two->step_two;
    	if(!empty($step_two)){
    		//$ids = $session_step_three->vehicle_id;
    		$db->updateVisualBookingStepTwo($user_ip, $id);
    		Application_Form_FrmMessage::redirectUrl("/index/booking");
    	}else{
	    	if($id){
	    		$session_step_one =new Zend_Session_Namespace('booking');
	    		$user_ip = $session_step_one->user_ip;
	    		$session_step_two = new Zend_Session_Namespace('step_two');
	    		$session_step_two->step_two=1;
	    		$session_step_two->vehicle_id=$id;
	    		$db->addVisualBookingStepTwo($user_ip, $id);
	    		Application_Form_FrmMessage::redirectUrl("/index/booking");
	    	}
    	}
    }
    public function pageAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenufornt();
    	$this->view->showdata= $data;
    }
    public function inventoryAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenufornt();
    	$this->view->showdata= $data;
    }
    public function serviceAction(){
//     	$this->_helper->layout()->disableLayout();
    	$db = new Application_Model_DbTable_DbService();
    	$data = $db->getAllServiceList();
    	$this->view->showdata= $data;
    }
    public function servicedetailAction(){
    	$db = new Application_Model_DbTable_DbService();
    	
    	$id = $this->getRequest()->getParam("id");
    	$this->view->row = $db->getServiceDetailId($id);
    	
    	$data = $db->getAllServiceList(8,$id);
    	$this->view->showdata= $data;
    }
    
    public function ourteamAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenufornt();
    	$this->view->showdata= $data;
    }
    public function aboutAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenuabout();
    	$this->view->showdata= $data;
    }
    public function jobAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$id= $this->getRequest()->getParam("id");
    	$data_id = $this->view->id = $id;
    	if(!empty($id)){
    		$data = $db->getJobById($id);
    		$this->view->showdata= $data;
    	}else{
    		$data = $db->getAllJob();
    		$this->view->showdata= $data;
    	}
    }
    public function promoAction(){
    	$db = new Other_Model_DbTable_DbSeason();
    	$this->view->promorows=  $db->getAllPromotionName();
    	$this->view->prepromorows= $db->getAllPrePromotionName();;
    }
    public function prayerlistAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$this->view->showdata= $db->getAllPrayerList();
    }
    public function portfolioAction(){
    	$db = new Application_Model_DbTable_DbFrontend();
    	$this->view->rowsphoto = $db->getAllPhotoGalllery();
    }
    function fqaAction(){
    	$db = new Other_Model_DbTable_Dbfaq();
    	$this->view->rows=$db->getAllFAQInfrontend();
    }
    function policyAction(){
    	$db = new Other_Model_DbTable_Dbprivacy();
    	$this->view->rows=$db->getAllPrivacy();
    }
    public function vehicleAction(){
    	$id = $this->getRequest()->getParam("id");
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->geVehicleById($id);
    	$this->view->showdata= $data;
    	$this->view->rows=$db->getallSemilarVehicle();
    }
    function citytourAction(){
    	$db = new Application_Model_DbTable_DbFrontend();
    	$this->view-> province = $db->getAllProvince();
    }
    function tourlocationAction(){
    	$locaition_id = $this->getRequest()->getParam("id");
    	if(!empty($locaition_id)){
    		$db = new Application_Model_DbTable_DbFrontend();
    		$this->view-> tourlocation = $db->getCityTurePackage($locaition_id);
    	}else{
    		$this->_redirect("/index/citytour");
    	}
    }
    function citytourbookingAction(){
    	$session_tour =new Zend_Session_Namespace('bookcitytour');
    	$dbbooking = new Application_Model_DbTable_Dbbookingcitytour();
    	if(empty($session_tour->step1)){
    		$this->_redirect("/index/citytour");
    	}
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
	    	if(isset($post["confirm_book"])){
	    		$dbbooking->createSessionBookingCityTour($post,6);
	    	}elseif(isset($post["term_condiction"])){
	    		$dbbooking->createSessionBookingCityTour($post,5);
    	}
    	}
    	$db = new Application_Model_DbTable_DbGlobal();
    	$row_vehicle=array();
    	
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$user_session = $customer_session->customer_session;
    	if(!empty($user_session)){
    		$customer_user_session = $user_session;
    		$customer_user = $customer_session->customer_id;
    		$this->view->user_name = $customer_session->customer_name;
    		$this->view->user_info = $customer_session->user_info;
    	}else{
    		$customer_user_session=0;
    	}
    	 
    	$this->view->user_session = $customer_user_session;
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
    	
    	$this->view->pickup_info = $session_tour;
    	/*********************Step 3 sucess*/
//     	if(!empty($session_tour->step3)){
	         
//     	}
//     	/*********************step 4 sucess*/
//     	if(!empty($session_tour->step4)){
    		
//     	}
    }
    function selectguideAction(){
    	$guide_id = $this->getRequest()->getParam("id");
    	$dbbooking = new Application_Model_DbTable_Dbbookingcitytour();
    	if(!empty($guide_id)){
    		$dbbooking->createSessionBookingCityTour($guide_id,4);
    	}else{
    		$dbbooking->createSessionBookingCityTour(null,4);
    	}
    	$this->_redirect("index/citytourbooking");
    }
    function getvehiclecitytourAction(){//action step3
    	$vehicle_id = $this->getRequest()->getParam("id");
    	if($vehicle_id){
    		$dbbooking = new Application_Model_DbTable_Dbbookingcitytour();
    		$dbbooking->createSessionBookingCityTour($vehicle_id,3);
    		$this->_redirect("index/citytourbooking");
    	}
    	Application_Form_FrmMessage::redirectUrl("/index/testing");
    }
    function getvehicleAction(){//action step2
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		$reservation['pickup_date']=$post['pickup_date'];
    		$reservation['pickup_time']=$post['pickup_time'];
    		$reservation['pickup_mins']=$post['pickup_mins'];

    		$reservation['return_time']=$post['return_time'];
    		$reservation['return_mins']=$post['return_mins'];
    		$post['return_date']=$post['pickup_date'];
    		$post['return_time']=$post['pickup_time'].":".$post['pickup_mins'];
    		
    		$dbbooking = new Application_Model_DbTable_Dbbookingcitytour();
    		$dbbooking->createSessionBookingCityTour($reservation,2);
    	}
    	$this->_redirect("index/citytourbooking");
    }
    function getcitytourpackageidAction(){//action step 1
    	$package_id = $this->getRequest()->getParam("package_id");
    	if($package_id){
    		$dbbooking = new Application_Model_DbTable_Dbbookingcitytour();
    		$dbbooking->createSessionBookingCityTour($package_id,1);
    		$this->_redirect("index/citytourbooking");
    		
    	}else{
    		$this->_redirect("index/citytour");
    	}
    }
    /*get detail city tour */
    function citytourdetailAction(){
    	$id = $this->getRequest()->getParam("id");
    	$db = new Application_Model_DbTable_Dbbookingcitytour();
    	$this->view->row = $db->getPackageCityTourDetailById($id);
    	$this->view->photo = $db->getPackagPhotoById($id);
//         print_r($db->getPackageCityTourDetailById($id));
    	$db = new Application_Model_DbTable_DbFrontend();
    	$this->view->alllocation = $db->getLocatoinTurByPackageId($id);
    }
    function sendemailAction(){
    	$post=$this->getRequest()->getPost();
		$db = new Application_Model_DbTable_DbSendEmail();
		$sms = $db->sendContactEmail($post);
		echo Zend_Json::encode($sms);
		exit();
    }
   
    
    function checkUsernameAvailableAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$row = $db->checkAvailableUserName($data);
    		print_r(Zend_Json::encode($row));
    		exit();
    	}
    }
    function stuffAction(){
    	$db = new Stuff_Model_DbTable_DbStuff();
    	$this->view->stuffrow = $db->getAllStuffInFrontend();
    }
    function prayerAction(){
    	$id = $this->getRequest()->getParam("id");
    	$db = new Other_Model_DbTable_DbPlayerList();
    	$this->view->prayerrow=$db->getPlayerListById($id);
    }
    function getstuffidAction(){
    	$id = $this->getRequest()->getParam("id");
    	if($id){
    		$dbbooking = new Application_Model_DbTable_Dbstuffrental();
    		$dbbooking->createSessionStuffBooking($id,1);
    		$this->_redirect("stuffbooking/index");
    		 
    	}else{
    		$this->_redirect("stuffbooking/index");
    	}
    }
    function termconditionAction(){
    	$db = new Other_Model_DbTable_DbHelp();
    	$this->view->rows= $db->getTermCodition();
    }
    function infomationAction(){
    	$db = new Other_Model_DbTable_DbOtherinformation();
    	$this->view->rows=$db->getAllInfomation();
    }
    function detailinfoAction(){
    	$id = $this->getRequest()->getParam("id");
    	$db = new Other_Model_DbTable_DbOtherinformation();
    	$this->view->rows=$db->getAllInfomation();
    	
    	$this->view->row=$db->getOtherById($id);
    }
    function carsalesAction(){
    	$db= new Application_Model_DbTable_DbGlobal();
    	$this->view->rows = $db->getAllSaleVehicle();
    	
    }
    function guestbookAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$row = $db->sumitComment($data);
    		Application_Form_FrmMessage::Sucessfull('Thanks,Your comments has been sent!',"/index/guestbook");
    	}
    	 
    	$db = new Application_Model_DbTable_DbGlobal();
    	$this->view-> rows = $db->getAllGuestBookFooter(1);
    }
    function submitcommentAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$row = $db->sumitComment($data);
    		$this->_redirect("/index/guestbook");
    	}
    }
    function drivinglicensetransferAction(){
    	$db = new Other_Model_DbTable_DbHelp();
//     	$this->view->rows= $db->getTermCodition();
    }
    function vehiclelistAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$this->view->rows=$db->getallSemilarVehicle();
    }
    function instructionAction(){
    	$db = new Other_Model_DbTable_DbInstruction();
    	$id = $this->getRequest()->getParam("id");
    	$data = $db->getInstructionDetailById($id);
    	$this->view->detail = $data;
    	
    	$this->view->row=$db->getAllInstructionMenu();
    }
  function bookingserviceAction(){
    		$db = new Application_Model_DbTable_DbGlobal();
    	
    	$defual_guide = $db->getAllParameter("Defualt Rental Guide");
    	$car_rental = $db->getAllParameter("Car Rental Service Guide");
    	$city_tour = $db->getAllParameter("City Tour Service");
    	$taxi = $db->getAllParameter("Taxi Rental Service Guide");
    	$product = $db->getAllParameter("Product Rental Guide");
    	$guide = $db->getAllParameter("Guide Rental Service Guide");
    	$driver = $db->getAllParameter("Driver Rental Guide");
    	$instruction = $db->getAllParameter("Booking Instruction Guide");
    	$onlinepay = $db->getAllParameter("Online Payment Guide");
    	
    	$this->view->defual_guide = $defual_guide;
    	$this->view->carrental =$car_rental;
    	$this->view->citytour = $city_tour;
    	$this->view->taxi = $taxi;
    	$this->view->product = $product;
    	$this->view->guide = $guide;
    	$this->view->drive = $driver;
    	$this->view->instruction = $instruction;
    	$this->view->online_pay = $onlinepay;
    }
function otherbookAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$row = $db->OtherBooking($data);
    		
    		$db_mail = new Application_Model_DbTable_DbSendEmail();
    		$rs = $db_mail->sendOtherBookEmail($data);
                Application_Form_FrmMessage::message($rs);
    	}
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
    }

}




