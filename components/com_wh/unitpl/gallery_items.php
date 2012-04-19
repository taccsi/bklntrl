<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);

class gallery_items extends base_template{
	var $th_szel = 110;
	var $th_mag = 110;
		
	function __construct(){
		parent::__construct();
	}

	function getTpl()
	{
		ob_start();
		//print_r($this->cell->pics);
		//print_r($this->cell);
		?>
		<div class="div_listitem">
			{image}
        </div>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}	
	
	
}
?>