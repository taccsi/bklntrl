<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkategoriak extends controllBase
{
	var $view = "kategoriak";
	var $model = "kategoriak";
	var $controller = "kategoriak";	
	var $addLink = "index.php?option=com_wh&controller=kategoria&task=edit&cid[]=&fromlist=1";

	function __construct($config = array())
	{
		parent::__construct($config);
	}// function

	function edit()
	{
		$cid = JRequest::getVar("cid");
		//print_r($cid); exit;
		$link = "index.php?option={$this->option}&controller=kategoria&task=edit&cid[]={$cid[0]}";
		//die($link);
		$this->setRedirect($link);
		parent::display();
	}// function
	
	function remove(){
		$model = $this->getModel($this->model);
		$arrNemTorolheto = $model->delete();
		if( count($arrNemTorolheto) ){
			$msg = JText::_("EGY VAGY TOBB KATEGORIA NEM TOROLHETO");
		}else{
			$msg = JText::_("SIKERES TORLES");

		}
		$this->setRedirect("index.php?option={$this->option}&controller={$this->controller}", $msg);
	}
	
	function sorrend(){
		parent::__construct(); 
		global $Itemid;
		$model =$this->getModel($this->model);
		$model -> sorrend();
		$id = JRequest::getVar("id", "");
		$this->redirectSaveOk = "index.php?option=com_wh&controller={$this->controller}&Itemid={$Itemid}";
		$this->setAllapotMegtartLink();
		$this->setRedirect($this->redirectSaveOk);
	}	

}//class
?>