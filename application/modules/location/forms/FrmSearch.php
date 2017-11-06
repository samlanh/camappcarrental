<?php 
Class Location_Form_FrmSearch extends Zend_Dojo_Form{
	protected $tr = null;
	protected $btn =null;//text validate
	protected $filter = null;
	protected $text =null;
	protected $validate = null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->btn = 'dijit.form.Button';
		$this->validate = 'dijit.form.ValidationTextBox';
	}	
	public function search(){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Application_Model_DbTable_DbGlobal();
		
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->text,
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("ADVANCE_SEARCH")));
		$_title->setValue($request->getParam("title"));
	
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status->setAttribs(array('dojoType'=>$this->filter,
				'class'=>'fullside'));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL_STATUS"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status_search"));
		
		$service_rs = $db->getServiceType();
		$_service_type = new Zend_Dojo_Form_Element_FilteringSelect("service_type");
		$_arr=array(0=>$this->tr->translate("Choose Service Type"));
		if(!empty($service_rs))foreach($service_rs AS $row){
			$_arr[$row['id']]=$row['name'];
		}
		$_service_type->setMultiOptions($_arr);
		$_service_type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'required'=>'true',
				'class'=>'fullside'));
		$_service_type->setValue($request->getParam("service_type"));
		
		$this->addElements(array($_service_type,$_title,$_status));
	
		return $this;
	}

}