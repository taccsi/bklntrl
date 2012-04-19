<?php
defined( '_JEXEC' ) or die( '=;)' );

class whControllerKepek extends controllBase
{
	var $view = "kepek";
	var $model = "kepek";
	var $controller = "kepek";
	var $addView = "kep";
	var $addLink = "index.php?option=com_wh&controller=kep&task=edit&fromlist=1&cid[]=";
	var $jTable = "wh_kep";
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->addLink = "index.php?option=com_wh&controller=kep&task=edit&fromlist=1&cid=[]{$this->tmpl}";
	}// function

	function bezar(){
		//die("---");
		global $Itemid;
		$kapcsolodo_id = $this->getSessionVar("kapcsolodo_id");
		$link = "index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$kapcsolodo_id}";
		?>
        <script >
        parent.document.location="<?php echo $link ?>";
        </script>
        <?php		
	}
	

}//class
?>