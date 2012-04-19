<?php
defined( '_JEXEC' ) or die( '=;)' );

class xCemail /* extends xccAdmin */{

	var $recallDay = 7;
	var $elvalaszto = "<br />------------------------------------------------------------------------------------------------------------<br />";
	function __construct()
	{
		$this->_db = JFactory::getDBO();
		//$this->cron();
	}//function

	function kuldLevel($from, $fromname, $recipient, $subject, $body_, $footer="", $header="", $mode = 1 ){
		$body = $header;
		//$body .= $this->elvalaszto;
		$body .= $body_;
		if($footer){
			$body.= $this->elvalaszto;
			$body.=$footer;
		}
		$mode = 1;
		JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
	}

	function cron(){
		$cronfile = dirname(__FILE__)."/cron.php";
		if (file_exists( $cronfile )){
			include( $cronfile );
		}
		if( @$last_cron_date != date("Ymd") /*|| 1*/ ){
			$last_cron_date = date("Ymd");			
			$ret = true;
			echo "hello cronfile";
			//$this->manageRecallMails(); 
		}else{
			$ret = false;
		}
		$Fnm = $cronfile;
		$inF = fopen($Fnm,"w"); 
		fwrite($inF,'<?php $last_cron_date='.$last_cron_date.';?>');
		fclose($inF); 
		return $ret;
	}
	
	function getUserByAdId($id){
		$q="select u.* from #__pad as p inner join #__users as u on p.user_id = u.id 
		where p.id = {$id} ";
		$this->_db->setQuery($q);
		$row = $this->_db->loadObject();
		return $row;
	}

	function sendOkAd($id){
		$from = $this->from;
		$fromname = $this->fromname;
		$user = $this->getUserByAdId($id);
		$recipient[]="mariomedia@mediacenter.hu";
		$recipient[]=$user->email;
		$subject = "Hirdetés jóváhagyva";
		$body = $this->getOkMailBody();
		$mode = 1;
		//print_r($user); exit;		
		if($user->usertype <> "Super Administrator" || 1 ){
			JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
			//print_r($user);	exit;
		}
	}

	function getAd($id){
		$q = "select * from #__pad where id = {$id}";
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}
	
	function sendRecallMail($ad){
		$from = $this->from;
		$fromname = $this->fromname;
		$user = $this->getUserByAdId($ad->id);
		$recipient[]="mariomedia@mediacenter.hu";
		$recipient[]="{$user->email}";
		$subject = "Hirdetése hamarosan lejár";
		$body = $this->getRecallMailBody($ad);
		$mode = 1;
		if($user->usertype <> "Super Administrator" || 1 ){
			JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
			//print_r($user);	exit;
		}
	}

	function sendAskLongerMail($ad){
		$user = $this->getUserByAdId($ad->id);	
		$from = $user->email;
		$fromname = $ad->owner;
		$recipient[]="mariomedia@mediacenter.hu";
		//$recipient[]="info@ingatlankinalo.hu";
		$subject = "Hosszabítási kérelem";
		$body = $this->getAskLongerMailBody($ad);
		$mode = 1;
		JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
	}

	function manageRecallMails(){
		//$days = $this->adLifeTime-$this->recallDay;
		//$adLifeTime
		$q = "select * from #__pad where 
		CURDATE()-okdate > (lifetime-{$this->recallDay}) and 
		published = 2 and 
		recallmaildate = '0000-00-00'";
		
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//echo $this->_db->getQuery()."<br />";
		//echo $this->_db->getErrorMsg()."<br />";		
		//print_r($rows);exit;
		foreach($rows as $ad){
			$o_="";
			$o_->id = $ad->id;
			$o_->recallmaildate = date("Y-m-d", time() );
			$this->_db->updateObject("#__pad", $o_, "id"); //recall date berírva
			$this->sendRecallMail($ad);
		//exit;
		}
	}

	function sendRefusedMail($id){
		$from = $this->from;
		$fromname = $this->fromname;
		$user = $this->getUserByAdId($id);
		$ad = $this->getAd($id);
		//print_r($user);
		//exit;
		$recipient[]="mariomedia@mediacenter.hu";
		$recipient[]="{$user->email}";
		$subject = "Hirdetése törölve/visszautasítva";
		$body = $this->getRefusedMailBody($ad);
		$mode = 1;
		if($user->usertype <> "Super Administrator" ){
			JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
			//print_r($recipient); exit;
		}
	}

