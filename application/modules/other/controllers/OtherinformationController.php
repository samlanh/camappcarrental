<?php
class Other_OtherinformationController extends Zend_Controller_Action {
	const REDIRECT_URL='/other';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
				$db = new Other_Model_DbTable_DbOtherinformation();
				$this->view->rows= $db->getAllOtherinformation();
						
   }
	function addAction()
		{
			if($this->getRequest()->isPost()){//check condition return true click submit button
				$_data = $this->getRequest()->getPost();
				$_dbmodel = new Other_Model_DbTable_DbOtherinformation();
				try {
					if(isset($_data['save_new'])){
						$_dbmodel->addOtherinformation($_data);
						Application_Form_FrmMessage::message("INSERT_SUCCESS");
					}else if(isset($_data['save_close'])){
						$_dbmodel->addOtherinformation($_data);
						Application_Form_FrmMessage::Sucessfull(("INSERT_SUCCESS"),self::REDIRECT_URL . "/otherinformation/index");
					}
				}catch (Exception $e) {
					Application_Form_FrmMessage::message("INSERT_FAIL");
					$err =$e->getMessage();
					Application_Model_DbTable_DbUserLog::writeMessageError($err);
				}
			}
		}
		function editAction()
		{
		$_db = new Other_Model_DbTable_DbOtherinformation();
			if($this->getRequest()->isPost()){//check condition return true click submit button
				$_data = $this->getRequest()->getPost();
				
				try {
					$_db->updateOtherinformation($_data);
					Application_Form_FrmMessage::Sucessfull(("EDIT_SUCCESS"),self::REDIRECT_URL . "/otherinformation");
				}catch (Exception $e) {
					Application_Form_FrmMessage::message("EDIT_FAIL");
					$err =$e->getMessage();
					Application_Model_DbTable_DbUserLog::writeMessageError($err);
				}
			}
			$id = $this->getRequest()->getParam("id");
			$this->view->row = $_db->getOtherById($id);		
		}
}

