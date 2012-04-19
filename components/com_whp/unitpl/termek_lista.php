<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class termek_lista extends base_template{
	function __construct(){
		
	}

	function getTpl()
	{
		@$class = $this->kampanyok[$this->cell->kampany->grafika]['lista_class'];
		if ($this->cell->akc_belyeg) $class = $this->kampanyok['kampany_4']['lista_class'];
		ob_start();
		//print_r($this->cell);
		?>
        <div class="<?php echo $class; ?>">
        <table>
            <tr>
            	<td class="td_picture">{listaKep}</td>
            	<td class="td_data"><h3>{nev}</h3><div class="desc">{leiras_rovid}</div>
            		<div class="readmore">{reszletek}</div>
            	</td>
            	
            </tr>
        </table>
       </div>
 
        
    
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
	function reszletek(){
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$Itemid = $active->id;
		
		$tpl = "<a class=\"a_reszletek\" href=\"index.php?option=com_whp&controller=termek&cond_kategoria_id={$this->cell->kategoria_id}&Itemid={$Itemid}&termek_id={$this->cell->id}\"><span class=\"span_reszletek\">".jtext::_("RESZLETEK")."</span></a>";
		return $tpl;	
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
