<?php
class location_LocationController extends Zend_Controller_Action {
	const REDIRECT_URL ='/location';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
				$search = array(
						'title' => $_data['title'],
						'status' => $_data['status_search']);
			}
			else{
		
				$search = array(
						'title' => '',
						'status' => -1,
				);
		
			}
			$db = new Location_Model_DbTable_DbLocation();
			$rs_rows= $db->getAllLocations($search);
		
			$list = new Application_Form_Frmtable();
			$collumns = array("Location Type","Province","Modify","Status");
			$link=array(
					'module'=>'location','controller'=>'location','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('location_name'=>$link,'province_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$frm = new Location_Form_FrmSearch();
		$frm =$frm->search();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
	}
	
	public function addAction(){
		if($this->getRequest()->isPost()){
			$db = new Location_Model_DbTable_DbLocation();
			$data = $this->getRequest()->getPost();
			$db->addPackage($data);
			if(!empty($data['save_new'])){
				Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS",self::REDIRECT_URL."/location/add");
			}else{
				Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS",self::REDIRECT_URL."/index");
			}
		}
		$db = new Location_Model_DbTable_DbProvince();
		$this->view->provincelist = $db->getAllProvince();
		$this->view->locationtype = $db->getAllLocationType();
	}
	public function editAction(){
		$db_model = new Location_Model_DbTable_DbLocation();
		
		if($this->getRequest()->isPost()){
			
			$data = $this->getRequest()->getPost();
			$db_model->updatePackage($data);
			Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS",self::REDIRECT_URL."/location");
		}
		$db = new Location_Model_DbTable_DbProvince();
		$this->view->provincelist = $db->getAllProvince();
		$id = $this->getRequest()->getParam("id");
		$this->view->row = $db_model->getLocationById($id);
		
		//print_r($db_model->getLocationById($id));
		$this->view->rowpic = $db_model->getPhotoDetailById($id);
		$this->view->locationtype = $db->getAllLocationType();
	}
}

