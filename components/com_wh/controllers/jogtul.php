<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerjogtul extends controllBase
{
	var $view = "jogtul";
	var $model = "jogtul";
	var $controller = "jogtul";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=jogtulok";
	var $cancelLink = "index.php?option=com_wh&controller=jogtulok";
	var $addLink = "index.php?option=com_wh&controller=jogtul&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function

	function csvExport(){
		jrequest::setVar( "layout", "csvexport" );
		$model = $this->getModel($this->model);
		$db = $model->csvExport();
		$this->display();		
	}

	function jutalekNyomtatas(){
		jrequest::setVar( "layout", "nyomtatas" );
		jrequest::setVar( "format", "raw" );
		$this->display();
	}

	function tomegesFunkciok(){
		jrequest::setvar( "layout", "tomegesfunkciok" );
		$this->display();
	}
	
	function tomegesNyomtatas(){
		jrequest::setVar( "layout", "nyomtatas" );
		jrequest::setVar( "format", "raw" );
		$this->display();
	}
	
	function tomegesEmailKuldes(){
		$model = $this->getModel($this->model);
		$db = $model->tomegesEmailKuldes();
		$link = "index.php?option=com_wh&controller=jogtul&layout=tomegesfunkciok";
		if($db){
			$this->setRedirect($link, $db." ".jtext::_("EMAIL_SIKEKERESEN_KIKULDVE") );
		}else{
			$this->setRedirect($link, $db." ".jtext::_("SIKERTELEN_EMAIL_KULDES") );
		}
	}

}//class
?>