<?php
class Booking_indexController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Booking_Model_DbTable_DbCarRentalNew();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'to_book_date' => date("Y-m-d"),
						'from_book_date' => date("Y-m-d"),
						'search_text' => "",
						'customer'=>-1,
				);
			}
			$rs_rows= $db->getAllCarBooking($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("Booking No","Customer Name","Date Book","Pickup Date","Return Date","Total Fee","Total Payment",);
			$link=array(
					'module'=>'booking','controller'=>'index','action'=>'view',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('booking_no'=>$link,'customer'=>$link,));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Booking_Form_FrmSearchBooking();
		$frm =$frm->FormSearch();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	public function addAction(){
		
		$db = new Booking_Model_DbTable_DbCarRentalNew();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$booking_id=$db->addBookingRental($data);
			//$this->_redirect("/booking/index/add");
// 			Application_Form_FrmMessage::redirectUrl("/booking/carrentalbooking/add");
		}
		
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$array= array(
				'pickup_date'=>date("Y-m-d"),
				'return_date'=>date("Y-m-d"),
				'return_time'=>date("07:00:00"),
		);
		$this->view->rowsguide = $db_globle->getAllAvailableGuide($array);
		$this->view->vehiclevaliable = $db_globle->getAllAvailableVehicle($array);
		$this->view->productavailable= $db_globle->getEquipment($array);
		
		
		$frm = new Booking_Form_FrmBookingNew();
		$form = $frm->FromBooking();
		Application_Model_Decorator::removeAllDecorator($form);
		$this->view->frm = $form;
	}
	function getCustomerAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbCarRentalNew();
			$row = $db->getNameCustomer($data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function searchavailableAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbCarRentalNew();
			$row = $db->getSearchAvailable($data);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	
	function getbookinglistformAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbCarRentalNew();
			$row = $db->getBookingListToShow($data);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
}

