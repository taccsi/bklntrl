<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("RENDELES") ?></h3>
</div>
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
<div class="div_wh_clear"></div>
<div>
<form method="post" enctype="multipart/form-data" id="adminForm" >
<?php //echo html_entity_decode($this->allGroups["maindata"]); ?>
<?php echo $this-> rendelAdat; ?>
<input type="hidden" name="controller" value="move" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_wh" />
</form>
</div>
