<?php

class CustomerController extends Zend_Controller_Action
{

	const REDIRECT_URL = '/transfer';
	
    public function init()
    {
        /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');  
    	
    }

    public function customerloginAction()
    {
		if($this->getRequest()->isPost())		
		{
			$id = $this->getRequest()->getParam("id");
			$formdata=$this->getRequest()->getPost();
				$user_name=$formdata['user_name'];
				$password=$formdata['password'];
				$db_user=new Application_Model_DbTable_DbCustomer();
				if($db_user->customerAuthenticate($user_name,$password)){					
					$session_user=new Zend_Session_Namespace('customer');
					$user_id=$db_user->getCustomerId($user_name);
					$user_info = $db_user->getUserById($user_id, 1);
					
					$session_user->customer_session = 1;
					$session_user->customer_id = $user_id;
					$session_user->customer_name = $user_name;
					
					$session_user->pwd=$password;		
					$session_user->last_name= $user_info['last_name'];
					$session_user->first_name= $user_info['first_name'];
					$session_user->user_info = $user_info;
					
// 					$dbbooking = new Application_Model_DbTable_Dbbookingtaxi();
// 					$dbbooking->createSessionTaxiBooking($formdata,3);
					if($id ==2){
						Application_Form_FrmMessage::redirectUrl("/taxi/index");
					}else if($id ==1){
						Application_Form_FrmMessage::redirectUrl("/index/booking");
					}else if($id ==3){
						Application_Form_FrmMessage::redirectUrl("/index/citytourbooking");
					}elseif($id==4){
						Application_Form_FrmMessage::redirectUrl("/stuffbooking/index");
					}elseif($id==5){
						Application_Form_FrmMessage::redirectUrl("/guidebooking/index");
					}elseif($id==6){
						Application_Form_FrmMessage::redirectUrl("/carbooking/index");
					}
				}
				else{
					Application_Form_FrmMessage::message("ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ");					
					$this->view->msg = 'ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ';
					if($id ==2){
						Application_Form_FrmMessage::redirectUrl("/taxi/index");
					}else if($id ==1){
						Application_Form_FrmMessage::redirectUrl("index/booking");
					}else if($id ==3){
						Application_Form_FrmMessage::redirectUrl("/index/citytourbooking");
					}elseif($id==4){
						Application_Form_FrmMessage::redirectUrl("/stuffbooking/index");
					}elseif($id==5){
						Application_Form_FrmMessage::redirectUrl("/guidebooking/index");
					}elseif($id==6){
						Application_Form_FrmMessage::redirectUrl("/carbooking/index");
					}
				}
				
				
// 				}
		}	
		$frmbooks = new Application_Form_FrmBooking();
		$frmbooking = $frmbooks->FromBooking();
		Application_Model_Decorator::removeAllDecorator($frmbooking);
		$this->view->frmbooking = $frmbooking;
    }
    public function logoutAction()
    {
        // action body
       
        if($this->getRequest()->getParam('value')==1){        	
        	$aut=Zend_Auth::getInstance();
        	$aut->clearIdentity();        	
        	$session_user=new Zend_Session_Namespace('customer');
        	$session_user->unsetAll();       	
        	$this->_redirect("/index");
        	Application_Form_FrmMessage::redirectUrl("/");
        	//exit();
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
    	//$this->_helper->layout()->disableLayout();
    	$db = new Application_Model_DbTable_DbCustomer();
    	$session_user=new Zend_Session_Namespace('customer');
    	$user_id= $session_user->customer_id;
    	$row_customer = $db->getUserById($user_id,1);
    	$this->view->customer_data = $row_customer;
    	$this->view->customer_name = $session_user->customer_name;
    }
    public function  editAction(){
//     	$this->_helper->layout()->disableLayout();
    	$db = new Application_Model_DbTable_DbCustomer();
    	if($this->getRequest()->isPost()){
    		$_data = $this->getRequest()->getPost();
    		$db->updatecustomer($_data);
    		Application_Form_FrmMessage::Sucessfull("your profile has been update! ", "/customer/dashboard");
    		
    	}
    	$session_user=new Zend_Session_Namespace('customer');
    	$user_id= $session_user->customer_id;
    	$row_customer = $db->getUserById($user_id,1);
    	$fm = new Application_Form_FrmBooking();
		$frm = $fm->FrmCustomer($row_customer);
    	Application_Model_Decorator::removeAllDecorator($fm);
		$this->view->frm_client = $frm;
    	$this->view->customer_data = $row_customer;
    	$this->view->customer_name = $session_user->customer_name;
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
    	 
    	$frmbooks = new Application_Form_FrmBook();
    	$frmbooking = $frmbooks->getFromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooks->getFromBooking();
    }
    
    function signupAction(){
$req_id = $this->getRequest()->getParam("id");
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbCustomer();
    		$id = $db->signUp($data);
    		$user_name = $db->getUserById($id, 2);
    		$customer_session  = new Zend_Session_Namespace('customer');
    		$customer_session->customer_session = 1;
    		$customer_session->customer_id = $id;
    		$customer_session->customer_name = $user_name;
    		$user_id=$db->getCustomerId($user_name);
    		$user_info = $db->getUserById($id, 1);
    		$customer_session->last_name= $user_info['last_name'];
    		$customer_session->first_name= $user_info['first_name'];
    		$customer_session->user_info = $user_info;
    		if($req_id==1){
    			Application_Form_FrmMessage::redirectUrl("/index/booking");
    		}elseif($req_id==2){
    			Application_Form_FrmMessage::redirectUrl("/taxi/index");
    		}elseif($req_id==3){
    			Application_Form_FrmMessage::redirectUrl("/index/citytourbooking");
    		}elseif($req_id==4){
    			Application_Form_FrmMessage::redirectUrl("/stuffbooking/index");
    		}elseif($id==4){
						Application_Form_FrmMessage::redirectUrl("/stuffbooking/index");
					}elseif($id==5){
						Application_Form_FrmMessage::redirectUrl("/guidebooking/index");
					}else{
    			Application_Form_FrmMessage::redirectUrl("/customer/dashboard");
    		}
    	}
    }
    function paymentAction(){
    	$this->_helper->layout()->disableLayout();
    	$db = new Application_Model_DbTable_DbGlobal();
    	$db_mail = new Application_Model_DbTable_DbSendEmail();
    	$id = $this->getRequest()->getParam("id");
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if($id==1){
	    		$session_step_one =new Zend_Session_Namespace('booking');
		    	$user_ip = $session_step_one->user_ip;
		    	$db->updateVisualBookingStebSix($user_ip, $data);
		    	$booking_id = $db->addBooking($user_ip);
		    	
		    	$db_mail->sendInvoiceEmail($booking_id);
		    	
		    	$session_step_one =new Zend_Session_Namespace('booking');
		    	$session_step_one->unsetAll();
		    	
		    	$session_step_two = new Zend_Session_Namespace('step_two');
		    	$session_step_two->unsetAll();
		    	
		    	$session_step_three = new Zend_Session_Namespace('step_three');
		    	$session_step_three->unsetAll();
		    	
		    	$session_step_four = new Zend_Session_Namespace('step_four');
		    	$session_step_four->unsetAll();
		    	
		    	$session_step_five = new Zend_Session_Namespace('step_five');
		    	$session_step_five->unsetAll();
		    	Application_Form_FrmMessage::Sucessfull("Thank You For Support. Please Check Your E-mail Address! If don't see please contact administrator.", "/index");
    		}elseif ($id==2){
    			$db_citytour = new Application_Model_DbTable_Dbbookingcitytour();
    			$booking_id=$db_citytour->addCityTourBooking($data);
    			$db_mail->sendInvoiceEmail($booking_id);
    			
    			$session =new Zend_Session_Namespace('bookcitytour');
    			$session->unsetAll();
    			Application_Form_FrmMessage::Sucessfull("Thank You For Support. Please Check Your E-mail Address! If don't see please contact administrator.", "/index");
    		}elseif ($id==3){
    			$db_citytour = new Application_Model_DbTable_Dbstuffrental();
    			$booking_id=$db_citytour->addProductRental($data);
    			$db_mail->sendInvoiceEmail($booking_id);
    			 
    			$session =new Zend_Session_Namespace('stuffbooking');
    			$session->unsetAll();
    			Application_Form_FrmMessage::Sucessfull("Thank You For Support. Please Check Your E-mail Address! If don't see please contact administrator.", "/index");
    		}elseif ($id==4){
    			$db_citytour = new Application_Model_DbTable_Dbguiderental();
    			$booking_id=$db_citytour->addProductRental($data);
    			$db_mail->sendInvoiceEmail($booking_id);
    			 
     			$session =new Zend_Session_Namespace('guidebooking');
     			$session->unsetAll();
     			Application_Form_FrmMessage::Sucessfull("Thank You For Support. Please Check Your E-mail Address! If don't see please contact administrator.", "/index");
    		}
elseif ($id==5){
    			$db_citytour = new Application_Model_DbTable_DbCarRental();
    			$booking_id=$db_citytour->addProductRental($data);
    			$return = $db_mail->sendInvoiceEmail($booking_id);
    			 
     			$session =new Zend_Session_Namespace('carbooking');
     			$session->unsetAll();
     			Application_Form_FrmMessage::Sucessfull($return , "/index");
    		}
	    	
	    	
    	}
    }
    function signuptaxiAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbCustomer();
    		$id = $db->signUp($data);
    		$user_name = $db->getUserById($id, 2);
    		$step_four  = new Zend_Session_Namespace('step_four');
    		$customer_session  = new Zend_Session_Namespace('customer');
    		$customer_session->customer_session = 1;
    		$step_four->step4 = 1;
    		$customer_session->customer_id = $id;
    		$customer_session->customer_name = $user_name;
    		Application_Form_FrmMessage::redirectUrl("/taxi/index");
    	}
    }
    function emailinvoiceAction(){
    	$this->_helper->layout()->disableLayout();
    	$id = $this->getRequest()->getParam("id");
    	$db = new Application_Model_DbTable_DbGlobal();
    	$row_booking = $db->getBookingById($id, 1);
    	$row_booking_detail = $db->getBookingById($id, 2);
    	
    	$this->view->booking = $row_booking;
    	$this->view->booking_detail = $row_booking_detail;
    }
function forgetpassAction(){
    	$db = new Application_Model_DbTable_DbCustomer();
    	$url = $this->getRequest()->getParam("url");
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
	    	if(!empty($url)){
	    		$db->updatePassword($data,$url);
	    		Application_Form_FrmMessage::Sucessfull("Your password have been updated!", "/customer/customerlogin");
	    	}else{
	    		$db->passLink($data["email"]);
	    		Application_Form_FrmMessage::Sucessfull("Your E-mail Have Been Sent./r/n Please Check Your E-mail Address !", "/customer/customerlogin");
	    	}
    	}
    	$frm = new Application_Form_FrmBooking();
    	$form = $frm->FrmCustomer();
    	$this->view->frms = $form;
    	$this->view->url = $url;
    }
    
}
