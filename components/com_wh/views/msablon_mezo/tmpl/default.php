<?php
defined( '_JEXEC' ) or die( '=;)' );
$tmpl = JRequest::getVar('tmpl'); 
$tmpl = ($tmpl) ? $tmpl : '';

?>

<input type="hidden" id="cid" value="<?php echo (int)$_GET['cid'][0]; ?>" />
<input type="hidden" id="bsznev" value="<?php echo str_replace('"','',$this->beszallito->nev); ?>" />
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("MSABLON_MEZO ADATLAP") ?></h3>

<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
</div>
<div class="div_wh_clear"></div>
<div id="editcell"><form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="table_msablon_mezo" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <fieldset>
    <?php 
    echo html_entity_decode($this->allGroups["maindata"]) ;
    //echo $this->szemelyesadatok ;
    ?> 
    </fieldset>
    </td>
  </tr>
</table>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" name="controller" value="msablon_mezo" />
<input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" />
</form><div class="clr"></div></div>

