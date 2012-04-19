<?php

defined( '_JEXEC' ) or die( '=;)' );

?>

<div class="div_rendeles">

<h1 class="componentheading"><?php echo jtext::_("RENDELES"); ?></h1>

<div class="body"> 

<form method="get" name="adminForm" id="adminForm" action="" >

<div id="ajaxContentKosar"></div>



<h2 class="h2_form"><?php echo JTEXT::_('KUPON') ?></h2>
<div class="div_kupon"><?php echo html_entity_decode($this->allFormGroups["kupon"]); ?></div>

<?php //echo  html_entity_decode($this->allFormGroups["data1"]); //fizetesi mod ?>

<h2 class="h2_form"><?php echo JTEXT::_('FIZETESI MOD').'</h2>'; ?></h2>
<div class="div_atvetel"><?php echo html_entity_decode($this->allFormGroups["fizetesi_mod"]); ?></div>


<h2 class="h2_form"><?php echo jtext::_("SZAMLAZASI_ADATOK") ?></h2>
<div class="div_szamlazasiadatok"><?php echo  html_entity_decode($this->allFormGroups["data3"]); ?></div>  

<h2 class="h2_form"><?php echo jtext::_("EGYEB_INFORMACIOK") ?></h2>
<div class="div_egyeb_info"><?php echo  html_entity_decode($this->allFormGroups["data4"]); ?></div>

<?php // echo  html_entity_decode($this->allFormGroups["submit"]); ?>

<input name="option" id="option"  TYPE="hidden" VALUE="com_whp" />

<input name="controller" id="controller" TYPE="hidden" VALUE="rendeles" />

<input name="task" type="hidden" id="task" value="save" />



</form>

</div>

<?php /*<div class="bottom"><?php echo jtext::_("CSILLAG_JELOLT_MEZOK_KOZETELEZOK") ?></div> */ ?>

</div>