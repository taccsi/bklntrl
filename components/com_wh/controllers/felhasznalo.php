<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerFelhasznalo extends controllBase
{
	var $view = "felhasznalo";
	var $model = "felhasznalo";
	var $controller = "felhasznalo";	
	var $redirectSaveOk = "index.php?option=com_whp&controller=basket&Itemid=";
	var $cancelLink = "index.php?option=com_whp&controller=felhasznalo";
	var $addLink = "";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->redirect_();
	}
	
	function logout(){
		global $mainframe, $Itemid;
		$app = &JFactory::getApplication();
		$app->logout();
		$msg = JText::_("SIKERESSEN KIJELENKEZETT");
		$link = JRoute::_("index.php?option=com_wh&controller=felhasznalo&Itemid={$Itemid}");
		$this->setRedirect($link, $msg);
	}// function
}//class
?>