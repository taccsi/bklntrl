<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpControllermove extends controllBase
{
	var $view = "move";
	var $model = "move";
	var $controller = "move";	
	/*var $redirectSaveOk = "index.php?option=com_wh&controller=move";
	var $cancelLink = "";
	var $addLink = "";	*/

	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->session();
	}// function
	
	function nagyReset(){
		$model = $this->getModel($this->model);
		$model->nagyReset();
		$this->setRedirect("index.php?option=com_whp&controller=move", "KESZ");				
	}
	
	function expTermekek(){
		$model = $this->getModel($this->model);
		$model->expTermekek();
		$this->setRedirect("index.php?option=com_whp&controller=move", "KESZ");				
	}
	
	function expKategoriak(){
		$model = $this->getModel($this->model);
		$model->expKategoriak();
		$this->setRedirect("index.php?option=com_whp&controller=move", "KESZ");				
	}
}//class
?>