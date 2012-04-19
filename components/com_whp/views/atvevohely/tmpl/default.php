<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" >
<?php
echo $this->atvevohely;

?>

  <input type="hidden" name="mennyiseg_kosarba" id="mennyiseg_kosarba" value="" /> 
  <input type="hidden" name="option" value="com_whp" />
  <input type="hidden" name="kosarba_id" id="kosarba_id" value="" />
  <input type="hidden" name="task" id="task" value="" />
  <input type="hidden" name="controller" id="controller" value="atvevohely" />
</form>

 