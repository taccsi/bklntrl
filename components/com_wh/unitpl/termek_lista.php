<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);

class termek_lista extends base_template{
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
            <div class="head">
            	<div class="termeknev">{checker}{nev}</div>
            	{allapotIkonok}
            </div>
            <div class="body">
	            <table class="table_listing" border="0" cellspacing="0" cellpadding="0">
	              <tr>
	                <td>{elsokep}</td>
	                <td>
	                	<div id="arazoLite">{arazoLite}</div>
	                </td>
	              </tr>
	            </table>
	       </div>
        </div>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}	
	
	function nev(){
		global $Itemid;
		
		$lim = 12;
		$nev = strip_tags( $this->cell->nev );
		if (strlen($nev)> $lim){
			$nev = substr($nev,0,$lim).'...';
		}
		
		$tmpl = JRequest::getVar('tmpl');
		$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
		$link = 'index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='.$this->cell->id;
		
		$ret = "<a href=\"{$link}\" title=\"{$this->cell->nev} ({$this->cell->kategorianev})\" alt=\"{$this->cell->nev} ({$this->cell->kategorianev})\">{$nev}</a>";
		
		return $ret;
	}
}
?>