<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("UGYFELEK") ?></h3>    
	<?php 
	if( JRequest::getVar("tmpl") == "component" ){
		$arr = array(JText::_("UJ"), JText::_("TOROL") );
	}else{
		$arr = array(JText::_("KIVALASZT") );
	}	
	?>
	<div class="div_wh_topmenu">
		<?php new whMenu($arr); ?>
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
			$tmpl = JRequest::getVar('tmpl');
			$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
			$link = JRoute::_( 'index.php?option=com_wh&controller=beszallito&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			$o = "";
			$o->CHECKED = $checked;
			$o->BESZALLITO = "<a href=\"{$link}\">{$row->nev}</a>";
			$arr[] = $o; 
			$k = 1 - $k;
		}
	}
$lista = new listazo($arr, "adminlist", $this->pagination->getPagesLinks(), $this->pagination->getPagesLinks() );
echo $lista->getLista();
?>
</div>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="beszallitok" />
<?php 
if( JRequest::getVar("tmpl") ){
?>
<input type="hidden" name="tmpl" id="tmpl" value="<?php echo JRequest::getVar("tmpl") ?>" />
<?php
}
?>

</form>
</div>