<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class termek_ertekeles extends base_template{
	function __construct(){
		
	}

	function getTpl()
	{
		ob_start();
		//print_r($this->cell);
		?><div class="komment_head"><div class="name">{nev}</div><div class="date">{datum}</div></div><div class="komment_leir">{leiras}</div><div class="komment_rate">{csillagok}</div>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
}
?>
