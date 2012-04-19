<?php
/**
 * @version $Id: header.php 789 2009-01-26 15:56:03Z elkuku $
 * @package    bikeadmin
 * @subpackage
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     Trifid Kft {@link http://trifid.hu}
 * @author     Created on 05-May-09
 */

//--No direct access
defined( '_JEXEC' ) or die( '=;)' );
//$file = jrequest::getvar("filename", ""); 
@$sess =jsession::getinstance();
$file = $sess->get("filename__"); 
//die($file);
//die($this->localfile);

 

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
?>
