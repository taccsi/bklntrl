<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("JOGTULOK") ?></h3> 
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
?>
</div>
<?php
	$arr = array();
	if(count( $this->items )){
		$k = 0;
		global $Itemid;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = &$this->items[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id );
			$link = JRoute::_( "index.php?option=com_wh&controller=jogtul&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$row->id}" );
			$o = "";
			$o->CHECKED = $checked;
			$o->jogtul = "<a href=\"{$link}\">{$row->nev}</a>";
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
<input type="hidden" name="controller" id="controller" value="jogtulok" />
<?php
if( $tmpl = JRequest::getVar('tmpl') ){
	?>
	<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" />
    <?php
}
?>
<div class="clr"></div>
</form>




</div>