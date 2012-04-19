<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("") ?></h3>
<div class="div_wh_topmenu">
	<?php //new whMenu; ?>  
</div>
</div>
<div>
<form method="post" enctype="multipart/form-data" id="adminForm" >
<input type="file" name="csvfile" /> <input type="submit" value="feltölt" />
<input type="submit" onclick="$('task').value='toroldb'" value="toroldb"  />
<input type="hidden" name="controller" value="import" />
<input type="file" name="csvfile2" /> <input type="submit" onclick="$('task').value='feldolgoz_csv2'" value="feldolgoz_csv2" />
<br /><br /><br />
<input type="file" name="csvfile3" /> <input type="submit" onclick="$('task').value='feldolgoz_csv_csak_arak'" value="feldolgoz_csv_csak_arak" />

<input type="hidden" name="task" id="task" value="feldolgoz_csv" />
<input type="hidden" name="option" value="com_wh" />
</form>
</div>
