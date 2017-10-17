<?php
class Report_BookingController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
  function indexAction(){
  	$db = new Report_Model_DbTable_booking();
  	
  	if($this->getRequest()->isPost()){
  		$formdata=$this->getRequest()->getPost();
  		$search = array(
  				'book_no' => $formdata['book_no'],
  				'customer'=>$formdata['customer'],
  				'date_book'=>$formdata['date_book'],
  				'pickup_date'=>$formdata['pickup_date'],
  				'return_date'=>$formdata['return_date'],
  				'status'		=>	$formdata['status'],
  		);
  	}
  	else{
  		$search = array(
  				'book_no' => -1,
  				'customer' => -1,
  				'date_book'=> date('Y-m-d'),
  				'pickup_date'=>date('Y-m-d'),
  				'return_date'=>date('Y-m-d'),
  				'status'=>-1,);
  	}
  	$row = $db->getAllBooking($search);
  	$this->view->row_booking = $row;
  	
  	$form = new Report_Form_FrmBooking();
  	$frm = $form->FrmBooking();
  	Application_Model_Decorator::removeAllDecorator($frm);
  	$this->view->frms = $frm;
  }
}

