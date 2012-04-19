<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("TOMEGES_FUNKCIOK") ?></h3> 
</div>
<div class="div_wh_topmenu">
	<?php //new whMenu( array(jtext::_("UJ"), jtext::_("TORLES")  ) ); ?>  
</div>
<div class="div_wh_clear"></div>
<div>
<form action="index.php" method="get" name="adminForm" id="adminForm">
<table class="table_tomeges" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="td_tomeges_ev"><?php echo $this->tomegesInputok[0]->EV ?></td>
    <td class="td_tomeges_honap"><?php echo $this->tomegesInputok[1]->HONAP ?></td>
    <td class="td_tomeges_mail">
    <input type="button" onclick="if(confirm('<?php echo jtext::_("BIZTOS_VAGY_BENNE") ?>')){$('task').value='tomegesEmailKuldes'; $('adminForm').submit()}" value="<?php echo jtext::_("TOMEGES_EMAIL_KUKLDES") ?>"  />
    </td>
    <td>
    <input type="button" onclick="jutalekNyomtatasTomeges()" value="<?php echo jtext::_("NYOMTATAS") ?>"  />
    </td>
    <td>
    <input type="button" onclick="$('task').value='csvExport'; $('adminForm').submit()" value="<?php echo jtext::_("EXPORT") ?>"  />
    </td>
  </tr>
</table>

<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="jogtul" />
<input type="hidden" name="layout" value="tomegesfunkciok" />
<input type="hidden" name="tomeges" value="1" />

</form> 
</div>