<?php
defined( '_JEXEC' ) or die( '=;)' );
$pane =& JPane::getInstance('tabs' /*, array('startOffset'=>1)*/);
echo $pane->startPane( 'pane' );
echo $pane->startPanel( JText::_("termek"), 'panel1' );
?>
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
      <form method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" >
      <fieldset>
        <legend><?php echo JText::_("termek") ?></legend>
		<?php echo html_entity_decode($this->allGroups["maindata"]);?>
        </fieldset>
          <input type="hidden" name="option" value="com_whp" />
          <input type="hidden" name="task" id="task" value="" />
          <input type="hidden" name="controller" value="termek" />
		<fieldset>
		  <legend><?php echo JText::_("TERMEKVARIACIOK") ?></legend>
      		<?php echo html_entity_decode($this->allGroups["termekvariaciok"]);?>
		</fieldset>
		</form>
        
        </td>
      <td>
        <fieldset>
		  <legend><?php echo JText::_("KEPEK") ?></legend>
		  <?php echo html_entity_decode($this->allGroups["kepek"]);?>
		</fieldset>
      </td>
    </tr>
  </table>
  <?php
echo $pane->endPanel();
echo $pane->endPane();
?>

