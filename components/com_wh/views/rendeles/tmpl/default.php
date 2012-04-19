<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("RENDELES") ?></h3>

<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
</div>
<div class="div_wh_clear"></div>
<div class="div_rendeles">
<div id="editcell">
<form action="" method="post" enctype="multipart/form-data" id="adminForm" >
<?php echo html_entity_decode($this->allGroups["maindata"]); ?>
<?php echo html_entity_decode($this->allGroups["kapcsolok"]); ?>
<?php echo html_entity_decode($this->allGroups["rendeles"]); ?>
<?php echo html_entity_decode($this->allGroups["kiszallitas"]); ?>

<input type="hidden" name="controller" value="rendeles" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="torol_tetel_id" id="torol_tetel_id" value="" />
<input type="hidden" name="rendeles_id" id="rendeles_id" value="" />
</form>
</div></div>
