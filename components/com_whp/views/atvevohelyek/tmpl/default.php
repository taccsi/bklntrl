<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div id="div_atvevohelylista">
<h1><?php echo $this->oldalcim ?></h1>
<form action="index.php" method="get" name="adminForm" id="adminForm" >
<?php
echo '<div class="pagenav">'.$this->pagination->getpageslinks().'</div>';
echo $this->atvevohelyek;
echo '<div class="pagenav">'.$this->pagination->getpageslinks().'</div>';
?>
<input type="hidden" name="option" value="com_whp" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="controller" id="controller" value="atvevohelyek" />
</form>
<div class="bottom">&nbsp;</div>
</div>

