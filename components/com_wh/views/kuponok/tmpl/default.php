<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("") ?></h3> 
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
</div>
<div class="div_wh_clear"></div>
<div>
<form action="index.php" method="get" name="adminForm" id="adminForm" >
<div id="editcell">
<?php
	echo $this->search;
	$arr = array();
	if(count( $this->items )){
		$k = 0;
		global $Itemid;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = &$this->items[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id );
			$link = JRoute::_( "index.php?option=com_wh&controller=kupon&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$row->id}" );
			$o = "";
			$o->CHECKED = $checked;
			$o->azonosito_kod = "<a href=\"{$link}\">{$row->azonosito_kod}</a>";
			$o->ertek = ( $row->ertek_tipus == "%" ) ? ar::_( $row->ertek, "%" ) : ar::_( $row->ertek );
			$o->email = $row->email;									
			$o->tipus = $row->tipus;
			$o->AKTIV = $row->aktiv;
			
			$o->felhasznalas_datum = ($row->felhasznalas_datum != "0000-00-00 00:00:00") ? $row->felhasznalas_datum : "";			
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
<input type="hidden" name="controller" value="kuponok" />
<?php
if( $tmpl = JRequest::getVar('tmpl') ){
	?>
	<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" />
    <?php
}
?>
</div><div class="clr"></div>
</form>




</div>