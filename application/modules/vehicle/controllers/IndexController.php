<?php
class Vehicle_indexController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL_ADD ='/vehicle/index/add';
	const REDIRECT_URL_ADD_CLOSE ='/vehicle/index/';
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
		$db_make = new Vehicle_Model_DbTable_DbVehicle();
		if($this->getRequest()->isPost()){
			$search=$this->getRequest()->getPost();
			//print_r($search);exit();
		}
		else{
			$search = array(
					'adv_search' => '',
					'make'=> -1,
					'model'=> -1,
					'submodel'=> -1,
					'search_status' =>-1,
					);
		}
		$rows=$db_make->getAllVehicle($search);
		$glClass = new Application_Model_GlobalClass();
		$rows = $glClass->getImgActive($rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("Vehicle Ref","Year","Make","Model","Sub Model","Type","Color","Engine No","Chassis No","Frame No","Plate No","Date","STATUS");
			$link=array(
					'module'=>'vehicle','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rows,array('reffer'=>$link,'year'=>$link,'make_id'=>$link,'model_id'=>$link,'sub_model'=>$link,'status'=>$link,'type'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllMake();
		//array_unshift($model, array ( 'id' => -1, 'name' => 'Selected Make') );
		$this->view->all_make=$model;
		
	}
	public function addAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try {
				$db_make = new Vehicle_Model_DbTable_DbVehicle();
				if(isset($data['save_new'])){
					$db_make->addVehicle($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_make->addVehicle($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$db=new Vehicle_Model_DbTable_DbVehicle();
		$rows_engin=$db->getAllEnGince();
		$this->view->rows_engine=$rows_engin;
		$rows_type=$db->getAllType();
		$this->view->rows_type=$rows_type;
		$rows_tran=$db->getAllTransmisstion();
		$this->view->rows_tran=$rows_tran;
		$rows_veh_typ=$db->getAllVehicleType();
		$this->view->rows_veh_typ=$rows_veh_typ;
		//select store mark
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllMake();
		array_unshift($model, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
		$this->view->all_make=$model;
	}
	function editAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try{
				$db_make = new Vehicle_Model_DbTable_DbVehicle();
				if(isset($data['save_close'])){
					$db_make->updateVehicle($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$id=$this->getRequest()->getParam('id');
		$db=new Vehicle_Model_DbTable_DbVehicle();
		$rows_v=$db->getVehicleById($id);
		$this->view->row_vehicle=$rows_v;
		$rows_engin=$db->getAllEnGince();
		$this->view->rows_engine=$rows_engin;
		$rows_type=$db->getAllType();
		$this->view->rows_type=$rows_type;
		$rows_tran=$db->getAllTransmisstion();
		$this->view->rows_tran=$rows_tran;
		$rows_veh_typ=$db->getAllVehicleType();
		$this->view->rows_veh_typ=$rows_veh_typ;
		//select store mark
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllMake();
		array_unshift($model, array ( 'id' => -1, 'name' => 'បន្ថែម​អ្នក​ទទួល​ថ្មី') );
		$this->view->all_make=$model;
		
	}
	function getSubModelAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Vehicle_Model_DbTable_DbVehicle();
			$makes = $db->getAllSubModelById($data['model_id']);
			array_unshift($makes, array ( 'id' => -1, 'name' => 'បន្ថែមថ្មី') );
			print_r(Zend_Json::encode($makes));
			exit();
		}
	}
	function addSubModelAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Vehicle_Model_DbTable_DbVehicle();
			$id = $_dbmodel->addSubModelajx($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	
}

