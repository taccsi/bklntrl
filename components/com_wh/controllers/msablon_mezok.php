<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllermsablon_mezok extends controllBase
{
	var $view = "msablon_mezok";
	var $model = "msablon_mezok";
	var $controller = "msablon_mezok";
	var $addView = "msablon_mezo";
	var $jTable = "wh_msablonmezo";
	var $redirectSaveOk = "index.php?option=com_wh&controller=msablon_mezok&";			

	function __construct($config = array())
	{
		$user = &JFactory::getUser();
		parent::__construct($config);
		$this->addLink .= "index.php?option=com_wh&controller=msablon_mezo&task=edit&fromlist=1&cid[]={$this->tmpl}";	
		$this->session();
		//echo $this->getSessionVaR("kapcsolodo_id")." kapcsolodo_id";					
	}// function
	
	function kivalaszt(){
		//die("---");
		global $Itemid;
		$cid = JRequest::getVar("cid", array() );
		$model = $this->getModel("msablon_mezok");
		$model->kivalaszt("mezo_id", "#__wh_msablon");
		$kapcsolodo_id = $this->getSessionVar("kapcsolodo_id");
		$link = "index.php?option=com_wh&controller=msablon&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$kapcsolodo_id}";
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

}//class
?>
