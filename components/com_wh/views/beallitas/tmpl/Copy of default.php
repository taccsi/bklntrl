<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("BEALLITASOK") ?></h3>
</div>
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
<div class="div_wh_clear"></div>
<div>
<form method="post" enctype="multipart/form-data" id="adminForm" >
<?php echo html_entity_decode($this->allGroups["maindata"]);?>
<?php 
$pane =& JPane::getInstance('tabs', array('startOffset'=>1));
echo $pane->startPane( 'pane' );

echo $pane->startPanel( JText::_("ALTALANOS BEALLITASOK"), 'panel1' );
	echo html_entity_decode($this->allGroups["maindata"]);
echo $pane->endPanel();

echo $pane->startPanel( JText::_("FELH_KAT"), 'panel2' );
	echo html_entity_decode($this->allGroups["felh_kat"]);
echo $pane->endPanel();

echo $pane->startPanel( JText::_("WEBSHOP KAT"), 'panel3' );
	echo html_entity_decode($this->allGroups["webshop_kat"]);
echo $pane->endPanel();

echo $pane->endPanel();
echo $pane->endPane();

?>

<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_wh" />
</form>
</div>