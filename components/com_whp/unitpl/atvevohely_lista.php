<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class atvevohely_lista extends base_template{
	function __construct(){
		
	}

	function getTpl()
	{
		ob_start();
		//print_r($this->cell); 
		?>

        <div class="listaelem">
      <table>
                <tr>
                    
                    <td class="info">
                        <div>
                          <div class="atvhely_nev">{nev}</div>
                          <div class="atvhely_adatok">{atvhely_adatok}</div>
                            
                        </div>
                    </td>
               </tr>
            </table>
             
        </div>
        
<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
	
	function atvhely_adatok(){
		//print_r($this->cell);
		ob_start();
		?>
		<div class="row"><?php echo '<strong>'.JText::_('CIM').':</strong> '.$this->cell->irszam.', '.$this->cell->telepules.', '.$this->cell->utca_hazszam ?> </div>
        <div class="row"><?php echo '<strong>'.JText::_('TELEFON').':</strong> '.$this->cell->telefon ?> </div>
        <div class="row"><?php echo '<strong>'.JText::_('NYITVATARTAS').':</strong> '.$this->cell->nyitvatartas ?> </div>
        
<?php 
		
		$tmp = ob_get_contents();
		ob_end_clean();
		return $tmp;	
	}
	
	function reszletek(){
		$Itemid = $this->Itemid;
		
		$tpl = "<a class=\"a_reszletek\" href=\"index.php?option=com_whp&controller=termek&cond_kategoria_id={$this->cell->kategoria_id}&Itemid={$Itemid}&termek_id={$this->cell->id}\">".jtext::_("RESZLETEK")."</a>";
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
