<?php
defined( '_JEXEC' ) or die( '=;)' );

?>
<div class="div_kosar">
<h1 class="componentheading"><?php echo JText::_("KOSAR") ?></h1>
<div class="body">
<?php @$sess = JSession::getInstance();
	if(count( $sess->get("kosar") ) ){
	?>
<form method="post" name="adminForm" id="adminForm" action="" >
  <?php echo $this->kosarlista;?>
  <input NAME="option" id="option"  TYPE="hidden" VALUE="com_whp" />
  <input NAME="controller" id="controller" TYPE="hidden" VALUE="kosar" />
  <input name="task" type="hidden" id="task" value="" />
  <input name="Itemid" type="hidden" id="Itemid" value="<?php echo $this->Itemid; ?>" />
  <input name="tetel_id" type="hidden" id="tetel_id" value="" />
	<?php echo $this->kosarGombok ?>  
</form>
<?php }else{ ?>
<div style="margin-left:11px" class="div_ures"><?php echo JText::_("URES_A_KOSAR"); ?></div>
<?php
}?>
</div>
<div class="bottom">&nbsp;</div>
</div>
