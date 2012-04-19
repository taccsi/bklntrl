<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("WH FOOLDAL") ?></h3>

</div>
<div style=" width:700px; text-align:center; padding:40px; margin-left:auto; margin-right:auto; font-size:18px"><h2>Üdvözlet az adminfelületen, kérlek válassz a fenti menüpontok közül.</h2></div>
<h3>A következő modul(ok)hoz van jogosultságod:</h3>
<?php 
$arr = felhasznaloiJogok::_();
//print_r($arr);
$user=jfactory::getuser();
echo "<ul class=\"ul_jogok\">";
if($user->usertype !="Super Administrator"){
	foreach($arr[ $user->usertype ] as $a){
		if($a!="wh"){
			echo "<li>";
			$arr_ = explode(",", $a);
			if(count($arr_)>1){
				echo "<ul>";
					foreach($arr_ as $b){
						echo "<li>".jtext::_($b)."</li>";
					}			
				echo "</ul>";
			}else{
				echo jtext::_($a);
			}
			echo "</li>";
		}
	}
}else{
	echo "Super Administrator vagy, minden funkciót elérhetsz";
}
echo "</ul>";	
?>