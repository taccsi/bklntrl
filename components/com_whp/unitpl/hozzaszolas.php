<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

//ini_set("display_errors", 1);
error_reporting(E_ALL);

class hozzaszolas {
	var $th_szel = 102;
	var $th_mag = 128;
		
	function __construct(){
		//parent::__construct();
	}

	function getTpl()
	{
		ob_start();
	//print_r($this->cell);
		
		
		?>
			<div class="div_bubi <?php echo $class; ?>">
            	<div class="top"><h3>{felhasznalonev} :: <?php echo $this->cell->NEV; ?></h3></div>
                <div class="middle">
                	<?php echo $this->cell->SZOVEG; ?>
                </div>
                <div class="bottom">
                	<div class="bottom_wr">
                    	<div class="bottom_date"><?php echo $this->cell->DATUM; ?></div>
                        <div class="bottom_torol"><?php
						//print_r($this->cell); 
						 echo $this->cell->TORLES; ?></div>
                    </div>
                </div>
                
			</div>
        
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}	
	
	function felhasznalonev(){
		ob_start();
		?>
		
        <a href="mailto:<?php echo $this->cell->USER_EMAIL ?>" title="<?php echo $this->cell->USER_EMAIL ?>" alt="<?php echo $this->cell->USER_EMAIL ?>"><?php echo $this->cell->USER_NEV ?></a>
		
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
		
	}
	
	
	/*function _nev(){
		ob_start();
		//print_r($this->cell->pics);
		if(strlen($this->cell->nev) < 35) {
		?>
		<h2><a href="<?php echo $this->getListLink() ?>">
		<?php } else if(strlen($this->cell->nev) > 55) { ?>
        <h2><a class="termeknev_verylong" href="<?php echo $this->getListLink() ?>">
        <?php } else { ?>
        <h2><a class="termeknev_long" href="<?php echo $this->getListLink() ?>">
        <?php }
		echo $this->cell->nev ?></a></h2>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;	
	}*/
	
}
?>
