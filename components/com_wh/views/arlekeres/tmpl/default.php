<?php
defined( '_JEXEC' ) or die( '=;)' );
$tmpl = JRequest::getVar('tmpl'); 
$tmpl = ($tmpl) ? $tmpl : '';
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("ARLEKERES") ?></h3>
  <?php //new whMenu();  ?>
</div>
<div id="editcell">
<form action="index.php" method="get" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php 
echo $this->search;
$cond_kategoria_id = JREquest::getVar("cond_kategoria_id");
$stop_ = JREquest::getVar("stop_");
if( $cond_kategoria_id && !$stop_){
	//echo $cond_kategoria_id;
	$limitstart = $this->limitstart;
	$limit = $this->limit;
	$total = $this->total;
	echo JText::_("KESZ").": {$limitstart} / ".JText::_("OSSZES").": {$total}";
	echo $this->arak;
	if( $limitstart < $total ){
		?>
		<script>
			setInterval( autoSubmit, 4000 );
			function autoSubmit(){
				document.getElementById('adminForm').submit();
			}
		</script>
		<?php
		$limitstart+=$limit;
	}
	global $Itemid;
	?>
<?php 
}?>

<input type="hidden" name="Itemid" value="<?php JREquest::getVar( "Itemid" ); ?>" />
<input type="hidden" name="limitstart" id="limitstart" value="<?php echo $limitstart ?>" />
<input type="hidden" name="limit" value="<?php echo $limit ?>" />
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" name="controller" value="arlekeres" />
</form>
<div class="clr"></div>
</div>

