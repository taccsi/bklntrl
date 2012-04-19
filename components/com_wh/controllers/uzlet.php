<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControlleruzlet extends controllBase
{
	var $view = "uzlet";
	var $model = "uzlet";
	var $controller = "uzlet";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=uzletek";
	var $cancelLink = "index.php?option=com_wh&controller=uzletek";
	var $addLink = "index.php?option=com_wh&controller=uzlet&task=edit";	

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
		$link = "index.php?option=com_wh&controller=uzlet&layout=tomegesfunkciok";
		if($db){
			$this->setRedirect($link, $db." ".jtext::_("EMAIL_SIKEKERESEN_KIKULDVE") );
		}else{
			$this->setRedirect($link, $db." ".jtext::_("SIKERTELEN_EMAIL_KULDES") );
		}
	}

}//class
?>