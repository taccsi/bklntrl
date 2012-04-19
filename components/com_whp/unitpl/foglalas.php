<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class foglalas extends base_template{
	function __construct(){
	}

	function getTpl()
	{
		@$class = $this->kampanyok[$this->cell->kampany->grafika]['bontas_class'];
		ob_start();
		//print_r($this->cell); die();
		?>
	      <h1>{nev}</h1>
	  	  <div class="route-head"><h3 class="route"><?php echo Jtext::_('ROVID_ISMERTETO') ?></h3></div>
	      <div class="article route"><div class="listaKep">{listaKep}</div><div class="egyebKepek">{egyebkepek}</div> <div class="leiras_rovid">{leiras_rovid}</div></div>
	  	  <div class="route-head"><h3 class="route"><?php echo Jtext::_('FOGLALAS') ?></h3></div>
	      <div class="article route">
	      	<div class="tura_ar"><?php echo Jtext::_('A_TURA_ARA') ?> {arHTML_me}</div>
	      	<div class="resztvevok_szama"><?php echo Jtext::_('HANY_FO_SZAMARA_FOGLAL') ?>{kosar} </div>
	      	<div class="idopont_valaszto"><?php echo Jtext::_('VALASSZA_KI_A_TURA_KIVANT_IDOPONTJAT') ?>{idopont_select}</div></div>
          
          <div class="route-head"><h3 class="route"><?php echo Jtext::_('KIEGESZITO_SZOLGALTATASOK') ?></h3></div>
	      <div class="article route">{related_products}</div>         
       	
       	<div id="ajaxContentFoglalgomb"></div>
                   
	   
	     
		<?php 
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
	
	function kosar(){
		$ret = ($this->cell->kosar) ? "<div class=\"kosar\">". $this->cell->kosar . "</div>" : "";
		return $ret;
	}

	function kosarMatrix(){
		$ret = ($this->cell->kosarMatrix) ? "<div class=\"kosarmatrix_container\">". $this->cell->kosarMatrix . "</div>" : "";
		return $ret;
	}

	function me(){
		return ($this->cell->me) ? jtext::_("MENNYISEGI_EGYEG").": {$this->cell->me}" : "";
	}

	function leiras() {
		if($this->cell->leiras) {
			$ret = $this->cell->leiras;
		} else {
			$ret = "";
		}
		return $ret;
	}

	function egyebkepek(){
		ob_start();
		
		$i = 0;
		foreach ($this->cell->egyebkepek as $kep){
			$class = (++$i % 4) ? "" : "utolso"; 
			echo '<div class="div_kiskep '.$class.'">'.$kep.'</div>';
		}
		
		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	
	}
		
	function gyarto(){
		ob_start();
		
		if ($this->cell->gyarto_url){
		?>
		
          <a  class="gyarto linkbutton" href="<?php echo $this->cell->gyarto_url ?>" target="_blank" title="<?php echo $this->cell->gyarto ?>" >Hol vásárolhatom meg?</a>
           
		<?php
		} else {$ret = '';}
		
		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function uzlet(){
		ob_start();
		
		if ($this->cell->uzlet_url){
		?>
		
          <a class="uzlet linkbutton" href="http://<?php echo $this->cell->uzlet_url ?>" target="_blank" title="<?php echo $this->cell->uzlet_nev ?>" >Most vásárolom meg!</a>
           
		<?php
		} else {$ret = '';}
		
		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	
	
	function uzlet__(){
		ob_start();
		
		
		?>
		<table border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td class="key"><?php echo Jtext::_('UZLET_NEVE') ?></td>
            <td><?php echo $this->cell->uzlet->nev ?></td>
          </tr>
          <tr>
            <td class="key"><?php echo Jtext::_('UZLET_CIM') ?></td>
            <td><?php echo $this->cell->uzlet->cim ?></td>
          </tr>
          <?php if(@$this->cell->uzlet->web) {?>
          <tr>
            <td class="key"><?php echo Jtext::_('UZLET_WEB') ?></td>
            <td><a href="<?php echo $this->cell->uzlet->web ?>" target="_blank"><?php echo $this->cell->uzlet->web ?></a></td>
          </tr>
         <?php } ?>
        </table>
        
        
        
		<?php
		
		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	
	
}
?>
