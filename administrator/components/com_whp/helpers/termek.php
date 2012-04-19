<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmltermek extends kep{
	var $images = 3;
	var $uploaded = "media/termekek";
	var $table = "#__whp_termek";	
	var $kepPrefix = "";
	var $nopic = "components/com_whp/assets/images/nopic.jpg";

	

	function getErtekeles( $node ){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		//$this->document->addScriptDeclaration("window.addEvent(\"domready\", function(){ ;});");		

		foreach($this->ertekelesArr as $k=>$v){
			echo "<div class=\"clr\" >".jtext::_($k)."</div>";
			echo "<div class=\"clr\" >".$this->getStarOptions( $k )."</div>";
		}
		?>
		<script>initStars( )</script>				
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function getAr( $node ){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$afa_id = $this->getAktVal("afa_id");
		echo $this->getNettoBruttoInput("ar", "bruttoAr", $value, $afa_id, "ar_{$name}", "" );
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
   }

	function getTermekVariaciok( $node ){
		ob_start();
		$document = jfactory::getDocument();
		$id = $this->getAktVal("id");
		$document->addScriptDeclaration("window.addEvent(\"domready\", function(){getTermekVariaciok('{$id}');});");
		$name = $node->getAttribute('name');
		$value=$this->getaktVal($name);
		?>
        <div id="ajaxContentParameterLista" ></div>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
   }

	function getFeladasAdmin($node) {
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$arr = array();
		ob_start();
		echo $value;
		$ret = ob_get_contents();	  
		ob_end_clean();
		return $ret;
		// die($readonly);
	}
	

	function getKategoria($node) {
		$name = $node->getAttribute('name');
      $value=$this->getAktVal($name);
	  $arr = array();
		ob_start();
	  if( $this->isPopup() ){
		$obj = $this->getObj("#__whp_kategoria", $value);
		?>
<input name="<?php echo $name ?>" type="hidden" value="<?php echo $value ?>" />
<?php echo $obj->nev ?>
<?php	
	  }else{
		  $kategoriafa = new kategoriafa( );	  
	      echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"alapinput", "readonly" => "readonly"), "value", "option", $value);
	
	  }
	$ret = ob_get_contents();	  
	ob_end_clean();
	return $ret;
	 // die($readonly);
   }

	function getKategoria_d($node) {
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		ob_start();
		$obj = $this->getObj("#__whp_kategoria", $value);
		echo $obj->nev;
		$ret = ob_get_contents();	  
		ob_end_clean();
		return $ret;
		// die($readonly);
	}
}
?>
