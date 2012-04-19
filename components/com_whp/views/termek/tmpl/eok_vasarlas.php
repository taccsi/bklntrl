<?php
defined( '_JEXEC' ) or die( '=;)' );
$document = jfactory::getDocument();
$document->addStylesheet('templates/fapados/css/eok_popup.css'); 
$document->addScriptDeclaration("window.print()");


?>
<form method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" >
<?php
//echo $this->termek;
echo $this->eok_vasarlas_szoveg;
?>

  <input type="hidden" name="mennyiseg_kosarba" id="mennyiseg_kosarba" value="" /> 
  <input type="hidden" name="option" value="com_whp" />
  <input type="hidden" name="kosarba_id" id="kosarba_id" value="" />
  <input type="hidden" name="task" id="task" value="" />
  <input type="hidden" name="controller" id="controller" value="termek" />
</form>

 