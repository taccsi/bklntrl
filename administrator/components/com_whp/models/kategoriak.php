<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whpModelkategoriak extends whpAdmin
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("kategoria.xml");	
		//$this->rebuild_tree(0, 0);
		//$this->tree( 0 );
		//$this->tree_ = $tree; 
	}//function

	function getTree(){
		ob_start();
		$this->kategoriafa = new kategoriafa( );
		$i=0;
		foreach($this->kategoriafa->catTree as $c){
			$link = "index.php?option=com_whp&controller=kategoria&task=edit&cid[]={$c->value}&fromlist=1";
			$checked = JHTML::_('grid.id',   $i++, $c->value );
			echo $checked;?> <a href="<?php echo $link ?>"><?php echo $c->option; ?> </a> <?php echo $this->sorrendNyilak($c); ?> <input name="sorrend[]" value="<?php echo $c->value; ?>" type="hidden" /><br />
            <?php
		}
		$tree = ob_get_contents();
		ob_end_clean();
		return $tree;
	}

	function sorrendNyilak( $obj ){
		ob_start();
		//print_r($obj);
		//die;
		;
		$q = "select count(id) as osszes from #__whp_kategoria where szulo = {$obj->szulo} and id <> {$obj->value}";
		$this->_db->setQuery($q);
		$osszes = $this->_db->loadResult();
		//print_r($obj);
		//echo $q."<br />";
		//echo $osszes." -";
		if($obj->sorrend < ($osszes) ){
			$link= "javascript:void(0);";
			$js = "javascript:sorrend('".$obj->value."', 'le');";
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js ?>" ><img src="components/com_whp/assets/images/downarrow.png" /></a>
			<?php
		}
		if($obj->sorrend > 0 ){
			$link= "javascript:void(0);";
			$js = "javascript:sorrend('".$obj->value."', 'fel');";
			?>
			<a href="<?php echo $link ?>" onclick="<?php echo $js ?>" ><img src="components/com_whp/assets/images/uparrow.png" /></a>
			<?php
		}		

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function _buildQuery()
	{
		$cond = $this->getCond();	
		$query = "SELECT * FROM #__whp_kategoria";
		//echo $query;
		return $query;
	}

	function sorrend(){
		$irany = JRequest::getVar("irany");
		$sorrendOsszes = JRequest::getVar("sorrend", array() );		
		$sorrendId = JRequest::getVar("sorrendId");				
		$obj = $this->getObj($sorrendId);
		$q = "select id from #__whp_kategoria where szulo = {$obj->szulo}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadResultArray();
		$sorrend = array();
		foreach($sorrendOsszes as $s){
			if(in_array($s, $rows)){
				$sorrend[]=$s;
			}
		}
		$ind = array_search($sorrendId, $sorrend);			
		if($irany=="le"){
			$temp = $sorrend[$ind+1];
			$sorrend[$ind+1] = $sorrendId;
			$sorrend[$ind] = $temp;
		}else{
			$temp = $sorrend[$ind-1];
			$sorrend[$ind-1] = $sorrendId;
			$sorrend[$ind] = $temp;
		}
		
		foreach($sorrend as $id){
			$ind = array_search($id, $sorrend);
			$q = "update #__whp_kategoria set sorrend = '{$ind}' where id = {$id} ";
			$this->_db->setQuery($q);
			$this->_db->Query();
			//die( $this->_db->getErrorMsg() );
		}
		$arr = array();
	}
	
	function getObj($id){
		$q = "select * from #__whp_kategoria where id = {$id}";	
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		//die( $this->_db->getErrorMsg() );
		return $obj;
	}
	
	function delete(){
		$arrNemTorolheto=array();
		foreach( JREquest::getVar("cid", "")  as $id){
			if( $this->torolhetoKat($id) ){
				$q="delete from #__whp_kategoria where id = {$id}";
				$this->_db->setQuery($q);
				$this->_db->query();
			}else{
				$arrNemTorolheto[] = $id;
			}		
		}
		//print_r($arrNemTorolheto);
		return $arrNemTorolheto;
		//die;
	}
	
	function torolhetoKat($id){

		$q="select * from #__whp_kategoria where szulo = {$id}";
		$this->_db->setQuery($q);
		if($this->_db->loadResult()) return 0;//van gyereke a katnak

		$q="select * from #__whp_kategoria where id = {$id}";
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		
		$q="select kategoria_id from #__whp_termek as x inner join #__whp_kategoria as c 
		where c.lft > {$obj->lft} and c.rgt < {$obj->rgt}";
		$this->_db->setQuery($q);
		//print_r($this->_db->loadResult());
		if($this->_db->loadResult()) return 0;	
		return 1;
	}

	function getCond( $szulo=0 ){
		$cond ="";
		$group = $this->xmlParser->getGroup("condFields" );
		$mezok = array();
		$db = JFactory::getDBO();
		foreach ( $group->childNodes as $element ){
			if(is_a($element, "DOMElement") ){
				$field= $element->getAttribute('name');
				$q= $element->getAttribute('q');
				$val = JRequest::getVar($field, "");
				if( $val ){
					//echo "{$field}------<br />";
					switch($field){
						case "datum": $cond .= "DATE_FORMAT( datum, '%Y') = {$val} and "; break;
						default : $cond .= "{$q} like '%{$val}%' and ";
					}
				}
			} 
		}
		//getGroup( $value )
		//$cond = "id in (".implode(",",$this->user->jog->kategoriak).") and ";
		$cond .= "szulo = {$szulo} and ";
		if($cond){
			$cond = "where ".substr($cond, 0, strlen($cond)-4);
		}
	 	//echo $cond." * <br /><br />";
      //die($cond);
	  
	  //$cond="";
      return $cond;
   }

	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;
	}//function
	
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
 	    jimport('joomla.html.pagination');
 	    $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit );
 		return $this->_pagination;
  }//function

}// class
?>