<?php
defined( '_JEXEC' ) or die( '=;)' );
$file = urldecode(jrequest::getVar("pdfFile")) ;
$filename = urldecode(jrequest::getVar("filename")).'.pdf';
$forras = "admin/media/termekfajlok/";
$safefilename = $forras.urldecode($file);
//echo "<br />".$safefilename;

jimport('joomla.filesystem.file');
//$safefilename = JFile::makeSafe($filename);

if ( file_exists( $safefilename ) /*|| 1*/ ) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream'); 
    header('Content-Disposition: attachment; filename='.basename( str_replace(" ","_", JFile::makeSafe( $filename ) ) ) );
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($safefilename));
    ob_clean();
    flush();
    readfile( $safefilename );
    exit;
}


?>
