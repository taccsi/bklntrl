<?php defined( '_JEXEC' ) or die( '=;)' ); ?>
<?php 
$document = jfactory::getDocument();
$document->addStylesheet('templates/wh/css/nyomtatas.css'); 
$document->addScriptDeclaration("window.print()");
?>
<?php echo $this->nyomtatasTomeges; ?>
