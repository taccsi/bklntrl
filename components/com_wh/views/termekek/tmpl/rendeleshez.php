<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("TERMEKEK") ?></h3>    
	<?php 
		$arr = array(JText::_("UJ"), JText::_("TOROL"), JText::_("KIVALASZT"), JText::_("MENT") );
	?>
<div class="div_wh_topmenu">
	<?php new whMenu($arr); ?>
</div>
</div>
<div class="div_wh_clear"></div>
<div>
<form action="index.php" method="post" name="adminForm" id="adminForm" >
<?php
	echo $this->search;
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
			$link = JRoute::_( 'index.php?option=com_wh&controller=termek&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			$o = "";
			$o->CHECKED = $checked;
			if($this->aktTermek == $row->id){
				$o->TERMEK = "<a href=\"{$link}\" class=\"aktTermek\">{$row->nev}</a>";
			}
			else {$o->TERMEK = "<a href=\"{$link}\">{$row->nev}</a>";}
			$o->BESZALLITO_ARAZAS = $row->beszallitoInput;
			$o->KISKERAR = "<span class=\"lista_ar\">{$row->kiskerAr}</span>";
			$o->KONKURENCIA = $row->konkurenciaArak;
			if($row->haszon){
				$o->HASZON = $row->haszon;
			}
			$o->KATEGORIA = $row->kategorianev;	
			$o->KEZDOKEP = $row->elsokep;
			$arr[] = $o; 
			$k = 1 - $k;
		}
		$lista = new listazo($arr, "adminlist", $this->pagination->getPagesLinks(), $this->pagination->getPagesLinks() );
		echo $lista->getLista();
	}else{
		echo jtext::_("NINCS TALALAT");
	}
?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden_" name="task" id="task" value="" />
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
</div>
