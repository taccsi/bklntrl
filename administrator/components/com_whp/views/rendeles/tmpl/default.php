<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" >
<?php echo html_entity_decode($this->allGroups["admin"]); ?>
<input type="hidden" name="controller" value="rendeles" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_whp" />
</form>
