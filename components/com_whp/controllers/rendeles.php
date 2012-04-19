<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllerRendeles extends controllBase
{
	var $view = "rendeles";
	var $model = "rendeles";
	var $controller = "rendeles";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=rendeles";
	var $cancelLink = "index.php?option=com_whp&controller=rendelesek";

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
		if( !$this->user->id ){
			$link = "index.php?option=com_whp&controller=felhasznalo";
			$msg =JText::_("KEREM JELENTKEZZEN BE VAGY REGISZTRALJON");
			//$this->setRedirect ( $link, $msg );
		}else{
		/*
			$link = "index.php";
			$msg =JText:_("kerem jelentkezzen be");			
		*/	
		} 
	}// functiondie()

	function save()
	{
		$Itemid = $this->Itemid;		
		$this->session();
		$model = $this->getModel($this->model);
		$errorFields = $model->checkMandatoryFields();
		if(!count($errorFields) ){
			if ($id = $model->store() ) {
				$msg = "";
			} else {
				$msg = JText::_( 'Hiba tortent mentes kozben' );
			}
			$model->deleteSession();
			//$link = jroute::_("index.php?option=com_whp&Itemid={$Itemid}");
			$link = jroute::_("index.php?option=com_whp&controller=rendeles&layout=thankyou&Itemid={$this->Itemid}");
			$this->setRedirect($link, $msg);
		}else{
			$msg = JText::_( 'HIBAS_RENDELESI_ADATOK' );
			$errorFields_="&errorFields[]=";
			$errorFields_.=implode("&errorFields[]=",$errorFields);
			$link = jroute::_("index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$errorFields_}&Itemid={$Itemid}");
			$this->setRedirect($link, $msg);
		}
	}// function
}//class
?>