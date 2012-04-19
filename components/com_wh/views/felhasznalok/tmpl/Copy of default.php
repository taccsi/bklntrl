<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("") ?></h3> 
</div>
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
<div class="div_wh_clear"></div>
<div>
<form action="../../Copy of szuzek/tmpl/index.php" method="get" name="adminForm" id="adminForm">
<div id="div_kat_tree"><?php echo $this->tree ?></div>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="" />
</form> 
</div>