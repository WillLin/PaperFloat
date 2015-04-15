<?php
include 'lib/pdfparser.php';
include 'simple_html_dom_parser.php';
include 'paper.php';

//session_start();

function hack($url, $i)
{

	$cookie = 'cookies.txt';
	$timeout = 30;

	//$url = "http://ieeexplore.ieee.org/ielx7/6597024/6623991/06624021.pdf";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Encoding: none','Content-Type: application/pdf'));

	//header('Content-type: application/pdf');
	$result = curl_exec($ch);
	curl_close($ch);

	//$text = parsePDF($result);	
	//echo $text;

	$file = "Downloads1/" . $i . 'file.pdf';
	file_put_contents($file, $result);
	//$text = parsePDF($file);
	//return $text;

}

function getBy($author)
{
	$author = strtolower($author);
	$auhtor = preg_replace("/[\s_]/", "_", $author);
	$query = "http://dl.acm.org/results.cfm?h=&query=" . $author;
	$html = file_get_html($query);

	$localauthors = array();
	$titles = array();
	$conferences = array();
	$links = array();
	$texts = array();
	$paperArray = array();

	// authors
	foreach ($html->find('tr td div.authors') as $e)
	{	

		$localauthor = trim($e->plaintext);
		$localauthors[] = $localauthor;
	}

	foreach ($html->find('tr td a.medium-text') as $e)
	{
		$titles[] = $e->plaintext;
	}

	$i = 0;
	
	foreach($html->find('tr td tr td tr td a') as $e)
	{	
		$comparable = $e->plaintext;
		$comparable = substr($comparable, 81, 84);

		if ($comparable == "PDF")
		{	
			$link = "http://dl.acm.org/" . $e->href;
			$links[$i] = $link;
			hack($link, $i);
			$i++;
		}

		
		
	}

	foreach($html->find('tr td div.addinfo') as $e)
	{
		$conferences[] = $e->plaintext;
	}

	for ($i = 0; $i < count($localauthors); $i++)
	{		

		if (!empty($links[$i]))
		{
			$paper = new Paper();
			$paper->setAuthor($localauthors[$i]);
			$paper->setTitle($titles[$i]);
			$paper->setConference($conferences[$i]);
			$paper->setLink($links[$i]);
			$paperArray[] = $paper;
		}
	}

return $paperArray;

}

function parseACM($paperArray)
{
	for ($i = 0; $i < count($paperArray); $i++)
	{
		$string = "Downloads1/" . $i . "file.pdf";
		if (filesize($string) <= 64886)
		{
			unset($paperArray[$i]);
		}

		else
		{
			$text = parsePDF($string);
			$paperArray[$i]->setText($text);
		}
		
	}

	return $paperArray;

}

$paperArray = getBy("dijkstra");
$final = parseACM($paperArray);

var_dump($final);



?>