<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__)."/base_template.php");
//ini_set("display_errors", 1);
error_reporting(E_ALL);

class show_group extends base_template{
	var $th_szel = 215;
	var $th_mag = 215;
	var $th_kiskep_szel = 120;
	var $th_kiskep_mag = 50;	
		
	function __construct(){
		parent::__construct();
		@$this->session =& JSession::getInstance();
	}

	function getTpl()
	{
		foreach($this->cell->searchInGroup as $k => $sig){
			if (JRequest::getVar('from') != 'basket'){
				$p = JRequest::getVar($k);
				if ($p === NULL && $this->session->get($k) !== NULL)
					JRequest::setVar($k, $this->session->get($k));
				else
					$this->session->set($k, $p);
			} else $this->session->clear($k);
		} 
		
		ob_start();
		//print_r($this->cell);
		?>
		<div class="bontas_top"><div class="div_bontas_images">
        	{listImage}
            {pics}
        </div>
        <div class="div_bontas_texts">
        	{name}
            {tulajdonsagok}
            <div class="desc">{descr}</div>
        </div><div class="clr"></div></div>
        
<div class="bontas_kozepsav">

        	<strong><?php echo ($this->filter()) ? JText::_('SZURES'). ':' : ''; ?></strong>
            {filter}
            <div class="clr"></div>
        </div>
		{sorok}
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}	

	function sorok(){
		//print_r($this->cell->rows);
		if(count($this->cell->rows)){
		
			ob_start();
			$paros='even';
			  
			  foreach($this->cell->rows as $r): 
	
				  if ($this->sor($r)): 
					  
					  if ($paros != 'odd' ) {$paros='odd';} else {$paros='even';}
					  if (priceHelper::isDiscounted($r->id)) {$disc=' discounted';} else {$disc='';}
					  ?>                 
					  <tr class="tr_sorok_content_<?php echo $paros.$disc; ?>">
						<?php foreach($this->cell->groupParams as $p): ?>
						<?php if (count($this->cell->searchInGroup[$p->paramName]) > 1 ): ?>
						<td><?php echo @$r->params[$p->paramName]->value; ?></td>
						<?php endif; ?>
						<?php endforeach; ?>
						<td><?php echo $this->getPrice($r->price); ?></td>
						<td><?php echo @$r->keszleten ?></td>
						<td class="td_last"><input class="input_pcs" name="pcs[]" value="" type="text" <?php echo ($r->price == 0) ? 'disabled="disabled"' : ''; ?> /><input name="sku[]" value="<?php echo $r->sku ?>" type="hidden" /></td>
					  </tr>
				  <?php endif; ?>
			  <?php endforeach;
			$ret = ob_get_contents();
			ob_end_clean();
	
			ob_start();
			if (trim($ret) != ""){
			
			?>
			<a class="addtocart" onclick="document.getElementById('basketForm').submit()" >Kosárba</a>        
			<form id="basketForm" name="basketForm" method="post" action="index.php">
			<table class="table_variaciok" border="0" cellspacing="0" cellpadding="0">
			  <tr class="tr_sorok_head">
				<?php
				
				 foreach($this->cell->groupParams as $p): ?>
				
				<?php 
				
				
				if (count($this->cell->searchInGroup[$p->paramName]) > 1 ): ?>
				<th><?php echo JText::_($p->name); ?></th>
				<?php endif; ?>
				<?php endforeach; ?>
				<th><?php echo JText::_('ARDB'); ?></th>
				<th><?php echo JText::_('KESZLETEN'); ?></th>
				<th class="th_last"><?php echo JText::_('DARAB'); ?></th>
			  </tr>
			  
			  <?php 
			  
				echo $ret;
				
			   ?>
			</table>
			<input type="hidden" name="rajszam" value="<?php echo JRequest::getVar("rajszam"); ?>"  />
			<input type="hidden" name="option" value="com_vs"  />
			<input type="hidden" name="controller" value="basket"  />
			<input type="hidden" name="task" value="add"  />     
			</form>
			<a class="addtocart" onclick="document.getElementById('basketForm').submit()" >Kosárba</a>        
			<?php
			}else{
				?>
				<div style="padding:10px 0; text-align:center"><?php echo JText::_("NINCS TALALAT");?></div>            
				<?php
			}
			$tpl=ob_get_contents();
			ob_end_clean();
			return $tpl;
		}
	}
	
