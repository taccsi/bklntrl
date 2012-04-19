<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<input type="hidden" name="controller" value="impex" />
<input type="hidden" name="task" id="task" value="" /> 
<input type="hidden" name="option" id="option" value="com_wh" />
</form>
<?php
echo $this->jelentes;
?>
<div style=" margin:20px 0 0 10px" ><input type="button" onclick="if(confirm('<?php echo jtext::_("BIZTOS_VAGY_BENNE") ?>') ) {window.location='index.php?option=com_wh&controller=impex&task=export'}" value="<?php echo jtext::_("TEMREK_EXPORT") ?>" />
</div>
