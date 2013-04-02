<?php
header("Content-Type: application/pdf")
$path = parse_url($_GET['pdfFile'], PHP_URL_PATH);
$filetodisplay = parse_url($_GET['pdfFile'], PHP_URL_QUERY);
$filetodisplay = strtok($filetodisplay,'=');
$filetodisplay = strtok('=');
echo $filetodisplay.'<br>';
echo file_get_contents($path.$filetodisplay);
?>