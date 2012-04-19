<?php

defined( '_JEXEC' ) or die( '=;)' );

?>





<div class="div_wh_top_holder">		

    <h3 class="h3_contentheading"><?php echo JText::_("TERMEKEK") ?></h3>    
	<?php 
	if( JRequest::getVar("tmpl") == "component" ){
		$arr = array(JText::_("UJ"), JText::_("TOROL"), JText::_("KIVALASZT RENDELESHEZ") );
	}else{
		$arr = array( JText::_("KIVALASZT"), JText::_("KIVALASZT RENDELESHEZ") );
	}	
	?>

	<div class="div_wh_topmenu">
		<?php new whMenu($arr); ?>
	</div>

</div>

<div class="div_wh_clear"></div>

<div id="editcell">
	
	<div id="termekek_kategoriafa">
		<?php echo $this->kategoriafa ?>
	</div>
	<div id="termekek_right">
		<form action="index.php" method="get" name="adminForm" id="adminForm" >
		<?php		
			echo $this->search;
			echo $this->atarazas;
		
		
			$arr = array();
			if(count( $this->items)){
				$k = 0;
				global $Itemid;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
					$row = &$this->items[$i];
					$besz_nettoar="";
					$checked = JHTML::_('grid.id',   $i, $row->id );
					$tmpl = JRequest::getVar('tmpl');
					$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
					$link = 'index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='.$row->id ;
		
					$o = "";
					$o->CHECKED = $checked;
					$o->AKTIV = $row->aktivKapcsolo;
					//$o->KEZDOKEP = $row->elsokep;
					$o->TERMEK = "<table><tr>";
					if($this->aktTermek == $row->id){
						$o->TERMEK .= "<td class=\"termekimg\">" . $row->elsokep . "</td><td class=\"termeknev\"><a href=\"{$link}\" class=\"aktTermek\">{$row->nev}</a>". " ({$row->kategorianev})<br />{$row->cikkszam}</td>";
					}
					else {$o->TERMEK .= "<td class=\"termekimg\">" . $row->elsokep . "</td><td class=\"termeknev\"><a href=\"{$link}\">{$row->nev}</a>". " ({$row->kategorianev})<br />{$row->cikkszam}</td>";}
		
					$o->TERMEK .= "</tr></table>";
		
					//$o->KATEGORIA = "{$row->kategoria}";
					//$o->SPECTERMVAR = "{$row->specTermVar}";			
					//$o->UZLET = "{$row->uzlet}";						
					$o->KAMPANY = "{$row->kampany}";			
					//$o->BESZALLITO_ARAZAS = $row->beszallitoInput;
					//$o->KONKURENCIA = $row->konkurenciaArak;
					$o->KISKERAR = "<span class=\"lista_ar\">{$row->kiskerAr}</span>";
					//$o->KATEGORIA = $row->kategorianev;	
					$arr[] = $o; 
					$k = 1 - $k;
				}
		
				$lista = new listazo($arr, "adminlist", $this->pagination->getPagesLinks(), $this->pagination->getPagesLinks() );
				echo $lista->getLista();
			}else{
				echo jtext::_("NINCS TALALAT");
			}
		
		
			echo $this->termekBoxok;
		?>
	
		<input type="hidden" name="option" value="com_wh" />
		<input type="hidden" name="task" id="task" value="" />
	
		<?php 
		if( JRequest::getVar("tmpl") ){
		?>
	
		<input type="hidden" name="tmpl" id="tmpl" value="<?php echo JRequest::getVar("tmpl") ?>" />
	
		<?php
		}
		?>
	
		<input type="hidden" name="controller" value="termekek" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<div class="clr"></div>
	</div>
</div>