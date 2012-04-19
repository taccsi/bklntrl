<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" >
		<fieldset>
        <legend><?php echo JText::_("KATEGORIA") ?></legend>
		<?php echo html_entity_decode($this->allGroups["maindata"]); ?>
        </fieldset>
<input type="hidden" name="controller" value="kategoria" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_whp" />
</form>
