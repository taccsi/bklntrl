<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("FELHASZNALO") ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>  
	</div>    
</div>
<div class="div_wh_clear"></div>
<div id="editcell" class="fcsoport">
<form action="index.php" method="get" name="adminForm" id="adminForm" >
<?php echo html_entity_decode($this->allGroups["maindata"]); ?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="fcsoport" />
</form>
</div>