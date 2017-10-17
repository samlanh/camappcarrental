<?php
class Other_PlayerlistController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	protected $tr;
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
   function indexAction(){
      	    $db = new Other_Model_DbTable_DbPlayerList();
      	    $rows=$db->getAllPlayerList();
   	        $glClass = new Application_Model_GlobalClass();
   			$rows = $glClass->getImgActive($rows, BASE_URL, true);
   			try{
   				$list = new Application_Form_Frmtable();
   				$collumns = array("Title","Status");
   				$link=array(
   						'module'=>'other','controller'=>'playerlist','action'=>'edit',
   				);
   				$this->view->list=$list->getCheckList(0, $collumns, $rows,array('title'=>$link,'descript'=>$link));
   			}catch (Exception $e){
   				Application_Form_FrmMessage::message("Application Error");
   				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
   			}
	}
   function addAction(){
   	if($this->getRequest()->isPost()){
   		try{
   			$data = $this->getRequest()->getPost();
   			$db = new Other_Model_DbTable_DbPlayerList();
   			if(!empty($data['save_new'])){
   				$db->addPlayerList($data);
   				Application_Form_FrmMessage::message("ការ​បញ្ចូល​ជោគ​ជ័យ !");
   			}elseif(!empty($data['save_close'])){
   				$db->addPlayerList($data);
   				Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !","/other/playerlist");
   			}
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   }
   function editAction(){
		   	if($this->getRequest()->isPost()){
		   		try{
		   			$data = $this->getRequest()->getPost();
		   			$db = new Other_Model_DbTable_DbPlayerList();
		   			if(!empty($data['save_close'])){
		   				$db->updatePlayerList($data);
		   				Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !","/other/playerlist");
		   			}
		   		}catch(Exception $e){
		   			Application_Form_FrmMessage::message($this->tr->translate('INSERT_FAIL'));
		   			$err =$e->getMessage();
		   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
		   		}
		   	}
		   	$id=$this->getRequest()->getParam('id');
		   	$db=new Other_Model_DbTable_DbPlayerList();
		   	$this->view->row_pllist=$db->getPlayerListById($id);
   }
}

