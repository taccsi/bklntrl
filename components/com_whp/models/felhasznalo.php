<?php

defined( '_JEXEC' ) or die( '=;)' );



class whpModelFelhasznalo extends whpPublic

{

	var $xmlFile = "felhasznalo.xml";

	var $uploaded = "";

	var $tmpname = "";

	// var $table = "#__whp_felhasznalo";

	//var $table ="wh_kategoria";

	

	function __construct()

	{

		parent::__construct(); 

		//ini_set('display_errors',1);
		$this->value = JRequest::getVar("value", "");

		$this->doc->setTitle(Jtext::_('FELHASZNALOI ADATOK'));

		$this->getData();

		//print_r($this->_data);

	 	$this->xmlParser = new xmlfelhasznalo( $this->xmlFile, $this->_data );

	

	}//function

	

	function ajaxCheck($func = "mandatoryCheck"){

		$nodeName = jrequest::getvar("nodeName", "");

		$ajaxVal = jrequest::getvar("ajaxVal", "");

		

		if( $this->$func( $nodeName, $ajaxVal ) ){

			$msg = jtext::_("MEZO_RENDBEN");			

			$ret = "<span class=\"span_ok\" >{$msg}</span>";

			//die("{$ajaxVal} ---");

		}else{

			

			$e_ = $this->xmlParser->getNode("name", $nodeName);

			if(is_a($e_, "DOMElement")){

				$msg = jtext::_($e_ ->getAttribute("mandatory_text"));

			}

			$ret = "<span class=\"span_error\" >{$msg}</span>";		

		}

		return $ret;

	}	


	
	

	function checkPassword( $name, $ajaxVal="" ){

		ob_start();

		if(!$ajaxVal){

			$val =JRequest::getVar($name, 0);

		}else{

			$val = $ajaxVal;

		}

		$tmp = $val;

		

		$tmp = ob_get_contents;

		ob_end_clean();

		return $tmp;

	}



	function checkPassword_megerositese( $name, $ajaxVal="" ){

		if($ajaxVal){

			//$password = $ajaxVal;

			$password = JRequest::getVar("password", "");

			$password_megerositese = JRequest::getVar("password_megerositese", "");			

		}else{

			$password = JRequest::getVar("password", "");

			$password_megerositese = JRequest::getVar("password_megerositese", "");			

		}

		//die("{$password} - {$password_megerositese}");

		if(!$this->user->id){	

			if( $password && $password==$password_megerositese){

				return true;

			}else{

				return false; 

			}

		}elseif( $password==$password_megerositese ){

			return true;

		}else{

			return false;

		}

	}		

	 

	function checkFelhasznalo( $name, $ajaxVal="" ){

		//die($name);

		($ajaxVal) ? $value = $ajaxVal : $value = $this->xmlParser->getAktVal($name);

		if( $value ){

			if($this->user->id){

				$q = "select id from #__users where username = '{$value}' and id <> {$this->user->id} ";

			}else{

				$q = "select id from #__users where username = '{$value}' ";

			}

			$this->db->setQuery($q);

			if($id = $this->db->loadResult()){

				return false;

			}else{

				return true;

			}

		}else{

			return false;

		}

	}

	

	function checkEmail($name, $ajaxVal=""){

		//return 1;

		//die(":::::::::: {$ajaxVal}");

		($ajaxVal) ? $value = $ajaxVal : $value = jrequest::getvar($name);

		if( $value ){			

			if($this->user->id){

				$q = "select id from #__users where email = '{$value}' and id <> {$this->user->id} ";

				//die($q);

			}else{

				$q = "select id from #__users where email = '{$value}' ";

			}

			$this->db->setQuery($q);

			if($id = $this->db->loadResult()){

				return false;

			}else{

				return $this->checkEmailFormat($name, $ajaxVal); 

			}

		}else{

			return false;

		}

	}



