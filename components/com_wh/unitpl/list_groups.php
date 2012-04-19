<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);

class list_groups extends base_template{
	var $th_szel = 200;
	var $th_mag = 90;
		
	function __construct(){
		parent::__construct();
	}

	function getTpl()
	{
		ob_start();
		//print_r($this->cell->pics);
		//print_r($this->cell);
		if (priceHelper::isDiscounted($this->cell->id)) {$disc=' discounted';} else {$disc='';}
		?>
		<div class="div_listitem"><div class="<?php echo $disc; ?>">
        	<h2>{name}</h2>
            <table class="table_listing" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>{listImage}</td>
              </tr>
            </table></div>
        </div>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}	
	
	function name(){
		ob_start();
		//print_r($this->cell->pics);
		if(strlen($this->cell->name) < 35) {
		?>
		<h2><a href="<?php echo $this->getListLink() ?>">
		<?php } else if(strlen($this->cell->name) > 55) { ?>
        <h2><a class="termeknev_verylong" href="<?php echo $this->getListLink() ?>">
        <?php } else { ?>
        <h2><a class="termeknev_long" href="<?php echo $this->getListLink() ?>">
        <?php }
		echo $this->cell->name ?></a></h2>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;	
	}

	function listImage()
	{
		global $Itemid;
		//print_r($this->cell->pics);
		//exit;
		@$forras_kep = "{$this->dir_forras}gal_{$this->cell->pics[0]->id}.jpg";
		@$cel_kep = "{$this->dir_cel}galeria_{$this->cell->pics[0]->id}_{$this->th_szel}_{$this->th_mag}_1.jpg";
		$link=$this->getListLink();
		//echo $forras_kep." fkép<br />";
		//echo $cel_kep." ckép<br />";
		//print_r($this->cell->kepek[1]->file_name);exit;
		return $this->image($forras_kep, $cel_kep, $this->th_szel, $this->th_mag, "resize", $link, "rel=\"\"" ,
		"", $this->cell->name);
	}
}
?>
