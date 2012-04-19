<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("TERMEK") ?></h3> 

<div class="div_wh_topmenu">

<?php 
	if( JRequest::getVar("tmpl") == "component" ){
		$arr = array();
	}else{
		$arr = array(JText::_("M") );
	}	
	?>

	<?php new whMenu($arr); ?>
</div>
</div>
<div class="div_wh_clear"></div>
<div>
<form action="index.php" method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" class="noborderForm">

<?php
@$sess =& JSession::getInstance();
$aktiv_pane_id = $sess->get('aktiv_pane_id')+1;
$pane =& JPane::getInstance('tabs', array('startOffset'=>$aktiv_pane_id));
echo $pane->startPane( 'pane' );

echo $pane->startPanel( JText::_("ALTALANOS BEALLITASOK"), 'panel0' );
	echo html_entity_decode($this->allGroups["maindata"]);
echo $pane->endPanel();
echo $pane->startPanel( JText::_("TERMEK KATEG BEALLITAS"), 'panel1' );
	echo html_entity_decode($this->allGroups["kateg_beallitasok"]);
echo $pane->endPanel();

echo $pane->startPanel( JText::_("PARAMETEREK"), 'panel2' );
	echo html_entity_decode($this->allGroups["parameterek"]);
echo $pane->endPanel();

echo $pane->startPanel( JText::_("KAPCSOLODO TERMEKEK"), 'panel3' );
	echo html_entity_decode($this->allGroups["kapcsolodo_termekek"]);
echo $pane->endPanel();

echo $pane->startPanel( JText::_("KEPEK"), 'panel4' );
	echo html_entity_decode($this->allGroups["kepek"]);
echo $pane->endPanel();

echo $pane->endPanel();
echo $pane->endPane();
?>
<script language="javascript">
document.getElementById('aktiv_pane_id').value='<?php echo $aktiv_pane_id ?>';

</script>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="controller" value="termek" />
<?php 
if( JRequest::getVar("tmpl","") ){
?>
<input type="hidden" name="tmpl" id="tmpl" value="<?php echo JRequest::getVar("tmpl") ?>" />
<?php
}
?>

</form>
</div>
