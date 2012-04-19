<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerTermekek extends controllBase
{
	var $view = "termekek";
	var $model = "termekek";
	var $controller = "termekek";
	var $addLink = "index.php?option=com_wh&controller=termek&task=edit&cid[]=&fromlist=1";
	var $redirectSaveOk = "index.php?option=com_wh&controller=termekek";
	var $jTable = "wh_termek";	
	
	function __construct($config = array())
	{
		parent::__construct($config);
		//$this->getTermekek();
		$this->session();
	}// function

	function remove(){
		$model = $this->getModel("termekek");
		$model->torolTermekek();
		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek";
		$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk , JText::_("TERMEK_TOROLVE") );
	}

	function besz_null(){
		global $Itemid;
		$model = $this->getModel("termekek");
		$model->setBeszarNull( );
		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek";
		$this->setAllapotMegtartLink();
		$this->setredirect($this->redirectSaveOk , JText::_("BESZARAK NULLAZVA") );		
	}
	function klonoz(){
		global $Itemid;
		global $Itemid;
		$cid = JRequest::getVar("cid", array() );
		$model = $this->getModel("termekek");
		$uj_id = $model->klonoz( $cid[0] );
		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek";
		$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk."&cond_variaciokIdArr={$uj_id}" , JText::_("TERMEK_KLONOZVA") );
	}

	function elad_null(){
		global $Itemid;
		$model = $this->getModel("termekek");
		$model->setEladArNull( );
		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek";
		$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk , JText::_("BESZARAK NULLAZVA") );		
	}
	
	function arazas(){
		global $Itemid;
		$model = $this->getModel("termekek");
		$model->arazas( );
		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek";
		$this->setAllapotMegtartLink();
		$this -> setredirect($this->redirectSaveOk , JText::_("TERMEKEK ATARAZVA") );		
	}

	function kivalaszt_rendeleshez(){
		global $Itemid;
		$cid = JRequest::getVar("cid", array() );
		$model = $this->getModel("termekek");
		$kapcsolodo_id = $this->getSessionVar("kapcsolodo_id");
		$model->kivalaszt_rendeleshez($cid, $kapcsolodo_id );
		$link = "index.php?option=com_wh&controller=rendeles&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$kapcsolodo_id}";
		//$this->setRedirect($link);
		?>
        <script >
        parent.document.location="<?php echo $link ?>";
        </script>
        <?php
	}

	function kivalaszt(){
		global $Itemid;
		$cid = JRequest::getVar("cid", array() );
		$model = $this->getModel("termekek");
		$model->kivalaszt("kapcsolodo_termek_id", "#__wh_termek");
		$kapcsolodo_id = $this->getSessionVar("kapcsolodo_id");
		$link = "index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$kapcsolodo_id}";
		?>
        <script >
        parent.document.location="<?php echo $link ?>";
        </script>
        <?php		
		//$this->setRedirect("");
		//print_r($cid);
		//die;
	}
	
	function mentTermekek(){
		//die("-----");
		$this->session();
		global $Itemid;
		$model = $this -> getModel($this -> model);
		
		$model->saveProductPrices();
		/*
		$model -> mentBeszallitoAr();
		$model->mentKategoria();
		$model->mentSpecTermVar();
		*/
		$model->mentAktivAllapot();
		$model -> mentKampany();
		/*
		$model->mentUzlet();
		$model->mentUzletKapcsolo();
		*/
		$id = $this->getSessionVar("id");

		$this->redirectSaveOk = "index.php?option=com_wh&controller=termekek&Itemid={$Itemid}";
		$this->setAllapotMegtartLink();

		$this -> setredirect($this->redirectSaveOk , JText::_("SIKERES MENTES") );		
	}
	
	function cancel()
	{
		parent::display();
	}// function

}//class
?>