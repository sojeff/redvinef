<?php

$mailMerge = new Zend_Service_LiveDocx_MailMerge();
 
$mailMerge->setUsername('sokno')
          ->setPassword('trigger1');
 
$mailMerge->setLocalTemplate('document.doc');
 
// necessary as of LiveDocx 1.2
$mailMerge->assign('dummyFieldName', 'dummyFieldValue');
 
$mailMerge->createDocument();
 
$document = $mailMerge->retrieveDocument('pdf');
 
file_put_contents('document.pdf', $document);
 
unset($mailMerge);

// https://api.livedocx.com/2.1/mailmerge.asmx?wsdl

?>