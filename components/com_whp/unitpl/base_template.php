<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class base_template extends whpPublic{
	function konyv_adatok(){
		ob_start();
		?>
		<span><?php echo $this->cell->oldalszam.' '.JText::_('OLDAL'); ?></span><br />
        <span><?php echo JText::_('KIADO').': '.$this->cell->kiado; ?></span><br />
        <span><?php echo JText::_('ISBN').' '.$this->cell->isbn; ?></span><br />
        
		
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;	
	}
	
	
}
?>
