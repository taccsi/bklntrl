<?php

defined( '_JEXEC' ) or die( '=;)' );



class whpControllerkosar extends ControllBase

{

	var $view = "kosar";

	var $model = "kosar";

	var $controller = "kosar";	



	function __construct($config = array())

	{

		parent::__construct($config);

		//$this->session();

		/*if(!$this->checkKitoltotteAzAdatokat() ){

			$this->setRedirect("index.php?option=com_whp&controller=user&Itemid={$Itemid}");

		}	*/

	}// function



	function keep(){

		//parent::__construct($config);	 

		//$this->session();

		parent::display();

	}

	

	function finish(){

		parent::__construct();

		$Itemid = $this->Itemid;

		$modelOrder = $this->getModel("rendeles");

		$errorFields = array();

		$arr = array("payment", "shipping");

		foreach($arr as $a){

			if(!$modelOrder->xmlParser->getAktVal($a) ){

				$errorFields[] = $a;

			}

		}

		//print_r($errorFields);exit;		

		if(!count($errorFields) ){

			$model = $this->getModel($this->model);

			$model->finish();

			$link = JRoute::_("index.php?option=com_whp&controller=rendelesek&Itemid={$Itemid}");

			$this->setRedirect($link, JText::_("KOSZONJUK A MEGRENDELEST"));

		}else{

			$t = "";

			foreach($arr as $a){

				if($val = JREquest::getVar($a) ){

					$t.="&{$a}={$val}&";

				}

			}

			$msg = JText::_( 'KEREM ADJA MEG A RENDELESHEZ TARTOZO OSSZES ADATATOT' );		

			$link = JROUTE::_("index.php?option=com_whp&controller=kosar{$t}");

			$this->setRedirect($link, $msg);

		}

	}

	

	function atadas(){

		parent::__construct();

		$Itemid = $this->Itemid;

		

			$model = $this->getModel($this->model);

			$model->atadas();

			$link = JRoute::_("index.php?option=com_whp&controller=rendeles&task=checkout&Itemid={$Itemid}");

			$this->setRedirect($link);

		

	}



	function appl__y()

	{

		$this->session();	

		JRequest::setVar('hidemainmenu', 1);

		$model = $this->getModel($this->model);

		$errorFields = $model->checkMandatoryFields();

		//print_r($errorFields);exit;		

		if(!count($errorFields) ){

			if ($id = $model->store()) {

				$msg = JText::_( 'Sikeresen elmentve' );

			} else {

				$msg = JText::_( 'Hiba tortent mentes kozben' );

			}

			//parent::display();

		}else{

			$msg = JText::_( 'Hibasan, vagy hianyosan kitoltott mezok' );		

		}

		//print_r($_POST);exit;

		//$urlParameters = $this->getUrlParameters($model);

		$errorFields_="&errorFields[]=";

		$errorFields_ .= implode("&errorFields[]=",$errorFields);

		if($id) $fromlist = 1; else $fromlist="";

		$link = "index.php?option={$this->option}&task=edit&controller={$this->controller}&cid={$id}&fromlist={$fromlist}{$errorFields_}";

		$this->setRedirect($link, $msg);

	}// function

	

	/*function tetel_torol(){

		$Itemid = $this->Itemid;

		$model = $this->getModel($this->model);

		$model->tetel_torol();

		$link = JRoute::_("index.php?option=com_whp&controller=rendeles&Itemid={$Itemid}");

		//$this->setRedirect($link, JText::_("TETEL TOROLVE"));

	}*/

	

	/*function tetel_modosit(){

		$Itemid = $this->Itemid;

		$model = $this->getModel($this->model);

		$model->tetel_modosit();

		$link = JRoute::_("index.php?option=com_whp&controller=kosar&Itemid={$Itemid}");

		$this->setRedirect($link, JText::_("TETEL MODOSITVA"));

	}*/



	function add(){
		//die;
		$Itemid = $this->Itemid;
		$id = JRequest::getVar("id");
		$model = $this->getModel("kosar");
		//print_r($model); die();
		$msg = $model->add(); 
		//die('lefut');
		$termek_id = jrequest::getVar( "kosarba_id", "0" );
		$t_ = $model->getObj("#__wh_termek", $termek_id);
		$msg = ($msg) ? jtext::_($msg) : jtext::_("A_TERMEK_BEKERULT_A_KOSARBA");
		//$link=JRoute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$t_->kategoria_id}&termek_id={$termek_id}&Itemid={$Itemid}", $msg);		
		$link=JRoute::_("index.php?option=com_whp&controller=kosar&Itemid={$Itemid}");
		//die($link);
		$this->setRedirect($link, $msg);

	}

	

	function apply(){

		//die;

		$Itemid = $this->Itemid;

		$model = $this->getModel("kosar");

		$model->apply(); 

		$msg="";

		if(JRequest::getVar("finish")){

			$shippingadress = JRequest::getVar("shippingadress");

			$payment = JRequest::getVar("payment");

			$shipping = JRequest::getVar("shipping");

			$link=JRoute::_("index.php?option=com_whp&controller=rendeles&task=save&shippingadress={$shippingadress}&payment={$payment}&shipping={$shipping}");

		}else{

			$link=JRoute::_("index.php?option=com_whp&controller=kosar&Itemid={$Itemid}");		

		}

		//die($link);

		$this->setRedirect($link, $msg);

	}

	

	function reorder()

	{

		$Itemid = $this->Itemid;

		$model = $this->getModel("kosar");

		

		switch($model->reorder()){

			case -1:

				$msg = JText::_('Ujrarendeles technikai okokbol nem lehetseges.');

				break;

				

			case 0:

				$msg = JText::_('A korabbi rendeles tetelei bekerultek a kosarba.');

				break;

				

			case 1:

				$msg = JText::_('A korabbi rendeles tetelei - a mar nem kaphato termekek kivetelevel - bekerultek a kosarba.');

				break;

			

			case 2:

				$msg = JText::_('A korabbi rendeles tetelei mar nem rendelhetok, ezert az ujrarendeles nem lehetseges.');

				break;

		}

		

		$link=JRoute::_("index.php?option=com_whp&controller=kosar&Itemid={$Itemid}");

		$this->setRedirect($link, $msg);

	}

	

}//class

?>