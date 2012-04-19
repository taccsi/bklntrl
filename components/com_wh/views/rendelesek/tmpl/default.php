<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("RENDELESEK") ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>  
	</div>    
</div>

<div class="div_wh_clear"></div>
<div id="editcell">
<form action="index.php" method="get" name="adminForm" id="adminForm" class="adminForm" >

<?php echo $this->search; ?>

<?php
	//echo "<div style=\"width:400px; margin-left:auto; margin-right:auto; text-align:center\" >".$this->pagination->getPagesLinks()."</div>";
	echo "{$this->items}";
?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="rendelesek" />
<?php if( JRequest::getVar('tmpl') ){
	?><input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" /><?php
}
?>

</form>
</div>