	function name(){
		ob_start();
		//print_r($this->cell->rows);
		?>
		<h1><?php echo $this->cell->rows[0]->name ?></h1>
		<?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;	
	}
	
	
	function tulajdonsagok()
	{
		$params = array();
		$ret = "";
		
		foreach($this->cell->rows as $r){
			foreach($this->cell->groupParams as $p){
				if (!in_array($p->paramName, $params) && $p->paramName != "p_rajszam") {
					$params[] = $p->paramName;
					if (count($this->cell->searchInGroup[$p->paramName]) == 1){
						$ret .= '<span class="param">'.$p->name . ': ' . $r->params[$p->paramName]->value . '</span>';
					}
				}
			}
		}
		
		return $ret;
	}
	
	
	function filter()
	{
		$ret = "<form id=\"filterForm\" method=\"get\" action=\"\">";
		
		foreach($_GET as $k => $v){
			$ret .= (substr($k,0,2) != 'p_') ? "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />" : "";
		}
		
		foreach($this->cell->searchInGroup as $k => $sig){
			if (count($sig) > 1){
				sort($sig);
				$s = new stdClass();
				$s->value = '';
				$s->option = ' - ' .$this->paramName($k) . ' - ';
				$f = array($s);
				
				for($i=0;$i<count($sig);$i++){
					$s = new stdClass();
					$s->value = $s->option = $sig[$i];
					$f[] = $s;
				}
				
				$ret .= JHTML::_('select.genericlist', $f, $k, 'class="inputbox" onchange="document.getElementById(\'filterForm\').submit()"', 'value', 'option', JRequest::getVar($k)); 
			}
		}
		
		$ret .= "</form>";
		
		return (count($this->cell->searchInGroup)) ? $ret : '';
	}
	
	
	function paramName($param)
	{
		foreach($this->cell->groupParams as $p){
			if ($p->paramName == $param) return $p->name;
		}
		
		return "";
	}
	
	
	function sor($r)
	{
	
		 
		
		foreach($this->cell->searchInGroup as $k => $sig){
			
			$param = JRequest::getVar($k);
			//print_r($sig);
			if ($param === NULL || trim($param) == "") continue;
			
			foreach($r->params as $p){
				
				if ($p->paramName == $k && $p->value != $param) return false;
			}
		}
		
		return true;
	}
	

	function listImage()
	{
		global $Itemid;
		//print_r($this->cell->pics);exit;
		@$forras_kep = "{$this->dir_forras}gal_{$this->cell->pics[0]->id}.jpg";
		@$cel_kep = "{$this->dir_cel}galeria_{$this->cell->pics[0]->id}_{$this->th_szel}_{$this->th_mag}_1.jpg";
		$link=$forras_kep;
		//echo $forras_kep."<br />";
		//echo $cel_kep;
		
		return $this->image($forras_kep, $cel_kep, $this->th_szel, $this->th_mag, "resize", $link, "class=\"zoom\"" ,
		"", $this->cell->rows[0]->name);
	}

	function pics()
	{
		global $Itemid;
		$ret="";
		//echo $this->cell->rows[0]->name;
		for($i=1; $i<count($this->cell->pics); $i++){
			//print_r($this->cell->rows[0]->pics);exit;
			@$forras_kep = "{$this->dir_forras}gal_{$this->cell->pics[$i]->id}.jpg";
			@$cel_kep = "{$this->dir_cel}gal_{$this->cell->pics[$i]->id}_{$this->th_kiskep_szel}_{$this->th_kiskep_mag}_{$i}.jpg";
			$link=$forras_kep;
			$ret.= $this->image($forras_kep, $cel_kep, $this->th_kiskep_szel, $this->th_kiskep_mag, "resize", $link, "class=\"zoom\"" ,
			"",$this->cell->rows[0]->name);
		}
		return $ret;
	}	
}
?>
