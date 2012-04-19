<?php
defined( '_JEXEC' ) or die( '=;)' );
ini_set("display_errors", 0);
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("FELHASZNALO") ?></h3>
	<div class="div_wh_topmenu">
	 <?php //new whMenu; ?>  
	</div>    
</div>
<div class="div_wh_clear"></div>
<div id="editcell">
<form action="index.php" method="get" name="adminForm" id="adminForm" >
<?php //echo html_entity_decode($this->allGroups["maindata"]); 
//print_r( $this->f);
//die;
echo "<h2>".jtext::_("ALTALANOS_USER_INFO")."</h2>";
foreach($this->f->user as $vname => $v){
	if(!in_array($vname, array("password", "usertype", "block", "sendEmail", "gid", "registerDate", "activation", "params") ) ){
		echo "<span class=\"user_label\">".jtext::_($vname).":</span> {$v}<br />";
	}
}
parse_str( $this->f->felhasznalo->szamlazasi_cim );
echo "<br /><br />";
echo "<h2>".jtext::_("SZAMLAZASI_CIM")."</h2>";
echo "<span class=\"user_label\">".jtext::_("SZAMLAZASI_NEV").":</span> {$SZAMLAZASI_NEV}<br />";
echo "<span class=\"user_label\">".jtext::_("IRANYITOSZAM").":</span> {$IRANYITOSZAM}<br />";
echo "<span class=\"user_label\">".jtext::_("VAROS").":</span> {$VAROS}<br />";
echo "<span class=\"user_label\">".jtext::_("UTCA_HAZSZAM").":</span> {$UTCA}<br />";
echo "<br /><br />";
parse_str( $this->f->felhasznalo->szallitasi_cim );
echo "<h2>".jtext::_("SZALLITASI_CIM")."</h2>";
echo "<span class=\"user_label\">".jtext::_("SZALLITASI_NEV").":</span> {$SZALLITASI_NEV}<br />";
echo "<span class=\"user_label\">".jtext::_("IRANYITOSZAM").":</span> {$IRANYITOSZAM}<br />";
echo "<span class=\"user_label\">".jtext::_("VAROS").":</span> {$VAROS}<br />";
echo "<span class=\"user_label\">".jtext::_("UTCA_HAZSZAM").":</span> {$UTCA}<br />";
echo "<br /><br />";
echo "<h2>".jtext::_("VASARLASOK")."</h2>";
echo $this->vasarlasok;
?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="felhasznalo" />
</form>
</div>
