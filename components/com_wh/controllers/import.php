<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerImport extends controllBase
{
	var $view = "import";
	var $model = "import"; 
	var $controller = "import";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=import";
	var $cancelLink = "index.php?option=com_wh&controller=import";
	var $addLink = "index.php?option=com_wh&controller=import&task=edit";	

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function
	
	function toroldb(){
		$model = $this->getModel("import");	
		$model->toroldb();
		global $Itemid ;
		$this->setRedirect("index.php?option=com_wh&controller=import&Itemid={$Itemid}", $msg);
	}
	
	function feldolgoz_csv_csak_arak(){
		$model = $this->getModel("import");
		if( $sorok = $model->feldolgoz_csv_csak_arak() ){
			$msg = JText::_("SIKERES FELDOLGOZAS, FELTOLTOTT SOROK: {$sorok}");
		}else{
			$msg = JText::_("HIBA");
		}
		global $Itemid ;
		$this->setRedirect("index.php?option=com_wh&controller=import&Itemid={$Itemid}", $msg);
	}
	
	function feldolgoz_csv2(){
		$model = $this->getModel("import");
		if( $sorok = $model->feldolgoz_csv2() ){
			$msg = JText::_("SIKERES FELDOLGOZAS, FELTOLTOTT SOROK: {$sorok}");
		}else{
			$msg = JText::_("HIBA");
		}
		global $Itemid ;
		$this->setRedirect("index.php?option=com_wh&controller=import&Itemid={$Itemid}", $msg);
	}
	
	function feldolgoz_csv(){
		$model = $this->getModel("import");
		if( $sorok = $model->feldolgoz_csv() ){
			$msg = JText::_("SIKERES FELDOLGOZAS, FELTOLTOTT SOROK: {$sorok}");
		}else{
			$msg = JText::_("HIBA");
		}
		global $Itemid ;
		$this->setRedirect("index.php?option=com_wh&controller=import&Itemid={$Itemid}", $msg);
	}

}//class
?>