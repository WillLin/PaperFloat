<?php
include 'lib/composer/parser.php';
include 'simple_html_dom_parser.php';

 function createFileFromString($stringWithFile){
    header('Content-Description: File Transfer');
    header("Content-Type: application/pdf");
    header('Content-Disposition: attachment; filename=document.pdf');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    flush();
    file_put_contents("document.pdf", base64_decode($stringWithFile));
    readfile("document.pdf");
    exit();
}

function downloadFile ($url, $path) {

  $newfname = $path;
  $file = fopen ($url, "rb");
  if ($file) {
    $newf = fopen ($newfname, "wb");

    if ($newf)
    while(!feof($file)) {
      fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
    }
  }

  if ($file) {
    fclose($file);
  }

  if ($newf) {
    fclose($newf);
  }

 }

function pdfVersion($filename)
{
    $fp = @fopen($filename, 'rb');
    if (!$fp) {
        return 0;
    }
    /* Reset file pointer to the start */
    fseek($fp, 0);
    /* Read 20 bytes from the start of the PDF */
    preg_match('/\d\.\d/',fread($fp,20),$match);
    fclose($fp);
    if (isset($match[0])) {
        return $match[0];
    } else {
        return 0;
    }
} 

function getPapersByKeyWord($link)
{
	// assume you plugged something in there search box
	$html = file_get_html($link);

	foreach ($html->find('tr td a.medium-text') as $e)
	{
		// echo $e->plaintext . "<br>" ;
		// echo $e->href . "<br>";
		$papers[] = array("name" => $e->plaintext, "link" => "http://dl.acm.org/" . $e->href);
	}

	return $papers;
	// kinda works

}

function getOtherLinks ($link)
{
	$html = file_get_html($link);
	$count = 0;

	foreach ($html-> find ("tr td a") as $e)
	{
		//echo $e->plaintext . "<br>" ;

		if ( strpos($e->plaintext, "next") !== false)
		{

				$nextLink = "http://dl.acm.org/" . $e->href;
				break;
		}

		$count++;

	}

	return $link;
}

function parseIEEE()
{


}

function hack($url)
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
	echo "<br>";
	$result = curl_exec($ch);
	curl_close($ch);

	//$text = parsePDF($result);
	//echo $text;
	$file = 'file.pdf';
	file_put_contents($file, $result);
	$text = parsePDF($file);
	echo $text;

}

function serachIEEEKeyWord($keyword)
{
	$keyword = strtolower($keyword);
	$keyword = preg_replace("/[\s_]/", "_", $keyword);
	$query = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?querytext=" . $keyword;
	$xml = simplexml_load_file($query);
	//print_r($xml);
	$count = count($xml->document);
	echo $count . "<br>";

	$arrayOfLinks = array();
	for ($i = 0; $i < $count; $i++)
	{
		$arrayOfLinks[$i] = (string)$xml->document[$i]->mdurl;
	}
	print_r($arrayOfLinks);

	echo "<br>";
	$link1 = $arrayOfLinks[1];
	$html = file_get_html($link1);

	echo "here: " . $link1 . "<br>";
	foreach ($html -> find("div.button-set span a.html") as $e)
	{
		$link = $e->href;

		$link = "http://ieeexplore.ieee.org" . $link;
		echo $link;
	}

	$html = file_get_html($link);

	foreach($html->find("p") as $e)
	{
		echo $e->plaintext . "<br><br>";
	}
	
}


function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}



$html = file_get_contents_curl("http://ieeexplore.ieee.org/xpl/articleDetails.jsp?tp=&arnumber=389912&contentType=Conference+Publications");


$doc = new DOMDocument();
@$doc->loadHTML($html);
$metas = $doc->getElementsByTagName('meta');

for ($i = 0; $i < $metas->length; $i++)
{
	$meta = $metas->item($i);
	if ($meta->getAttribute('name') == "citation_pdf_url")
	{
		$pdfLink = $meta->getAttribute('content');
		break;
	}
}


echo $pdfLink . "<br>"; 
$firstPart = substr($pdfLink, 0, 30);
$secondPart = substr($pdfLink, 30, strlen($pdfLink));
$pdfLink = $firstPart . "x"  . $secondPart;
echo $pdfLink;

hack($pdfLink);

//serachIEEEKeyWord("fuzzy logic");

?>