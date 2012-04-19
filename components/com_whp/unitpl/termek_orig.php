<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);
class termek extends base_template{
	function __construct(){
	}

	function getTpl()
	{
		@$class = $this->kampanyok[$this->cell->kampany->grafika]['bontas_class'];
		ob_start();
		//print_r($this->cell);
		?>
        <div class="utvonal">{utvonal}</div>
        <div class="outbox <?php echo $class; ?>">
           
            <div class="bontas_top">
            	<div class="bontas_top_left">
                	<div class="listaKep">{listaKep}</div>
                    
                </div>
                <div class="bontas_top_right">
                	 <h1>{nev}</h1>
                	<div class="arHTML">{arHTML_me}</div>
                    {termvarSelect}{kalkulator}
                    <div class="leiras_rovid">{leiras_rovid}</div>
                    
                    <div class="letoltheto_file">{letoltheto_file}</div>
                    {kosar}
                </div>
                
            </div>
            <div class="bontas_middle">
            	<div class="egyebKepek">{egyebkepek}</div>
            	
            </div><a id="horgony_kapcs" href="#related"><?php echo Jtext::_('KAPCSOLODO_TERMEKEK') ?></a>
            <div id="tabs">{tabs}</div>
        </div>
        <div class="div_share outbox">
        	<table>
            	<tr>
                	<td class="left"><div class="shareLinks">{shareLinks}</div></td>
                    <td class="right"><div class="kategoriaLink">{kategoriaLink}</div><div class="recommender">{recommender}</div></td>
                </tr>
            </table>   
        </div><a name="related"></a>
        <div class="unidiv_list">{related_products}</div>
		<div class="unidiv_list">{additional_products}</div>
        
        
       
        
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
			$ret = "<h3>".Jtext::_("LEIRAS")."</h3>".$this->cell->leiras;
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
