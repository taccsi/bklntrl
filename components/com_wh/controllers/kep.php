<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerkep extends controllBase
{
	var $view = "kep";
	var $model = "kep";
	var $controller = "kep";	
	var $redirectSaveOk = "index.php?option=com_wh&controller=kepek";
	var $cancelLink = "index.php?option=com_wh&controller=kepek";

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->redirectSaveOk = "index.php?option=com_wh&controller=kepek{$this->tmpl}";
		//$this->session();
	}// function
	
	function megsemKep(){
		$this->session();
		$model = $this -> getModel($this -> model);
		$kep_id = $this->getSessionVar("id");
		$model -> torolKep( $kep_id );
		?>
        <script>
        	parent.fb.end(true); 
        </script>
        <?php
		/*
		$id = JRequest::getVar("id", "");
		$redirect = "index.php?option=com_wh&controller=termek&task=edit&fromlist=1&cid[]={$id}";
		$this -> setredirect($redirect, JText::_("SIKERES TORLES"));
		*/
	}
	
	function hozzaadKep(){
		global $Itemid;
		$termek_id = $this->getSessionVar("kapcsolodo_id");
		$model = $this->getModel( $this->model );
		$uj_kep_id = $model->hozzaadKep( $termek_id );
		$link = "index.php?option=com_wh&controller=kep&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$uj_kep_id}&tmpl=component";
		$this->setRedirect( $link );	
	}

}//class
?>