<?php
class Driverguide_indexController extends Zend_Controller_Action {
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Driverguide_Model_DbTable_DbDriver();
			if($this->getRequest()->isPost()){
				$formdata=$this->getRequest()->getPost();
				$search = array(
						'title' => $formdata['title'],
						'status_search'=>$formdata['status_search'],
				);
			}
			else{
				$search = array(
						'title' => '',
						'status_search' => -1,
				);
			}	
			$rs_rows= $db->getAllDriverGuide($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("Driver's Id","First Name","Last Name","Sex","Tel","Dob","Pob","Nationality","Group No","Home No","Stree",
					"Commune","District","Province","Status");
			$link=array(
					'module'=>'driverguide','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('driver_id'=>$link,'first_name'=>$link,'last_name'=>$link,'sex'=>$link,'tel'=>$link,'dob'=>$link));
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
			$data = $this->getRequest()->getPost();
			$db = new Driverguide_Model_DbTable_DbDriver();
			try{
				$id= $db->addDriver($data);
				if(isset($data['save_new'])){
					Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
				}
				else{
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESSS","/group/index");
				}
		
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$db = new Application_Model_DbTable_DbGlobal();
		$client_type = $db->getclientdtype();
		array_unshift($client_type,array(
		'id' => -1,
		'name' => '---Add New ---',
		) );
		$this->view->clienttype = $client_type;
		
		$fm = new Group_Form_FrmClient();
		
		$frm = $fm->FrmaddGuide();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_client = $frm;
		
		$dbpop = new Application_Form_FrmPopupGlobal();
		$this->view->frm_popup_clienttype = $dbpop->frmPopupclienttype();
	}
	public function editAction(){
		$db_model = new Driverguide_Model_DbTable_DbDriver();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			try{
					$id= $db_model->updateDriver($data);
					Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESSS","/driverguide");
		
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$db = new Application_Model_DbTable_DbGlobal();
		$client_type = $db->getclientdtype();
		array_unshift($client_type,array(
				'id' => -1,
				'name' => '---Add New ---',
		) );
		$this->view->clienttype = $client_type;
		
		$dbpop = new Application_Form_FrmPopupGlobal();
		$this->view->frm_popup_clienttype = $dbpop->frmPopupclienttype();
		
		$id = $this->getRequest()->getParam("id");
		$row = $db_model->getDriverById($id);
		$this->view->rs = $row;
		
		$fm = new Group_Form_FrmClient();
		$frm = $fm->FrmaddGuide($row);
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_client = $frm;
	}
}

