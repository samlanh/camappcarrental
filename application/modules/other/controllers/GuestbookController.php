<?php
class Other_GuestbookController extends Zend_Controller_Action {
	const REDIRECT_URL='/other';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		$db=new Other_Model_DbTable_DbGuestbook();
        $this->view->row=$db->getAllGuestbook();		
	}
	
	function addAction()
		{
			
// 			if($this->getRequest()->isPost()){//check condition return true click submit button
// 				$_data = $this->getRequest()->getPost();
// 				$_dbmodel = new Other_Model_DbTable_Dbabout();
// 				try {
// 					$_dbmodel->addabout($_data);
// 					if(!empty($_data['save_new'])){
// 						Application_Form_FrmMessage::message("INSERT_SUCCESS");
// 					}else{
// 						Application_Form_FrmMessage::Sucessfull(("INSERT_SUCCESS"),self::REDIRECT_URL . "/job/index");
// 					}
// 				}catch (Exception $e) {
// 					Application_Form_FrmMessage::message("INSERT_FAIL");
// 					$err =$e->getMessage();
// 					Application_Model_DbTable_DbUserLog::writeMessageError($err);
// 				}
// 			}
            $this->_redirect('other/guestbook/');
		}
		function editAction()
		{
		$_db = new Other_Model_DbTable_DbGuestbook();
			if($this->getRequest()->isPost()){//check condition return true click submit button
				$_data = $this->getRequest()->getPost();
				
				try {
					$_db->updateGuestbook($_data);
					Application_Form_FrmMessage::Sucessfull(("EDIT_SUCCESS"),self::REDIRECT_URL . "/guestbook");
				}catch (Exception $e) {
					Application_Form_FrmMessage::message("EDIT_FAIL");
					$err =$e->getMessage();
					Application_Model_DbTable_DbUserLog::writeMessageError($err);
				}
			}
			$id = $this->getRequest()->getParam("id");
			$this->view->row = $_db->getGuestbookById($id);		}
               function deleteAction()
		{
			$id = $this->getRequest()->getParam("id");
			$_db = new Other_Model_DbTable_DbGuestbook();
					
					try {
						$_db->deleteGuestbook($id);
						Application_Form_FrmMessage::Sucessfull(("DELETE_SUCCESS"),self::REDIRECT_URL . "/guestbook");
					}catch (Exception $e) {
						Application_Form_FrmMessage::message("DELETE_FAIL");
						$err =$e->getMessage();
						Application_Model_DbTable_DbUserLog::writeMessageError($err);
					}
		}
}

