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
		
	}
	
	public function viewAction(){
		$id=$this->getRequest()->getParam('id');
		$db = new Report_Model_DbTable_DbGuide();
		$this->view->customer = $db->getCustomerInfoByBooking($id);
		$this->view->rows = $db->getItemBookingDetail($id);
	}
}

