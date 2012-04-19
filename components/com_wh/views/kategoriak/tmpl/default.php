<?php
defined( '_JEXEC' ) or die( '=;)' );
$document = jfactory::getdocument();
?>

<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("KATEGORIAK") ?></h3>
  <div class="div_wh_topmenu">
    <?php new whMenu; ?>
  </div>
</div>
<div class="div_wh_clear"></div>
<div id="editcell" >
<?php
$document->addScriptDeclaration('$j(document).ready(function() { getKategoriak();});');
?>
<form action="index.php" method="get" name="adminForm" id="adminForm">
  <?php //echo $this->pagination->getPagesLinks(); ?>
  <?php echo $this->search ?>
  <div id="ajaxContentKategoriak"></div>
  <?php //echo $this->pagination->getPagesLinks(); ?>
  <?php //echo $this->pagination->getPagesLinks(); ?>
  <input type="hidden" name="sorrendId" id="sorrendId" value="" />
  <input type="hidden" name="irany" id="irany" value="" />
  <input type="hidden" name="option" value="com_wh" />
  <input type="hidden" name="task" id="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="controller" value="kategoriak" />
</form>
<div class="clr"></div>
</div>
