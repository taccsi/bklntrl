<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerSzamlap extends controllBase
{
   var $view = "szamlap";
   var $model = "szamlap";
   var $controller = "szamlap";
   var $addLink = "index.php?option=com_wh&controller=termek&task=edit&cid[]=&fromlist=1";
   var $jTable = ""; 
   
   function __construct($config = array())
   {
      parent::__construct($config);
      //$this->getszamlap();
      
   }// function

   function import(){
		$model = $this->getModel($this->model);
		if($filename = $model->import()){
		   $msg = jtext::_("SIKERES_IMPORT");
		   $this->setRedirect("index.php?option=com_wh&controller=rendelesek", $msg);		   
		}else{
		   $feldolgozott_sorok = 0;
		   $msg = jtext::_("SIKERTELEN_IMPORT");
		   $this->setRedirect("index.php?option=com_wh&controller=szamlap");
		}
		//$this->setRedirect("index.php?option=com_wh&controller=szamlap&import=1&feldolgozott_sorok={$feldolgozott_sorok}", $msg);
		//die("{$filename}");
		//$this->setRedirect("http://office.trifid.hu/fapadoskonyvhu/admin/{$filename}");
		$this->setSessionVar("filename__", $filename );
		jrequest::setvar("layout", "export");
		//jrequest::setvar("filename", $filename );		
		$this->display();		
   }

   function edit(){
      $cid = JREquest::getVaR("cid", array(), "array");
      $id=$cid[0];
      $this->setRedirect("index.php?option=com_wh&controller=termek&task=edit&cid[]={$id}&fromlist=1");
   }
   
}//class
?>