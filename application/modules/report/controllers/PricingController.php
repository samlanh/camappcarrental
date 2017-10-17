<?php
class Report_PricingController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
    function indexAction(){
	  	
	 }
	 function rptGuideinfoAction(){//release all loan
	 	if($this->getRequest()->isPost()){
	 		$search = $this->getRequest()->getPost();
	 	}
	 	else{
	 		$search = array(
	 				'adv_search'=>'',
	 				'status'=>'',
	 				'start_date'=> date('Y-m-d'),
	 				'end_date'=>date('Y-m-d'));
	 	}
	 	$this->view->data = $search;
	 	
	 	$db = new Report_Model_DbTable_DbGuide();
	 	$this->view->rows = $db->getGuideInfo($search);
	 	
	 	$frm = new Application_Form_FrmAdvanceSearch();
	 	$form = $frm->AdvanceSearch();
	 	Application_Model_Decorator::removeAllDecorator($form);
	 	$this->view->frm = $form;
	 }
	function rptGuidepriceAction(){//release all loan
		$db = new Report_Model_DbTable_DbGuide();
		$this->view->rows = $db->getGuidePrice();
	  }
	 
	  function rptVehicleinfoAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllVehicleInfo();
	  }
	  function vehicleRentalpriceAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
		$this->view->rows = $db->getAllVehiclePrice();
	  }
	  function rptTaxirentalpriceAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllVehicleTaxi();
	  }
	  function rptCitytourirentalpriceAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllVehicleTaxiTour();
	  }
	  function rptServicePriceAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllServicePrice();
	  	
	  }
	  function rptCarrentalpickupAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllCarprice();
	  	 
	  }
	  function rptVehicleAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllVehicle();
	  	 
	  }
	  function rptCustomerAction(){
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->rows = $db->getAllClients();
	  	 
	  }
	  function bookingAction(){
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
	  function viewbookingAction(){
	  	$id=$this->getRequest()->getParam('id');
	  	$db = new Report_Model_DbTable_DbGuide();
	  	$this->view->customer = $db->getCustomerInfoByBooking($id);
	  	$this->view->rows = $db->getItemBookingDetail($id);
	  }
           function rptVehicleagreementAction(){
	  	$db = new Report_Model_DbTable_DbRptAgreement();
	  	$search=array();
	  	$this->view->rows = $db->getAllVehicleAgreement($search);
	  }
	  function vehicleagreementAction(){
	  	$id = $this->getRequest()->getParam('id');
	  	$db = new Report_Model_DbTable_DbRptAgreement();
	  	$row = $db->getAllVehicleAgreementById($id);
	  	$this->view->row =$row;
	  	 $this->view->vehicle = $db->getVehicleById($row['vehicle_id']);
	  }
          function rptDriveragreementAction(){
	  	$db = new Report_Model_DbTable_DbRptAgreement();
	  	$search=array();
	  	$this->view->rows = $db->getAllDriverAgreement($search);
	  }
	  function driveragreementAction(){
	  	$id = $this->getRequest()->getParam('id');
	  	$db = new Report_Model_DbTable_DbRptAgreement();
	  	$row = $db->getDriverAgreementById($id);
	  	$this->view->row =$row;
	  	$this->view->driverrows = $db->getDriverByBooking($row['booking_id']);
	  }
          function rptProductagreementAction(){
	  	$db = new Report_Model_DbTable_DbRptAgreement();
	  	$search=array();
	  	$this->view->rows = $db->getAllProductAgreement($search);
	  	
	  }
	  function productagreementAction(){
	  	$id = $this->getRequest()->getParam('id');
	  	$db = new Report_Model_DbTable_DbRptAgreement();
	  	$row = $db->getProductAgreementById($id);
	  	$this->view->row =$row;
	  	
	  	$data = $db->getProductBooking($row['booking_id']);
	  	$this->view->productlist = $data;
	  }
          public function generatebarcodeAction(){
	  	$product_code = $this->getRequest()->getParam('generatebarcode');
	  	header('Content-type: image/png');
	  	$this->_helper->layout()->disableLayout();
	  	$this->_helper->viewRenderer->setNoRender();
	  	$barcodeOptions = array('text' => "$product_code",'barHeight' => 40);
	  	$rendererOptions = array();
	  	$renderer = Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions)->render();
	  
	  }
  
}

