<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_(jrequest::getvar("task", "TOP10_TERMEKEK") ) ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>
	</div>    
</div>
<div id="editcell">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="adminForm" >
<?php echo $this->cont_; ?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="<?php echo jrequest::getvar("task", "TOP10_TERMEKEK") ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="kimutatas" />
</form>
</div>

