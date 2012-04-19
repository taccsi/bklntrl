<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.controller');

class controllBase extends JController
{

	

	/*function sorrend(){
		parent::__construct(); 
		$Itemid = $this->Itemid;
		$model =$this->getModel($this->model);
		$model -> xmlParser -> sorrend();
		$id = JRequest::getVar("id", "");
		echo $this->sorrendLink;
		die;
		$this->setRedirect($this->sorrendLink);
	}*/

	function __construct($config = array())
	{
		
		global $option, $Itemid;
		$this->Itemid = $Itemid;
		$this->option = $option;
		if($tmpl=JRequest::getVar("tmpl", "") ) {
			$this->tmpl="&tmpl={$tmpl}";
		}else{
			$this->tmpl="";
		}
		//echo $this->tmpl;
		$this->user = jfactory::getuser();
		$this -> setSessionVar("kapcsolodo_id", JRequest::getVaR("kapcsolodo_id", $this -> getSessionVar("kapcsolodo_id") ) );
		//echo $this->getSessionVaR("kapcsolodo_id")." kapcsolodo_id **";
		//die($this->getSessionVaR("kapcsolodo_id")." kapcsolodo_id **");
		
		parent::__construct($config); 
		
		//$this->setAllapotMegtartLink();	
		
	}// function
	
	function setAllapotMegtartLink(){
			//die($this->model);
			$model = $this->getModel($this->model);
			
			if(@$model->xmlParser){
				$this->session();	
				$node = $model->xmlParser->getGroup( "condFields" );
					foreach($node->childNodes as $e_){
						if(is_a($e_, "DOMElement")){
							$name = $e_->getAttribute('name');
							$v = $this->getSessionVar($name);
							$this->redirectSaveOk.="&{$name}={$v}";
							$this->cancelLink.="&{$name}={$v}";	
						}
					}
			}
		}
	function keep(){
		//die("-------");
		$this->session();
		JRequest::setVar( 'view', $this->model );
		//JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	function session()
	{ 
		//$data = $_REQUEST;
		@$sess =& JSession::getInstance();
		$model = $this->getModel($this->model);
		foreach($model->getFormFieldArray() as $varname ){
			$value = JRequest::getVar($varname);
			//echo $varname." : {$value} *********************<br />";
			if($value) {
				$sess->set($varname, $value);
			}else{
				if( isset($value) && $varname<>"id" ){
					$sess->set($varname, "");
				}
			}
		}
		//exit;
	}
	
	function add(){
		$model = $this->getModel($this->model);
		$model->deleteSession();
		//die("-----");
		$this->setRedirect($this->addLink);
	}
	
	

	function add_(){
		$model = $this->getModel($this->model);
		$model->deleteSession();
		JRequest::setVar("view", $this->view);
		parent::display();
	}
	
	function display()
	{
		JRequest::setVar("view", $this->view);
		parent::display();
	}// function


	function edit()
	{
		$this->session();
		JRequest::setVar( 'view', $this->model );
		JRequest::setVar('hidemainmenu', 1);
		//exit;
		parent::display();
	}// function

	function apply()
	{
		JRequest::setVar('hidemainmenu', 1);
		$model = $this->getModel($this->model);
		$this->session();	
		$errorFields = $model->checkMandatoryFields();
		//print_r($errorFields);exit;		
		if(!count($errorFields) ){
			if ($id = $model->store()) {
				$msg = JText::_( 'SIKERES MENTES' );
			} else {
				$msg = JText::_( 'HIBAS MENTES' );
			}
			//parent::display();
		}else{
			$msg = JText::_( 'HIBAS MEZOK' );		
		}
		//print_r($_POST);exit;
		//$urlParameters = $this->getUrlParameters($model);
		$errorFields_="&errorFields[]=";
		$errorFields_ .= implode("&errorFields[]=",$errorFields);
		$task = "edit";
		if($id){
			$fromlist = 1;
		}else{
			$fromlist="";
			$id = $this->getSessionVar("id");
			if(!$id){
				//$task = "add";
			}
		}
		$link = "index.php?option={$this->option}&task={$task}&controller={$this->controller}&cid[]={$id}&fromlist={$fromlist}{$errorFields_}{$this->tmpl}";
		//die($link);
		$this->setRedirect($link, $msg);
	}// function

	function getUrlParameters($model){
		$url="";

		foreach($model->getFormFieldArray() as $parName){
			$val = JRequest::getVar($parName);
			if(is_array($val)){
				//echo $val;
				//exit;
				foreach($val as $v){
					$url.="{$parName}[]={$v}&";					
				}
			}else{
				$url.="{$parName}={$val}&"; 
			}
		}
		return $url;
	}

	function save()
	{
		$this->session();
		$model = $this->getModel($this->model);
		$errorFields = $model->checkMandatoryFields();
		//print_r(JRequest::getVar("kat_id"));
		//die;
//die($this->model);
		if(!count($errorFields) ){
			if ($id = $model->store() ) {
				$msg = JText::_( 'SIKERES MENTES' );
			} else {
			//die($errorFields);
				$msg = JText::_( 'Hiba tortent mentes kozben' );
			}
			$link = $this->redirectSaveOk;
			$model->deleteSession();
			$this->setRedirect($link, $msg);
		}else{
			JRequest::setVar('hidemainmenu', 1);
			$msg = JText::_( 'HIBAS MEZOK' );
			$errorFields_="&errorFields[]=";
			$errorFields_.=implode("&errorFields[]=",$errorFields);
			$link = "index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$id}{$errorFields_}{$this->tmpl}";
			$this->setRedirect($link, $msg);
		}
	}// function

	function save_and_new()
	{
		//die('lefut');
		$this->session();	
		$model = $this->getModel($this->model);
		$errorFields = $model->checkMandatoryFields();
//die($this->model);
		if(!count($errorFields) ){
			if ($id = $model->store() ) {
				$msg = JText::_( 'SIKERES MENTES' );
			} else {
			//die($errorFields);
				$msg = JText::_( 'Hiba tortent mentes kozben' );
			}
			$link = $this->addLink;
			$model->deleteSession();
			$this->setRedirect($link, $msg);
		}else{
			JRequest::setVar('hidemainmenu', 1);
			$msg = JText::_( 'HIBAS MEZOK' );
			$errorFields_="&errorFields[]=";
			$errorFields_.=implode("&errorFields[]=",$errorFields);
			$link = $this->addLink;
			
			$this->setRedirect($link, $msg);
		}
	}



	function remove()
	{
		$model = $this->getModel($this->model);
		if(!$model->delete($this->jTable)) {
			$msg = JText::_( "HIBA MENTES KOZBEN" );
		} else {
			$msg = JText::_( "SIKERES TORLES" );
		}
		//die($option);
		$this->setRedirect( "index.php?option={$this->option}&controller={$this->controller}{$this->tmpl}", $msg );
	}// function

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Cancel' );
		$model =  $this->getModel($this->model);
	 	$model->deleteSession();		
		$this->setRedirect( $this->cancelLink, $msg );
	}// function

	function setSessionVar($var, $value){
		@$sess =& JSession::getInstance();
		$sess->set( $var, $value );
	}
	
	function getSessionVar($var){
		@$sess =& JSession::getInstance();
		//$o_ = $sess->get("padData");
		return $sess->get($var);
		//print_r($o_); exit;
		//return $o_->$var;
	}

}//class
?>