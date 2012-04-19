<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerFelhasznalo extends controllBase
{
	var $view = "felhasznalo"; 
	var $model = "felhasznalo";
	var $controller = "felhasznalo";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=termek&task=login";
	var $cancelLink = "index.php?option=com_whp&controller=felhasznalo";
	var $addLink = "";	 

	function __construct($config = array())
	{
		//die("-------------");
		parent::__construct($config);
		//$task = JRequest::getVar("task");
		//$this->redirect_();
	}// function

	function redirect_(){
		$Itemid = $this->Itemid;	
		$task = JREquest::getWord("task","");
		//die($task."----");
		if( $this->user->id && !in_array($task, array("edit", "save", "keep") ) ){//
			$link = JRoute::_( "index.php?option=com_whp&controller=termek&task=felad&Itemid={$Itemid}" );
			$this->setRedirect($link);
		}
	}
		
	function logout(){
		global $mainframe, $Itemid;
		$mainframe->logout();
		$msg = JText::_("SIKERES_KIJELENTKEZES");
		$link = JRoute::_("index.php");
		$this->setRedirect($link, $msg);
	}

	function save()
	{
		$Itemid = $this->Itemid;
		$this->session();
		$model = $this->getModel($this->model);
		$errorFields = $model->checkMandatoryFields();
		//print_r(JRequest::getVar("kat_id"));
		//die;
//die($this->model);
		//die( "valami" );
		if(!count($errorFields) ){
			if ($mode = $model->store() ) {
				switch ($mode){
					case 'update':
						$msg = JText::_( 'ADATAI_SIKERESEN_ELMENTVE' );
						$link = "index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$id}{$this->tmpl}";
						break;
						
					default:
						$msg = JText::_( 'ADATAI_SIKERESEN_ELMENTVE' );
						$link = "index.php?option=com_content&view=article&id=77";
				}	
				
			} else {
			//die($errorFields);
				$msg = JText::_( 'HIBA_TORTENT_MENTES_KOZBEN' );
			}
			//$link = jroute::_("index.php?option=com_whp&controller=termekek&Itemid={$Itemid}");
			
			$model->deleteSession();
			$this->setRedirect($link, $msg);
		}else{
			JRequest::setVar('hidemainmenu', 1);
			$msg = JText::_( 'HIBAS_MEZOK' );
			$errorFields_="&errorFields[]=";
			$errorFields_.=implode("&errorFields[]=",$errorFields);
			
			$link = "index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$id}{$errorFields_}{$this->tmpl}";
			$this->setRedirect($link, $msg);
		}
	}// function

	function login(){
		//die("valami");
		parent::__construct();
		global $mainframe, $Itemid;	
		$model = $this->getModel($this->model);
		$username = JRequest::getVar("l_username");
		$password = JRequest::getVar("l_password");
		if($model->login( $username, $password )== false ){
			$mainframe->logout();
			$this->setRedirect("index.php&option=com_whp&controller=termekek&Itemid={$Itemid}", jtext::_("NEM_LEPHET_BE") );
		}else{
		}
		$this->user=JFactory::getUser();
		@$msg = $error->message;
		//print_r($this->user);
		//exit;
		if($this->user->id){
			$msg = JText::_("SIKERESEN BEJELENTKEZETT");
			if ($this->getSessionVar("kosar")){
				$link = JRoute::_("index.php?option=com_whp&controller=kosar&Itemid={$Itemid}");
			} else{
				$link = JRoute::_("index.php?option=com_whp&controller=felhasznalo&Itemid={$Itemid}");
			}
			
			$this->setRedirect($link, $msg );
		}else{
			$msg = JText::_("");
			$link = JRoute::_("index.php?option=com_whp&controller=felhasznalo&Itemid={$Itemid}");	
		}
		$this->setRedirect($link, $msg);
	}
	
	function regisztracio(){ 
		//JREquest::setVar("layout", "regisztracio" );
		//die("regisztracio");
	}
}//class
?>