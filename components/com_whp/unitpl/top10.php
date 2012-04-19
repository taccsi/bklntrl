<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class top10 extends base_template{
	function __construct(){
	}

	function getTpl()
	{
		
		ob_start();
		//print_r($this->cell);
		?>
        	<table>
                <tr>
                	<td class="kep">{listaKep}</td>
                </tr>
                <tr>
                	<td class="nev">{nev}</td>
                 </tr>
            </table>
        <?
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
}
?>