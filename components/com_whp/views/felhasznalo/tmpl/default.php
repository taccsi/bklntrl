<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div id="div_regisztracio">
<?php
$user = JFactory::getUser();
if( !$user->id ) {
?>
    <div class="div_belepes">
    <h1 class="componentheading"><?php echo jtext::_("BELEPES_REGI_FELHASZNALO") ?></h1>
    <form method="post" id="loginForm" class="adminForm" name="adminForm">
    
    <?php
        echo html_entity_decode($this->allGroups["login"]);
    ?>
    <input type="hidden" name="option" value="com_whp" />
    <input type="hidden" name="task" id="task2" value="login" />
    <input type="hidden" name="controller" value="felhasznalo" />
    </form>
    <div class="bottom">&nbsp;</div>
    </div>
<?php
} 
( $user->id ) ? $label = JText::_("ADATAIM MODOSITASA") :  $label = JText::_("REGISZTRACIO_UJ_FELHASZNALO") ;
?>
<div class="div_regisztracio">
<h1 class="componentheading"><?php echo jtext::_($label) ?></h1>
<form method="post" id="regForm" class="adminForm adminForm_regAdatok" name="adminForm">

<?php
	echo html_entity_decode($this->allGroups["reg_adatok"]);
	//echo html_entity_decode($this->allGroups["kieg_adatok"]);
?>
<input type="hidden" name="option" value="com_whp" /> 
<input type="hidden" name="task" id="task" value="save" />
<input type="hidden" name="controller" value="felhasznalo" />
</form>
<br />
 <?php echo Jtext::_('REGISZTRACIOS_HOZZAJARULAS')?><br />

<div class="bottom">&nbsp;</div>
</div>

</div>


