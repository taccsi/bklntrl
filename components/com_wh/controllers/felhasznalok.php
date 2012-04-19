<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerfelhasznalok extends controllBase
{
	var $view = "felhasznalok";
	var $model = "felhasznalok";
	var $controller = "felhasznalok";
	var $addView = "felhasznalo";
	var $addLink = "index.php?option=com_wh&controller=felhasznalo&task=edit&fromlist=1&cid[]=";
	var $jTable = "wh_felhasznalo";
	function __construct($config = array())
	{
		parent::__construct($config);
	}// function
	
	function mentFelhasznalok(){
		$this->session();
		global $Itemid;
		$model = $this->getModel($this->model);
		$model -> mentFcsoport();		
		$id = $this->getSessionVar("id");
		//$this->setSessionVar("beszallito_id",JRequest::getVar("beszallito_id_",""));
		$this->redirectSaveOk = "index.php?option=com_wh&controller=felhasznalok&Itemid={$Itemid}";
		$this->setAllapotMegtartLink();
		//die($this->redirectSaveOk);
		$this -> setredirect($this->redirectSaveOk , JText::_("SIKERES MENTES") );		
	}


}//class
?>