	function checkEmailFormat($name, $ajaxVal=""){

		($ajaxVal) ? $value = $ajaxVal : $value = jrequest::getvar($name);		

		//$value = $this->xmlParser->getAktVal($name);

		//$value = jrequest::getvar("email");		

		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)) {

			return true;

		}

		else{

			return false;

		}

	}

	function store(){
		global $mainframe;
		$user_ = JFactory::getUser();
		$user_id = $user_->id;
		$user = JUser::getInstance($user_id);
		
		foreach(array("name", "username", "password", "email") as $f){
			switch($f){
				case "password":
					($password = JRequest::getVar( $f, "" ) ) ? $v = md5( $password ) : $v = "";
					$pass_mail = JRequest::getVar( $f, "" );
				break;
				default : $v = JRequest::getVar( $f, "" );
			}
			if($v){
				$user->set( $f, $v );
			}
		}
		$user->set( "usertype", "Registered" );
		$user->set( "gid", "18" );
		if ($this->user->id) {$user->set( "block", 0 );} else {$user->set( "block", 0 );} //itt dől el, hogy aktív lesz -e azonnal a felhasználó
		//die("valami ----");
		if( $id = $user->save() ){
			if(!$this->user->id) $this->login($user->username, $password);//el beléptet
			$us_ = JFactory::getUser();
			//$us_ = $this->getObj( $this->_db, "#__users", $user->name, "name" );
			$o = "";
			$fields_ = $this->_db->getTableFields("#__wh_felhasznalo", 1);
			foreach($fields_["#__wh_felhasznalo"] as $parName => $v){
				$val = JRequest::getVar($parName,"", "",2,2,2);
				if(is_array($val)){
					$o->$parName = ",".implode(",", $val).",";
				}else{
					$o->$parName = $val;
				}
			}
			$user_id = $us_->id;
			$o->user_id = $user_id;
			$o->webshop_id = $GLOBALS["whp_id"];
			//echo 
			//print_r($o);
			//die;
			if($o->id = $this->letezikUser($user_id, $GLOBALS["whp_id"] ) ){
				//die('fut');
				$this->_db->updateObject("#__wh_felhasznalo", $o, "id");
				//$this->savetoWebGalamb($user,$pass_mail);
				$mode ="update";
			}else{
				$this->_db->insertObject("#__wh_felhasznalo", $o, "id");
				//$this->savetoWebGalamb($user,$pass_mail); //webgalamb ideiglenenes kikapcsolva
				$this->sendMail($user,$pass_mail);
				$mode = "new";
			}
			return $mode;
		}else{
			//die("y");
			return 0;
		}
	}   	
	
	function saveToWebgalamb($user){
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$wg_api = new wg3api();
	
	// wg3 adatbázis beállítása
	$db_pre = 'wg3_';
	$hostname_local = "localhost";
	$database_local = "drprende_trifid";
	$username_local = "drprende_trifid";
	$password_local = "zC)E5k1I33o&";
	$local = mysql_connect($hostname_local, $username_local, $password_local) or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db( $database_local, $local ) or die ("Nem lehet megnyitni az adatbázist ".mysql_error() );
	mysql_query("set character set utf8") or die(mysql_error());
	mysql_query("set names utf8");

	
	if(!$wg_api -> user_mod(array('datum' => date("Y-m-d"), 'name' => $user->name), $user->email, 4)) 
			$wg_api -> new_user(array('datum' => date("Y-m-d"), 'mail' => $user->email, 'name' => $user->name, 'active' => 1), 4);

	}
	

	function login($username, $password){

		global $mainframe;

		$options = array();

		$credentials = array();

		$credentials['username'] = $username;

		$credentials['password'] = $password;

		$mainframe->login($credentials, $options);

		$user = jfactory::getuser();

		if($user->usertype != "Registered" ){

			return false;

		}else{

			return true;

		}

	}



	function letezikUser($user_id, $webshop_id){

		$q = "select id from #__wh_felhasznalo where user_id = {$user_id} limit 1";

		$this->_db->setQuery( $q );

		return $this->_db->loadResult();

	}



	function getObj($db, $table, $id, $pk ="id" ){

		$q = "select * from {$table} where `{$pk}` = '{$id}' limit 1";

		$db->setQuery($q);

		return $db->loadObject();

	}

	



	function sendMail($user,$pass_mail){

		$params = &JComponentHelper::getParams( 'com_whp' );

		$from = $this->params->get( 'felado_email' );
		
		$admin_email = $this->params->get( 'admin_email' );

		$fromname = $this->params->get( 'felado_nev' );

		//$recipient[]="balazs@trifid.hu";

		//mail('balazs@trifid.hu','proba','fdsfasf');

		//$recipient[]="info@bikeline.hu";

		//$recipient[]="info@webstarter.hu";

		$recipient[] = $user->email;
		$recipient[] = $admin_email;
		
		$id = JRequest::getVar("id");

		$subject = JText::_("REGISZTRALTAK"); 

		$body = sprintf(jTEXT::_("REGISZTRACIO_ERTESITO_BODY"), $user->name, $user->username, $pass_mail);

		$mode = 1;

		JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode, '', '', '');
			//	echo $body;
		//print_r($recipient); die();
		

	}

	

	function getData()

	{

		

		//print_r($_REQUEST);exit;

		$q = "select * from #__wh_felhasznalo where user_id = {$this->user->id}";

		//echo $q;

		//die;

		$this->_db->setQuery($q);

		$this->_data = $this->_db->loadObject() ;

		//echo $this->_db->getErrorMsg();

		$this->_data->username=$this->user->username;

		$this->_data->name=$this->user->name;

		$this->_data->email=$this->user->email;		

		//print_r($this->_data);	

		//die;

		$this->setMandatoryFields();

		//print_r($this);

		//die;

		return $this->_data;

	}//function



}// class

?>