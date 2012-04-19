<?php
defined( '_JEXEC' ) or die( '=;)' );
global $Itemid;
$addMezoLink = "index.php?option=com_wh&controller=msablon_mezo&task=edit&fromlist=&Itemid=9&cid[]=sablon_id={$this->id}&tmpl=component";
$selectMezoLink = "index.php?option=com_wh&controller=msablon_mezok&msablon_id={$this->id}&tmpl=component";
?>
<input type="hidden" id="bsznev" value="<?php echo str_replace('"','',$this->beszallito->nev); ?>" />
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("MSABLON ADATLAP") ?></h3>
</div>
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
<div class="div_wh_clear"></div>
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
<input type="hidden" name="controller" value="msablon" />
<input type="hidden" id="Itemid" name="Itemid" value="<?php echo $Itemid; ?>" />
</form></div>

