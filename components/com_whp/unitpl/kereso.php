<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class kereso {
	function __construct(){
	}

	function getTpl()
	{
		ob_start();
		//print_r($this->cell);
		?>
        <div class="szuperkereso">
            <div class="bottom">
                <div class="gyarto">{cond_nev}</div>
                <div class="kereso">{cond_submit}</div>
            </div>
        </div>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
}
?>
