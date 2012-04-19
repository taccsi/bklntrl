<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("tetelek") ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>  
	</div>    
</div>

<div class="div_wh_clear"></div>
<div id="editcell">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="adminForm" >

<?php
	echo $this->search;
	$arr = array();
	if(count( $this->items )){
		$k = 0;
		global $Itemid;
		$u_id=array();
		$u_name=array();
		$ws_id=array();
		$ws_name=array();
		
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			//array_search();
			$row = &$this->items[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id )."<input type=\"hidden\" name=\"cidT[]\" value=\"{$row->id}\" />";
			$tmpl = JRequest::getVar('tmpl');
			$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
			$link = JRoute::_( 'index.php?option=com_wh&controller=tetel&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			$o = "";
			$o->CHECKED = $checked;
			
			$rendelLink= "index.php?option=com_wh&controller=rendeles&Itemid=16&fromlist=1&task=edit&cid[]={$row->rendeles_id}";
			
			$o->RENDELES = "<a href=\"{$rendelLink}\">{$row->rendeles_id} <br />{$row->datum}</a>";
			$o->TERMEK = "{$row->nev}<br />{$row->cikkszam}";
			$o->CSOMAGSZAM = "<a href=\"{$link}\" >{$row->csomagszam}</a>";			
			$o->BESZALLITO_AR = $row->beszallitoAr;
			$o->BESZALLITO= $row->beszallito;
			$o->MEGRENDELEVE_DATUM= $row->megrendeleve_datum;
			$o->BEERKEZETT_DATUM= $row->beerkezett_datum;			
			$o->BESZALLITONAK_FIZETVE_DATUM= $row->beszallitonak_fizetve_datum;
			$o->ALLAPOT = $row->allapot;
			//$o->RENDEL_DATUM = date($row->datum);
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
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="tetelek" />
<?php if( JRequest::getVar('tmpl') ){
?>
<input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" />
<?php
}
?>

</form>
</div>