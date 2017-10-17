<?php
class Other_OwnervehicleController extends Zend_Controller_Action {
	const REDIRECT_URL='/other';
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	protected $tr;
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Other_Model_DbTable_DbOwner();
// 			if($this->getRequest()->isPost()){
// 				$search=$this->getRequest()->getPost();
// 			}
// 			else{
// 				$search = array(
// 						'adv_search' => '',
// 						'search_status' => -1);
// 			}
			$rs_rows= $db->getAllOwner();
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("Owner Name","Position","ID Card","Phone","E-mail","Insurance Hotline","Status");
			$link=array(
					'module'=>'other','controller'=>'ownervehicle','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('hand_phone'=>$link,'owner_name'=>$link,'position'=>$link,'id_card'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			echo $e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}	
	}
    function addAction(){
   	if($this->getRequest()->isPost()){
   		try{
   			$_data = $this->getRequest()->getPost();
   			$db = new Other_Model_DbTable_DbOwner();
   			if(!empty($_data['save_new'])){
   				$db->addOwner($_data);
   				Application_Form_FrmMessage::message($this->tr->translate('INSERT_SUCCESS'));
   			}else if(!empty($_data['save_close'])){
   				$db->addOwner($_data);
   				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/ownervehicle/index');
   			}
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
//    	$frm = new Other_Form_FrmZone();
//    	$frm_co=$frm->FrmAddZone();
//    	Application_Model_Decorator::removeAllDecorator($frm_co);
//    	$this->view->frm_zone = $frm_co;
   }
   function editAction(){
	   	if($this->getRequest()->isPost()){
	   		try{
	   			$_data = $this->getRequest()->getPost();
	   			$db = new Other_Model_DbTable_DbOwner();
	   		 if(!empty($_data['save_close'])){
	   				$db->updateOwner($_data);
	   				Application_Form_FrmMessage::Sucessfull($this->tr->translate('INSERT_SUCCESS'), self::REDIRECT_URL.'/ownervehicle/index');
	   			}
	   		}catch(Exception $e){
	   			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
	   			$err =$e->getMessage();
	   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
	   		}
	   	}
	   $id=$this->getRequest()->getParam('id');
	   $db=new Other_Model_DbTable_DbOwner();
	   $row=$db->getOwnerById($id);
	   $this->view->row_owner=$row;
   }
   public function addNewzoneAction(){
   	if($this->getRequest()->isPost()){
   		$data = $this->getRequest()->getPost();
   		$data['status']=1;
   		$db_co = new Other_Model_DbTable_DbZone();
   		$id = $db_co->addZone($data);
   		print_r(Zend_Json::encode($id));
   		exit();
   	}
   }
}

