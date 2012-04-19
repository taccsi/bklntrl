<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<?php /*
<div class="div_wh_top_holder"> 
  <h3 class="h3_contentheading"><?php echo JText::_("") ?></h3>
  <div class="div_wh_topmenu">
    <?php new whMenu; ?>
  </div>
</div>
*/ ?>
<div class="div_wh_clear"></div>
<div class="div_szamlap">
  <form method="post" enctype="multipart/form-data" id="adminForm" >
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><input type="file" name="importfile_csv" /></td>
        <td><input onclick="$('kapcsolo').value='csvImport'" type="submit" value="<?php echo jtext::_("CSV_FELTOLTES") ?>" /></td>
      </tr>
<!--      <tr>
        <td><input type="file" name="javitofajl_csv" /></td>
        <td><input onclick="$('kapcsolo').value='csvJavito'" type="submit" value="<?php echo jtext::_("CSV_JAVITO_FELTOLTES") ?>" /></td>
      </tr>
-->      <tr>
        <td><input type="file" name="nagyker_ar_csv" /></td>
        <td><input onclick="$('kapcsolo').value='csvImportNagykerAr'" type="submit" value="<?php echo jtext::_("CSV_IMPORT_NAGYKER") ?>" /></td>
      </tr>
      
            
    </table>
    <input type="hidden" name="kapcsolo" id="kapcsolo" value="" />    
    <input type="hidden" name="controller" value="szamlap" />
    <input type="hidden" name="task" id="task" value="import" />
    <input type="hidden" name="option" value="com_wh" />
  </form>
</div>
