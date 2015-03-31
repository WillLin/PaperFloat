<?php
 
// Include 'Composer' autoloader.
include 'vendor/autoload.php';
 
// Parse pdf file and build necessary objects.
function parsePDF($document) {
	$parser = new \Smalot\PdfParser\Parser();
	$pdf = $parser->parseFile($document);
	
	$text = $pdf->getText();
	return $text;
}


?>