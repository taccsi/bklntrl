<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("KATEGORIAK") ?></h3>
</div>
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
<div class="div_wh_clear"></div>
<div>
<form action="index.php" method="get" name="adminForm" id="adminForm">
<?php //echo $this->pagination->getPagesLinks(); ?> 
<hr/>
<div id="div_kat_tree"><?php echo $this->tree ?></div>
<hr/>
<?php //echo $this->pagination->getPagesLinks(); ?> 
<?php //echo $this->pagination->getPagesLinks(); ?> 
<input type="hidden" name="sorrendId" id="sorrendId" value="" />
<input type="hidden" name="irany" id="irany" value="" />
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="kategoriak" />
</form> 
</div>