	function sendNewAd($id){
		$from = $this->from;
		$fromname = $this->fromname;
		$user = $this->getUserByAdId($id);
		//print_r($user);
		//exit;
		$recipient[]="mariomedia@mediacenter.hu";
		$recipient[]="info@ingatlankinalo.hu";
		$subject = "A rendszerbe új hirdetés érkezett";
		$body = $this->getNewAdMailBody($id);
		$mode = 1;
		JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode);
	}

	function getNewAdMailBody($id){
		ob_start();
		?>
		<p>Tisztelt Admin</p>
		A rendszerbe új hirdetés érkezett, a hirdetés azonosítója: <strong>{id}</strong><br />
		A hirdetés további információit az admin felületen tekintheti meg<br />
  		<?php
		$ret = ob_get_contents();
		$ret = str_replace("{id}", $id, $ret);
		ob_end_clean();
		return $ret;
	}

	function getOkMailBody(){
		ob_start();
		?>
		<p>Tisztelt {owner}</p>
Hirdetését jóváhagytuk. Köszönjük, hogy igénybevette szolgáltatásunkat.<br />
		Hirdetés szövege:<br />
		{descr}<br /><br />
		<p>Tel: 06-70/325-1796.
		  <br />
		  Mail: info@ingatlankinalo.hu
          <br />
          Üdvözlettel:<br />
		  Ingatlankinalo.hu
       </p>
  		<?php
		$ret = ob_get_contents();
		$ret = str_replace("{owner}", $this->_data->owner, $ret);
		$ret = str_replace("{descr}", $this->_data->descr, $ret);		
		ob_end_clean();
		return $ret;
	}

	function getRefusedMailBody($obj){
		ob_start();
		?>
		<p>Tisztelt {owner}</p>
		Hirdetése törölve/elutasítva lett a rendszerből<br />
		Hirdetés szövege:<br />
		{descr}
		<p>Tel: 06-70/325-1796.
		  <br />
		  Mail: info@ingatlankinalo.hu
          <br />
          Üdvözlettel:<br />
		  Ingatlankinalo.hu
       </p>
  		<?php
		$ret = ob_get_contents();
		$ret = str_replace("{owner}", $obj->owner, $ret);
		$ret = str_replace("{descr}", $obj->descr, $ret);		
		ob_end_clean();
		return $ret;
	}
	function getAskLongerMailBody($obj){
		ob_start();
		?>
		Tisztelt Admin,<br /><br />
		<p>A következő hirdetésre hosszabítási kérelem érkezett:</p>
		Azonosító: {id}<br />
		Feladó: {owner}<br />
		Hirdetés szövege:<br />
		{descr}<br /><br />
		További információ az adminisztrációs felületen érhető el.
  		<?php
		$ret = ob_get_contents();
		$ret = str_replace("{id}", $obj->id, $ret);
		$ret = str_replace("{owner}", $obj->owner, $ret);
		$ret = str_replace("{descr}", $obj->descr, $ret);		
		ob_end_clean();
		return $ret;
	}

	function getRecallMailBody($obj){
		ob_start();
		?>
		<p>Tisztelt {owner}</p>
		hirdetése hamarosan lejár............<br />
		Hirdetés szövege:<br />
		{descr}<br /><br />
		Hirdetésének meghosszabítását az alábbi linkre kattintva kérheti:<br />
		{hosszabitlink}<br />
		<p>Tel: 06-70/325-1796.
		  <br />
		  Mail: info@ingatlankinalo.hu
          <br />
          Üdvözlettel:<br />
		  Ingatlankinalo.hu
       </p>
  		<?php
		$ret = ob_get_contents();
		$link = "<a href=\"http://www.ingatlankinalo.hu/index.php?option=com_pad&controller=show&id={$obj->id}&task=asklonger&Itemid=\">hirdetésem meghosszabítását kérem</a>";
		$ret = str_replace("{hosszabitlink}", $link, $ret);
		$ret = str_replace("{owner}", $obj->owner, $ret);
		$ret = str_replace("{descr}", $obj->descr, $ret);		
		ob_end_clean();
		return $ret;
	}

}