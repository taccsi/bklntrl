<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelbeszallitok extends modelBase
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		$this->limitstart = JRequest::getVar("limitstart", 0);
		$this->xmlParser = new xmlbeszallito("beszallito.xml");	

	}//function

	function getSearch(){
		$basetxt = JText::_('ide írja az ügyfél nevét');
		$nev = JRequest::getVar("nev");
		$nev = ($nev) ? $nev : $basetxt;
		$tmpl = JRequest::getVar('tmpl');
		$tmpl = ($tmpl) ? $tmpl : '';
		$controller = JRequest::getVar('controller');
		$controller = ($controller) ? $controller : '';
		
		ob_start();
	
		?>
        <form id="kereso_forgalmak" method="get" action="<?php echo JRoute::_('index.php'); ?>">
		<input class="kereso_nev" name="nev" value="<?php echo $nev; ?>" onfocus="if(this.value=='<?php echo $basetxt; ?>'){this.value='';}" tabindex="1" />
        <?php echo $this->getVarosok(); ?>
        <?php echo $this->getTKim(); ?>
        <?php if ($tmpl): ?><input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" /><?php endif; ?>
        <input type="hidden" name="option" value="com_wh" />
        <?php if ($controller): ?><input type="hidden" name="controller" value="<?php echo JRequest::getVar('controller'); ?>" /><?php endif; ?>
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
        <input class="kereso_submit" type="submit" value="<?php echo JText::_("KERESES") ?>" onclick="if(document.getElementById('kereso_forgalmak').nev.value=='<?php echo $basetxt; ?>'){document.getElementById('kereso_forgalmak').nev.value='';};return true;" />
        </form>
		<?php
	
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getSearch_(){
		ob_start();
		?>

        	<table class="ws_search" border="0" cellspacing="0" cellpadding="0">
              <tr>
              <td><span class="span_s_title"><?php echo JText::_("beszallito NAME") ?></span>: <input name="nev" value="<?php echo JRequest::getVar("nev") ?>" on /></td>
              <td><span class="span_s_title"><?php echo JText::_("VEGZETTSEG SZINT") ?></span>: <?php echo $this->getSearchSelect("vegzetseg_szint") ?></td>
              <td><span class="span_s_title"><?php echo JText::_("VEGZETTSEG TIPUS") ?></span>: <?php echo $this->getSearchSelect("vegzetseg_tipus") ?></td>
              <td><span class="span_s_title"><?php echo JText::_("NYELVTUDAS") ?></span>: <?php echo $this->getSearchSelect("nyelv1") ?></td>
              <td><span class="span_s_title"><?php echo JText::_("MUNKATAPASZTALAT") ?></span>: <?php echo $this->getSearchSelect("munkatapasztalat") ?></td>
              <td><span class="span_s_title"><?php echo JText::_("TERULET") ?></span>: <?php echo $this->getSearchSelect("terulet") ?></td>              
            <td><input type="submit" value=" <?php echo JText::_("KERESES") ?>" /></td>
            </tr>
            </table>
			<?php echo $this->xmlParser->getOrderHiddenFields(); ?>
            <input type="hidden" name="orderField" id="orderField" value="" />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getSearchSelect($name){
		$node = $this->xmlParser->getNode("name", $name);
		$value = $this->getSessionVar($name);
		return $this->xmlParser->getMultipleSelect($name, $node, $value );	
	}

	function _buildQuery()
	{
		$cond = $this->getCond();
		$cond = $this->getCondbeszallitok($cond);
		
		//die($cond);	
		$query = "SELECT u.* FROM #__wh_beszallito as u
					left JOIN #__wh_tkbeszallito AS tku ON tku.beszallitokod = u.beszallitokod
					left JOIN #__wh_munkatars AS m ON m.munkatars = tku.munkatars
		 {$cond} order by nev";
		//die($query);
		//echo $query;
		return $query;
	}

	function getData()
	{
		$query = $this->_buildQuery();
		$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
		if ($this->_data === NULL) $this->_data = array();
		//print_r($this->_data); 
		//echo $this->_db->getQuery();
		//die();
		array_map(array($this,"setInformaciok"), $this->_data);
		array_map(array($this,"setBuborekbeszallito"), $this->_data);				
		//array_map($this$this->_data,setInformaciok($item);
		return $this->_data;
	}//function
	
	function getOneletrajz($item){
		//oneletrajz
		ob_start();
		for($n=1; $n<=$this->images; $n++){
			$docname ="{$this->uploaded}/{$item->id}_{$n}.doc"; 
			//echo $docname." -- - - - -<br />";
			if(file_exists($docname)){
			   //$link = "";
			   echo "{$n}. ".JText::_("ONELETRAJZ"); ?> <a href="<?php echo $docname ?>">&gt;&gt;<?php echo JText::_("DOWNLOAD"); ?>&lt;&lt;</a><br />
   				<?php
			}
		}
		$ret = ob_get_contents();
		ob_end_clean();
		$item->oneletrajz=$ret;
		return $item;
	}
	
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);	
		}
		return $this->_total;
	}//function
  
	function getPagination()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_pagination))
 	{
 	    jimport('joomla.html.pagination');
 	    $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit );
 	}
 	return $this->_pagination;
  }//function

}// class
?>