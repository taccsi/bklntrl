<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("BEALLITASOK") ?></h3>
  <div class="div_wh_topmenu">
	<?php new whMenu; ?>  
  </div>
</div>

<div class="div_wh_clear"></div>
<div id="editcell">
<div class="div_beallitas">
<form method="post" enctype="multipart/form-data" id="adminForm" class="noborderForm">
<?php 
@$sess =& JSession::getInstance();
$aktiv_pane_id = $sess->get('aktiv_pane_id')+1;

$panelek = array(
			
			//array("title"=>"FELH_KAT", "id"=>"panel1"),
			array("title"=>"WEBSHOP_KAT", "id"=>"panel2"), 
			//array("title"=>"KOLTSEGEK_KATEGORIANKENT", "id"=>"panel3"),
			   
			   );



//echo $aktiv_pane_id.' fg';
if ($aktiv_pane_id>count($panelek)) {$aktiv_pane_id = 1;}
$pane =& JPane::getInstance('tabs' );
echo $pane->startPane( 'pane' );

foreach ($panelek as $panel){
echo $pane->startPanel( JText::_($panel["title"]), $panel["id"] );
	echo html_entity_decode($this->allGroups[strtolower($panel["title"])]);
	
echo $pane->endPanel();
	
	
	
	
	
}
/*echo $pane->startPanel( JText::_("ALTALANOS BEALLITASOK"), 'panel1' );
	echo html_entity_decode($this->allGroups["maindata"]);
echo $pane->endPanel();*/


echo $pane->endPane();
echo html_entity_decode($this->allGroups["maindata"]);

?>
<script language="javascript">
document.getElementById('aktiv_pane_id').value='<?php echo $aktiv_pane_id ?>';

</script>

<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_wh" />
</form>
</div>
</div>