<?php
class Application_Model_DbTable_DbCustomer extends Zend_Db_Table_Abstract
{
	protected $_name = 'ldc_customer';
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function customerAuthenticate($username,$password)
	{
	
		$db_adapter = Application_Model_DbTable_DbUsers::getDefaultAdapter();
		$auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
	
		$auth_adapter->setTableName($this->_name) // table where users are stored
		->setIdentityColumn('user_account') // field name of user in the table
		->setCredentialColumn('password') // field name of password in the table
		->setCredentialTreatment('MD5(?) AND status=1'); // optional if password has been hashed
			
		$auth_adapter->setIdentity($username); // set value of username field
		$auth_adapter->setCredential($password);// set value of password field
	
		//instantiate Zend_Auth class
		$auth = Zend_Auth::getInstance();
	
	
		$result = $auth->authenticate($auth_adapter);
	
	
		if($result->isValid()){
			return true;
		}else{
			return false;
		}
	}
	public function signUp($data){
		$db = $this->getAdapter();
		try {
			$db_globle = new Application_Model_DbTable_DbGlobal();
			$client_code = $db_globle->getNewClientId();
			$arr = array(
				'customer_code'	=>	$client_code,
				'first_name'	=>	$data["first_name"],
				'last_name'		=>	$data["last_name"],
				'email'			=>	$data["email"],
				'phone'			=>	$data["phone"],
				'user_account'	=>	$data["s_user_name"],
				'password'		=>	md5($data["s_password"]),
				'status'		=>	1,
'dob'			=>	$data["dob"],
				'sex'			=>	$data["gender"],
			);
			$this->_name = "ldc_customer";
			$row = $this->insert($arr);
			return $row;
		} catch (Exception $e) {
			echo$e->getMessage();
			exit();
			
		}
	}
	
	public function getUserById($id,$type){
		$db =$this->getAdapter();
		if($type==1){
			$sql = "SELECT *,(SELECT country_name from ldc_country where ldc_country.id = c.pob) AS pob,
			(SELECT country_name from ldc_country where ldc_country.id = c.country) AS country
			 FROM ldc_customer AS c WHERE c.`id`=$id";
			$row = $db->fetchRow($sql);
		}else{
			$sql = "SELECT CONCAT(c.`last_name`,' ',c.`first_name`) AS customer_name FROM ldc_customer AS c WHERE c.`id`=$id";
			$row = $db->fetchOne($sql);
		}
		
		if(!empty($row)){
			return $row;
		}
	}
	public function getCustomerId($user_name){
		$db =$this->getAdapter();
		$sql = "SELECT c.id FROM ldc_customer AS c WHERE c.`user_account`='$user_name'";
		$row = $db->fetchOne($sql);
		return $row;
	}
	public function updatecustomer($_data){//update customer
		$photoname = str_replace(" ", "_",$_data['name_en'].'-AGNF') . '.jpg';
		$upload = new Zend_File_Transfer();
		$upload->addFilter('Rename',
				array('target' => PUBLIC_PATH . '/images/'. $photoname, 'overwrite' => true) ,'photo');
		$receive = $upload->receive();
		if($receive)
		{
			$_data['photo'] = $photoname;
		}
		else{
			$_data['photo']=$_data['old_photo'];
		}
		
	
		try{
// 			$db = new Application_Model_DbTable_DbGlobal();
// 			$client_code = $db->getNewClientId();
				
			$_arr=array(
					'title'	 => $_data['title'],
					'first_name' =>$_data['name_kh'],
					'last_name' => $_data['name_en'],
					'sex' => $_data['sex'],
					'dob' => $_data['dob_client'],
					'photo'	=> $_data['photo'],
					'occupation' => $_data['occupation'],
					'pob' => $_data['country'],
					'company_name'=> $_data['company_name'],
					'nationality' => $_data['national_id'],
					'phone' => $_data['phone'],
					'email' => $_data['email'],
					'group_num' => $_data['group_num'],
					'house_num' =>$_data['home'],
					'street' =>$_data['street'],
					'commune' => $_data['commune'],
					'district' => $_data["district"],
					'province_id' => $_data['province'],
					'address1'      => $_data['address1'],
					'address2'      => $_data['address2'],
					'i_city'      => $_data['city'],
					'i_zipcode'      => $_data['i_zipcode'],
					'i_state'      => $_data['state'],
					'country'      => $_data['country'],
					'i_phone'      => $_data['i_phone'],
					'fax'      => $_data['fax'],
					
					'date'  => date("Y-m-d"),
			);
			$session_user=new Zend_Session_Namespace('customer');
			$user_id= $session_user->customer_id;
			
			$where = 'id = '.$user_id;
			$this->update($_arr, $where);
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
public function passLink($email){
		$db = $this->getAdapter();
		
		$sql = "SELECT c.`email`,c.`pass_link` FROM `ldc_customer` AS c WHERE c.`email`='"."$email"."' LIMIT 1";
		$row = $db->fetchRow($sql);
		if($row){
			$rand = rand();
			$arr = array(
				'pass_link'		=>	md5($rand),
			);
			$this->_name="ldc_customer";
			$where = $db->quoteInto("email=?", $email);
			$this->update($arr, $where);
                $result = $db->fetchRow($sql);
                $db_mail = new Application_Model_DbTable_DbSendEmail();
		$db_mail->resetPassEmail($result );
		}
		
	}
	public function updatePassword($data,$pass_link){
		$db = $this->getAdapter();
		$arr = array(
			'password'	=> md5($data["password"])
		);
		$where = $db->quoteInto("pass_link=?", $pass_link);
		$this->update($arr, $where);
	}
}