<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerbeszallitok extends controllBase
{
	var $view = "beszallitok";
	var $model = "beszallitok";
	var $controller = "beszallitok";
	var $addView = "beszallito";
	var $addLink = "index.php?option=com_wh&controller=beszallito&task=edit&fromlist=1&cid[]=";
	var $redirectSaveOk = "index.php?option=com_wh&controller=beszallitok&";	
	var $jTable = "wh_beszallito";
	function __construct($config = array())
	{
		$user = &JFactory::getUser();
		
		
		
		$tmpl = JRequest::getVar('tmpl');
		$this->tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
		$this->addLink .= $this->tmpl;
		parent::__construct($config);
		//$this->session();
		
	}// function
	
	function kivalaszt(){
		$this->session();
		global $Itemid;
		$cid = JRequest::getVar("cid", array() );
		$model = $this->getModel("beszallitok");
		$kapcsolodo_id = $this->getSessionVar("kapcsolodo_id");
		$model->kivalaszt($kapcsolodo_id, $cid);
		$link = "index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$kapcsolodo_id}";
		//die($link);
		?>
        <script >
        parent.document.location="<?php echo $link ?>";
        </script>
        <?php		
		//$this->setRedirect("");
		//print_r($cid);
		//die;
	}
	
	function bejelentkezes(){
		$user = JFactory::getUser();
		if($user->usertype == "Super Administrator"){
			$link = "index.php?option=com_wh&controller=kimutatas";
		}else{
			$link="index.php?option=com_wh&Itemid=2";
		}

		$this->setRedirect($link);
		parent::display();
	}
	
	function show(){
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=beszallito&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}

	function edit()
	{
		$cid = JRequest::getVar("cid");
		$link = "index.php?option={$this->option}&controller=beszallito&task=edit&cid[]={$cid[0]}&fromlist=1".$this->tmpl;
		$this->setRedirect($link);
		parent::display();
	}// function
	
	

	function cancel()
	{
		parent::display();
	}// function

}//class
?>