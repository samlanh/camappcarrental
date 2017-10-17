<?php
class Other_SeasonController extends Zend_Controller_Action {
	const REDIRECT_URL='/other';
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
			}
				else{
			
				}
				$db = new Other_Model_DbTable_DbSeason();
				$rs_rows= $db->getAllSeason();
				$list = new Application_Form_Frmtable();
				$collumns = array("Season Title","Value","Season Type","Start Date","End Date","Status");
				$link=array(
						'module'=>'other','controller'=>'season','action'=>'edit',
				);
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('season_title'=>$link,'season_type'=>$link,'value'=>$link));
			
		}
	
	function addAction()
		{
			if($this->getRequest()->isPost()){//check condition return true click submit button
				$_data = $this->getRequest()->getPost();
				$_dbmodel = new Other_Model_DbTable_DbSeason();
				try {
					$_dbmodel->addseason($_data);
					if(!empty($_data['save_new'])){
						Application_Form_FrmMessage::message("INSERT_SUCCESS");
					}else{
						Application_Form_FrmMessage::Sucessfull(("INSERT_SUCCESS"),self::REDIRECT_URL . "/season");
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
			$_db = new Other_Model_DbTable_DbSeason();
			if($this->getRequest()->isPost()){//check condition return true click submit button
				$_data = $this->getRequest()->getPost();
				try {
					$_db->updateSeason($_data);
					Application_Form_FrmMessage::Sucessfull(("EDIT_SUCCESS"),self::REDIRECT_URL . "/season");
				}catch (Exception $e) {
					Application_Form_FrmMessage::message("INSERT_FAIL");
					$err =$e->getMessage();
					Application_Model_DbTable_DbUserLog::writeMessageError($err);
				}
			}
			$id = $this->getRequest()->getParam("id");
			$this->view->row = $_db->getSeasonById($id);
		}
}

