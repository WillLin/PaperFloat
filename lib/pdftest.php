<?php
 
// Include pdf parser
include 'pdfparser.php';
 
// Parse a sample pdf file
	$text = parsePDF('document.pdf');

	echo $text;


?>