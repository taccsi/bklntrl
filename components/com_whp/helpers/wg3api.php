<?php 
class wg3api {
	
	
	
	function mq($query) {
		$sql = mysql_query($query) or $this->error_logging("Hibás sql kérés: <font color=green><b>$query</b></font>\r\nHiba:<font color=red>".mysql_error()."</font>");
		return $sql;
	}
	
	function m_array($query, $mezo = '') {
		$s=mysql_fetch_assoc( $this -> mq ($query) );
		if($mezo) return $s[$mezo];
		return $s;
	}
		
   function mail_id($mail, $db_pre='wg3_') {
		$x = $this->m_array("SELECT mail_id FROM `{$db_pre}users` where lower(mail)=lower('$mail') limit 0, 1", 'mail_id');
		$x = $x?$x:($this->m_array("SELECT mail_id FROM `{$db_pre}users` order by mail_id desc limit 0, 1", 'mail_id')+1);
		return $x;
	}

	function generateCode($length = 6) {
	   $Code = "";
		mt_srand((double)microtime()*1000000);
		
		while(strlen($Code)<$length) {
			$random = mt_rand(48,122);
			$random = md5($random);
			$Code .= str_replace('0','k',substr($random, 17, 1));
			}
			  
    	return $Code;
	}

	function getip() {
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}

			} else {
			
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
				$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
			} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
				$realip = getenv( 'HTTP_CLIENT_IP' );
			} else {
				$realip = getenv( 'REMOTE_ADDR' );
			}
		}
		return $realip; 
	}	
   
	// adott azonosítójú feliratkozó adatait teszi bele egy tömbbe 
	// pl: print_r(user_data(102));
	function user_data($uid, $db_pre='wg3_') {
	 $s = $this-> m_array("select * from `{$db_pre}users` where id='$uid'");
	 $x = $this-> mq("Select * from `{$db_pre}fields` where g='$s[g]'");
	 while ($y=mysql_fetch_assoc($x))
	  {
	    $v = $this->m_array("select * from `{$db_pre}fields_data` where un='$s[id]' and did='$y[id]'", ($y['type']==2||$y['type']==4)?'int_text':'text');
	    if     ($y['type']<2) $s[$y['name']] = $v;
		elseif (!strstr($v, ';')) $s[$y['name']] = $this->m_array("select * from `{$db_pre}fields_fields` where id='$v'", 'name');
		else 
		 {
		   $array = explode (';', $v);
		   $s[$y['name']] = array();
		   foreach($array as $vv) $s[$y['name']][] = $this->m_array("select * from `{$db_pre}fields_fields` where id='$vv'", 'name');
		 }
	  }
	return $s;
	}

  /* példa -- 
  new_user ( array 
                ( 'name' => 'Jakab Béla', 
						'mail' => 'jakab@bela.hu',
						'datum' => date('Y-m-d'),
						'jutalék' => '1200Ft',      // a plussz mezőket így kell megadni
						'active' => 1               // 1 - aktív, 0 - inaktív, 2- visszapattant
				 ),  5
			); 
			
	Ebben az esetben az ötös csoportba szúrúnk be egy Jakab Béla nevű feliratkozót.
	A függvény ellenőrzi, hogy létezik-e ilyen e-mail című feliratkozó, ha igen, és nincs engedélyezve a többszörös feliratkozás, akkor nem csinál semmit.
	Ha nem létező csoportba kívánnák beszúrni feliratkozónkat a függvény visszatérési értéke hamis lesz.
  */
	    function new_user($array, $g=0, $db_pre='wg3_') { 
	   if(!$g) return false;
	   $g_a=$this->m_array("select * from `{$db_pre}groups` where id='$g'"); // megnézzük létezik e a csoport								 
	   if($g_a)								   
		 { 									  
		   if($g_a['d_user'] || !$this->m_array("select * from `{$db_pre}users` where mail='$array[mail]' and g='$g'"))  // ha nincs benne a csoportba vagy lehet t?bbsz?r benne										
			{										  
			  $v_C=$this->generateCode(40);	
			  if(!$array['mail_id']) $array['mail_id'] = $this -> mail_id($array['mail'], $db_pre);
			  if(!$array['date']) $array['date']='CURDATE()'; else $array['date']="'$array[date]'";
			  $this->mq("insert into `{$db_pre}users` (name, mail, mail_id, ip, datum, verify_code,  active, g, ipdatum) values 
			                                          ('$array[name]', '$array[mail]', '$array[mail_id]', '".$this->getip()."',
													   $array[date], '$v_C', $array[active], '$g', '$array[ipdatum]')");
			  $id = mysql_insert_id();
  			  $this->mq("update `{$db_pre}groups` set stat_auto=stat_auto+1 where id='$g'");										
			  reset($array); $i =0;
			  foreach($array as $k=>$v)
			   {
			     $i++; 
				  $mezo = $this-> m_array ("select * from `{$db_pre}fields` where g='$g' and name='$k'");
				  if($mezo) // létezik a mezo hozzáadjuk.
				   {
				     if($mezo['type']<2) $this->mq ("insert into `{$db_pre}fields_data` (un, text, did) values ('$id', '".mysql_real_escape_string($v)."', '$mezo[id]')");
					 if($mezo['type']==2 || $mezo['type']==4) 
					  { 
					    if(!is_array($v)) 
						  {
						    $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v'");
						    if($mezo_x) $this->mq ("insert into `{$db_pre}fields_data` (un, int_text, did) values ('$id', '$mezo_x[id]', '$mezo[id]')");
						  }
						 else {
						 foreach($v as $v2)
						  {
					        $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v2'");
							if($mezo_x) $data=$mezo_x['id'];
							break;
						  }
					     if($data) $this->mq ("insert into `{$db_pre}fields_data` (un, int_text, did) values ('$id', '$data', '$mezo[id]')");
						} // ha tömb
					  } // r?di? - select
					 
					 //chx
					 if($mezo['type']==3) 
					  {  
					    $data = '';
					    if(is_array($v)) {
						 foreach($v as $v2)
						  {
					        $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v2'");
							if($mezo_x) $data.=$mezo_x['id'].';';
						  }
					     if($data) $this->mq ("insert into `{$db_pre}fields_data` (un, text, did) values ('$id', '$data', '$mezo[id]')");
						} // ha tömb
					    else // ha nem tömb
						  {
						    $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v'");
						    if($mezo_x) $this->mq ("insert into `{$db_pre}fields_data` (un, text, did) values ('$id', '$mezo_x[id]', '$mezo[id]')");
						  }
					  } // cx
				   } // létezo mezo
			   }
			} // if
		 } else return false; // if							  
	}

    
	// egyik csoportból másikba másolja az adott feliratkozót
	// user_copy(másolandó feliratkozó azonosítója, csoportok ahova másoljuk pontosvesszővel elválasztva, webgalamb adatbázis előtag neve)
	function user_copy($uid, $gs, $db_pre='wg3_') { 
	   $user_array = $this -> user_data ($uid);
	   $a=@explode(';', $gs);				   
	   if($a) foreach($a as $_id)							  
		 {
		   $g_a=$this->m_array("select id from `{$db_pre}groups` where id='$_id'"); // megnézzüök létezik e a csoport								 
		   if($g_a)	{  $this-> new_user($user_array, $_id, $db_pre); }
		} 
     } 
    
    
	// csoportból/csoportokból kitöröl egy adott email címmel feliratkozó feliratkozót
	function user_delete($mail, $gs, $db_pre='wg3_') {
		 $a=@explode(';', $gs);				   
		 if($a) foreach($a as $_id)							  
		  { 
			 $g_a=$this->m_array("select * from `{$db_pre}groups` where id='$_id'"); // megnézzüök létezik e a csoport								 
			 if($g_a)								   
			   { 									  
				 $y=$this->mq("select * from `{$db_pre}users` where g='$_id' and mail='$mail'");									  
				 while($z=mysql_fetch_array($y))										
				   {											
					  $this->mq("delete from `{$db_pre}users` where id='$z[id]'");											
					//  $this->mq("delete from `{$db_pre}stat` where uid='$z[id]'"); // statisztikából megfello sorok kitörlés
					//  $this->mq("delete from `{$db_pre}act_stat` where uid='$z[id]'");
					  $this->mq("delete from `{$db_pre}fields_data` where un='$z[id]'");											
					  $this->mq("update `{$db_pre}groups` set stat_autodel=stat_autodel+1 where id='$_id'");														
				   }								   
			   } 					  
		 } 
	}

  
  
  /* példa -- 
  user_mod ( array 
                ( 'name' => 'Jakab Béla', 
						'mail' => 'jakab@bela.hu',
						'datum' => date('Y-m-d'),
						'jutalék' => '1200Ft';  // a plussz mezőket így kell megadni
						'active' => 1                  // 1 - aktív, 0 - inaktív, 2 - visszapattant
				 ),  'valaki@gmail.com',
				     5
			); 
			
	Ebben az esetben az ötös csoportban lévő valaki@gamail.com adatait módosítjuk arra, anmire szeretnénk.
  */
  function user_mod($array, $mail, $g, $db_pre='wg3_') {
	   if(!$g) return false;
	   $g_a=$this->m_array("select * from `{$db_pre}groups` where id='$g'"); // megnézzük létezik e a csoport								 
	   if($g_a)								   
		 { 									  
			  $us=$this->m_array("select * from `{$db_pre}users` where mail='$mail' and g='$g'");
			  if(!$us) return false;
			  if(!isset($array['mail_id'])) $array['mail_id'] = $this -> mail_id($mail, $db_pre);
			  if(!isset($array['date'])) $array['date']='CURDATE()'; else $array['date']="'$array[date]'";
			  $sqlq1="update `{$db_pre}users` SET ";
			  $sqlq ='';
				   if(isset($array['name'])) $sqlq.="name='$array[name]'";
				   if(isset($array['mail'])) $sqlq.=($sqlq?',':'')."mail='$array[mail]'";
				   if(isset($array['ip'])) $sqlq.=($sqlq?',':'')."ip='$array[ip]'";
				   if(isset($array['datum'])) $sqlq.=($sqlq?',':'')."datum='$array[datum]'";
				   if(isset($array['active'])) $sqlq.=($sqlq?',':'')."active='$array[active]'";
				   if(isset($array['a'])) $sqlq.=($sqlq?',':'')."a='$array[a]'";
				   if(isset($array['ipdatum'])) $sqlq.=($sqlq?',':'')."ipdatum='$array[ipdatum]'";
			  
			  if($sqlq) $this->mq($sqlq1.$sqlq." WHERE id='$us[id]'");
			  
			  $id = $us['id'];
			  
			  reset($array); $i =0;
			  foreach($array as $k=>$v)
			   {
			     $i++; 
				  $mezo = $this-> m_array ("select * from `{$db_pre}fields` where g='$g' and name='$k'");
				  if($mezo) // létezik a mező hozzáadjuk.
				   {
				     if($mezo['type']<2)
					    if(! $this->m_array("select * from `{$db_pre}fields_data` where un='$id' and did='$mezo[id]'"))
						  $this->mq ("insert into `{$db_pre}fields_data` (un, text, did) values ('$id', '".mysql_real_escape_string($v)."', '$mezo[id]')");
						else 
						  $this->mq ("update `{$db_pre}fields_data` set text='".mysql_real_escape_string($v)."' where un='$id' and did='$mezo[id]'");
					 
					 if($mezo['type']==2 || $mezo['type']==4) 
					  { 
					    if(!is_array($v)) 
						  {
						    $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v'");
						    if($mezo_x) 
								if(! $this->m_array("select * from `{$db_pre}fields_data` where un='$id' and did='$mezo[id]'"))
								  $this->mq ("insert into `{$db_pre}fields_data` (un, int_text, did) values ('$id', '$mezo_x[id]', '$mezo[id]')");
								else 
								  $this->mq ("update `{$db_pre}fields_data` set text='$mezo_x[id]' where un='$id' and did='$mezo[id]'");
						  }
						 else {
						 foreach($v as $v2)
						  {
					        $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v2'");
							if($mezo_x) $data=$mezo_x['id'];
							break;
						  }
						  if($data)	
						    if(! $this->m_array("select * from `{$db_pre}fields_data` where un='$id' and did='$mezo[id]'"))
							   $this->mq ("insert into `{$db_pre}fields_data` (un, int_text, did) values ('$id', '$data', '$mezo[id]')");
							 else 
							   $this->mq ("update `{$db_pre}fields_data` set text='$data' where un='$id' and did='$mezo[id]'");
						} // ha tömb
					  } // rádió - select
					 
					 // chx
					 if($mezo['type']==3) 
					  {  
					    $data = '';
					    if(is_array($v)) {
						 foreach($v as $v2)
						  {
					        $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v2'");
							if($mezo_x) $data.=$mezo_x['id'].';';
						  }
					     if($data) 
						    if(! $this->m_array("select * from `{$db_pre}fields_data` where un='$id' and did='$mezo[id]'"))
							   $this->mq ("insert into `{$db_pre}fields_data` (un, int_text, did) values ('$id', '$data', '$mezo[id]')");
							 else 
							   $this->mq ("update `{$db_pre}fields_data` set text='$data' where un='$id' and did='$mezo[id]'");
						} // ha tömb
					    else // ha nem tömb
						  {
						    $mezo_x = $this-> m_array ("select * from `{$db_pre}fields_fields` where field_id='$mezo[id]' and name='$v'");
						    if($mezo_x) 
								if(! $this->m_array("select * from `{$db_pre}fields_data` where un='$id' and did='$mezo[id]'"))
								   $this->mq ("insert into `{$db_pre}fields_data` (un, int_text, did) values ('$id', '$mezo_x[id]', '$mezo[id]')");
								 else 
								   $this->mq ("update `{$db_pre}fields_data` set text='$mezo_x[id]' where un='$id' and did='$mezo[id]'");
						  }
					  } // cx
				   } // létező mező
			   }
		   return true;
		 } else return false; // if							  
  }

}// wg3 api class
?>