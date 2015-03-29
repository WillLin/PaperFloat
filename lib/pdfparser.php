<?php
 
// Include 'Composer' autoloader.
include 'vendor/autoload.php';
 
// Parse pdf file and build necessary objects; returns parsed text
function parsePDF($document) {
	$parser = new \Smalot\PdfParser\Parser();
	$pdf = $parser->parseFile($document);
	
	$text = $pdf->getText();
	return $text;
}


?>