<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class kiemelt_termekek extends base_template{
	function __construct(){
		$this->document = JFactory::getdocument();
		$this->document->addscript('modules/mod_whp_kiemelttermek/js.js');
	}

	function getTpl()
	{
		@$class = $this->kampanyok[$this->cell->kampany->grafika]['lista_class'];
		ob_start();
		//print_r($this->cell);
		?>
       <div onmouseover="{setHover}" onmouseout="setMouseOut()">
         <table>
                <tr> 
                 <td class="listakep">{listaKep}</td>
                <td class="nev"><a href="{termeklink}">{nev}<br /><span class="kicsi">Ár: {arHTML}</span></a>
</td></tr>
            </table></div>
<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
	function reszletek(){
		$Itemid = $this->Itemid;
		
		$tpl = "<a class=\"a_reszletek\" href=\"index.php?option=com_whp&controller=termek&cond_kategoria_id={$this->cell->kategoria_id}&Itemid={$Itemid}&termek_id={$this->cell->id}\">".jtext::_("RESZLETEK")."</a>";
		return $tpl;	
	}
	
	function setHover(){
		
		$ret = "setHover('{$this->cell->modulHatter}');";
		return $ret;
	}
	
	function leiras(){
		//print_r($this->cell->pics);
		$lim = 120;
		$leiras = strip_tags( $this->cell->leiras );
		if (strlen($leiras) > $lim) {$leiras = substr($leiras,0,$lim).'...';  } 
		return $leiras;
	}
	
	function nev_(){
		ob_start();
		//print_r($this->cell->pics);
		if (strlen($this->cell->nev) > 45) {$nev = substr($this->cell->nev,0,45).'...';  } else {$nev = $this->cell->nev;}
		?>
<a href="<?php echo $this->getListLink() ?>"><?php echo ($nev) ?></a>
<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;	
	}
	
	function szerzocim(){
		
	
	}
	
	

}
?>
