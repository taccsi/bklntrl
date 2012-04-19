<?php
defined( '_JEXEC' ) or die( '=;)' );
class listazo extends SimpleImage{
	function __construct($arr=array(), $class = "", $lapozoFelso="", $lapozoAlso="", $kivetelArr=array(), $sw="" ){
		$this->sw = $sw;
		$this->arr = $arr;
		$this->kivetelArr = $kivetelArr;
		$this->class = $class;
		$this->oszlopSzam = 0;
		$this->lapozoFelso = $lapozoFelso;
		$this->lapozoAlso = $lapozoAlso;		
		
		if(@$arr[0]){
			foreach($arr[0] as $a =>$v){
				$this->oszlopSzam++;
			}
		}
	}

	function spec(){
		ob_start();
		?>
		<table class="<?php echo $this->class ?>">
		<?php 
		if($this->lapozoFelso){
			echo "<tr><td class=\"td_lapozo\" colspan=\"{$this->oszlopSzam}\">{$this->lapozoFelso}</td></tr>";
		}
		?>
        <tr>
		<?php
        foreach($this->arr[0] as $oszlnev => $ertek){
            ?>
            <th class="liusta_th <?php echo $oszlnev ?>"><?php echo JText::_($ertek); ?></th>
            <?php
        }
        ?>
		</tr>
		<?php
		$db=0;
		foreach($this->arr as $obj){
			$ind = array_search($obj, $this->arr );
			if($ind){
				($db%2 == 0) ? $class = "class=\"row1\"" : $class = "class=\"row2\"";
				echo "<tr {$class} >";
				foreach($obj as $nev => $v){
					?>
					<td class="lista_td <?php echo $nev ?>" ><?php echo $v;?> </td>
				<?php
				}
				$db++;
				echo "</tr>";
			}
		}
		?>
		</tr>
		<?php 
		if($this->lapozoAlso){
			echo "<tr><td class=\"td_lapozo\" colspan=\"{$this->oszlopSzam}\">{$this->lapozoAlso}</td></tr>";
		}
		?>
		</table>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getLista(){
		if(@$this->arr[0]){		
			switch($this->sw){
				case 3:
					$ret = $this->spec(); break;				
				case 2 :
					$ret = $this->egyszeru(); break;			
				case 1 :
					$ret = $this->elforgatott(); break;			
				default :
					$ret = $this->normal();
			}
		}else{
			$ret = "";
		}
		return $ret;
	}

	function egyszeru(){
		ob_start();
		?>
		<table class="<?php echo $this->class ?>">
		<?php 
		$nevek = array();
		foreach($this->arr[0] as $nev => $ertek){
			$nevek[] = $nev;
		}
		foreach($this->arr as $obj) {
			echo "<tr><td>";
			foreach ($nevek as $nev){
				$i = array_search($nev, $nevek);
				echo "<span class=\"span_egyszeru{$i}\">{$obj->$nev}</span>";
			}
			echo "</td></tr>";
		}
		?>
		</table>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function elforgatott(){
		ob_start();
		?>
		<table class="<?php echo $this->class ?>">
		<?php 
		if($this->lapozoFelso){
			echo "<tr><td class=\"td_lapozo\" colspan=\"{$this->oszlopSzam}\">{$this->lapozoFelso}</td></tr>";
		}
		$nevek = array();
		foreach($this->arr[0] as $nev => $ertek){
			$nevek[] = $nev;
		}
		foreach($nevek as $nev) {
			echo "<tr>";
			foreach ($this->arr as $obj){
				$i = array_search($obj,$this->arr);
				if (!$i) echo "<th>{$nev}</th>";
				echo "<td>" . $obj->$nev . "</td>";
			}
			
			echo "</tr>";
		}
		?>
		</table>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function normal(){
		ob_start();
		?>
		<table class="<?php echo $this->class ?>">
		<?php 
		if($this->lapozoFelso){
			echo "<tr><td class=\"td_lapozo\" colspan=\"{$this->oszlopSzam}\">{$this->lapozoFelso}</td></tr>";
		}
		?>
        <tr>
		<?php
        foreach($this->arr[0] as $oszlnev => $ertek){
            ?>
            <th class="liusta_th <?php echo $oszlnev ?>"><?php echo JText::_($oszlnev); ?></th>
            <?php
        }
        ?>
		</tr>
		<?php
		$db=0;
		foreach($this->arr as $obj){
			($db%2 == 0) ? $class = "class=\"row1\"" : $class = "class=\"row2\"";
			echo "<tr {$class} >";
			foreach($obj as $nev => $v){
				?>
				<td class="lista_td <?php echo $nev ?>" ><?php echo $v;?> </td>
			<?php
		}
			$db++;
			echo "</tr>";
		}
		?>
		</tr>
		<?php 
		if($this->lapozoAlso){
			echo "<tr><td class=