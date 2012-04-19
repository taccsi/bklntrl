<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form action="index.php" method="get" name="adminForm" id="adminForm">
<?php //echo $this->pagination->getPagesLinks(); ?> 
<div id="div_kat_tree"><?php echo $this->tree ?></div>
<?php //echo $this->pagination->getPagesLinks(); ?> 
<input type="hidden" name="sorrendId" id="sorrendId" value="" />
<input type="hidden" name="irany" id="irany" value="" />
<input type="hidden" name="option" value="com_whp" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="kategoriak" />
</form> 
