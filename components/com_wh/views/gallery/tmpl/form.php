<?php
defined( '_JEXEC' ) or die( '=;)' );
$tmpl = JRequest::getVar('tmpl');
?>
<div class="div_wh_top_holder">	
	<h3 class="h3_contentheading"><?php echo JText::_("ADATOK"); ?></h3>
	<?php if ($tmpl == 'component')	{
			$document =& JFactory::getDocument();
		$document->addStyleSheet('templates/wh/css/style_floatbox.css');
			new whMenu();
		}else{
			new whMenu();
		} ?>
</div>
<div id="editcell"><form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="table_csoportok" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <fieldset>
    <?php 
    echo  html_entity_decode($this->allGroups["maindata"]) ;
    //echo $this->szemelyesadatok ;
    ?> 
    </fieldset>
    </td>
  </tr>
</table>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" name="controller" value="webshop" />
</form></div>
