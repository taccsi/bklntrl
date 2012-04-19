<?php
defined('_JEXEC') or die('=;)');
?>
<h1><?php echo Jtext::_('UTVONAL_LEIRASOK') ?></h1>
<div id="div_termeklista">
	<div id="ajaxContentTermekek">
		<?php
		$o = json_decode($this -> cont_);
		echo $o -> html;
		//echo '<div class="pagenav">'.$this->pagination->getpageslinks().'</div>';
	?>
	</div>
	<div class="bottom">
		&nbsp;
	</div>
</div>
