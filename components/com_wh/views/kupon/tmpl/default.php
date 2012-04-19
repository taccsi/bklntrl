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
<form method="post" enctype="multipart/form-data" id="adminForm" >
<?php echo html_entity_decode($this->allGroups["maindata"]); ?>
<input type="hidden" name="controller" value="kupon" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_wh" />
</form>
</div>
