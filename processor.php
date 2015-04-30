<?php
include 'lib/pdfparser.php';
include 'simple_html_dom_parser.php';
include 'paper.php';

require_once 'progressbar.php';

//start session
//session_start();



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


/* IEEE Functions */


function parseIEEE($papers)
{			
	for ($i = 0; $i < count($papers); $i++)
	{
		$string = "Downloads/" . $i . "file.pdf";

		$arrayOfPaperText = array();
		if (filesize($string) <= 64886)
		{	
			$arrayOfPaperText[$i] = ' ';
			//continue;
		}

		else
		{
			// !!!! CATCH PARSE EXCEPTION HERE
			try {
				$text = parsePDF($string);
				$text = preg_replace("/[^a-zA-Z0-9]+/", " ", $text);
			}
			catch (Exception $e) {
				//echo "There was a problem reading a paper from IEEE. The file returned is invalid. Skipping...<br />";
				$text = ' ';
				displayError('There was a problem reading a paper from IEEE. The file returned is invalid. Skipping...');
			}
			//$text = preg_replace("/[^a-zA-Z0-9]+/", " ", $text);
			$arrayOfPaperText[$i] = $text;
			$papers[$i]->setText($text);
		}

		// update progress bar
		$p = $_SESSION['progressbar'];

		$totalProcesses = $_SESSION['totalProcesses'];

		$processesDone = $_SESSION['processesDone'];
		$processesDone++;
		$_SESSION['processesDone'] = $processesDone;

		$p->setProgressBarProgress(($processesDone*100)/$totalProcesses);
	}

	// push paper array to session
	$_SESSION['IEEEPaperArray'] = $papers;
 
	return $arrayOfPaperText;
	
}

function getFileIEEE($url, $i)
{

	$cookie = 'cookiesIEEE.txt';
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

	$file = "Downloads/" . $i . 'file.pdf';
	file_put_contents($file, $result);
	//$text = parsePDF($file);
	//return $text;


	// update progress bar
	$p = $_SESSION['progressbar'];

	$totalProcesses = $_SESSION['totalProcesses'];

	$processesDone = $_SESSION['processesDone'];
	$processesDone++;
	$_SESSION['processesDone'] = $processesDone;

	$p->setProgressBarProgress(($processesDone*100)/$totalProcesses);


}


function searchIEEEKeyWord($keyword)
{

	$keyword = strtolower($keyword);
	$keyword = preg_replace("/[\s_]/", "_", $keyword);
	$query = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?querytext=" . $keyword;
	$xml = simplexml_load_file($query);
	$count = count($xml->document);
	$arrayOfLinks = array();
	for ($i = 0; $i < $count; $i++)
	{
		$arrayOfLinks[$i] = (string)$xml->document[$i]->mdurl;
	}
	//print_r($arrayOfLinks);
	$limit = $_SESSION['limit'];
	
	$paperArray = array();
	for ($i = 0; $i < $count; $i++)
	{
		if ($i < $limit) {
			$html = file_get_contents_curl($arrayOfLinks[$i]);
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			$metas = $doc->getElementsByTagName('meta');
			$paper = new Paper();
			for ($j = 0; $j < $metas->length; $j++)
			{
				$meta = $metas->item($j);
				$pdfLink = ' ';
				

				if ($meta->getAttribute('name') == "citation_conference")
				{
					$paper->setConference($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_author")
				{
					$paper->setAuthor($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_title")
				{
					$paper->setTitle($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_pdf_url")
				{	
					$pdfLink = $meta->getAttribute('content');
					break;
				}
			}
			

			$firstPart = substr($pdfLink, 0, 30);

			$secondPart = substr($pdfLink, 30, strlen($pdfLink));
			$pdfLink = $firstPart . "x"  . $secondPart;

			$paper->setLink($pdfLink);

			getFileIEEE($pdfLink, $i);

			$paperArray[] = $paper;
		}
	}

	return $paperArray;
}

/*
function searchIEEEKeyWord($keyword)
{

	$keyword = strtolower($keyword);
	$keyword = preg_replace("/[\s_]/", "_", $keyword);
	$query = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?querytext=" . $keyword;
	$xml = simplexml_load_file($query);
	//print_r($xml);
	$count = count($xml->document);
	//echo $count . "<br>";

	$arrayOfLinks = array();
	for ($i = 0; $i < $count; $i++)
	{
		$arrayOfLinks[$i] = (string)$xml->document[$i]->mdurl;
	}
	//print_r($arrayOfLinks);

	$limit = $_SESSION['limit'];
	
	for ($i = 0; $i < count($arrayOfLinks); $i++)
	{
		if ($i < $limit) {
			$html = file_get_contents_curl($arrayOfLinks[$i]);
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			$metas = $doc->getElementsByTagName('meta');

			for ($j = 0; $j < $metas->length; $j++)
			{
				$meta = $metas->item($j);
				$pdfLink = ' ';
				if ($meta->getAttribute('name') == "citation_pdf_url")
				{
					$pdfLink = $meta->getAttribute('content');
					break;
				}
			}

			$firstPart = substr($pdfLink, 0, 30);
			$secondPart = substr($pdfLink, 30, strlen($pdfLink));
			$pdfLink = $firstPart . "x"  . $secondPart;

			//echo $pdfLink . "<br>";
			getFileIEEE($pdfLink, $i) . "<br> <br>";
	
		}

	}

	if ($i >= $limit) {
		$i = $limit;
	}

	return $i;
}
*/

function searchIEEEAuthor($author)
{
	$author = strtolower($author);
	$author = preg_replace("/[\s_]/", "_", $author);
	$query = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=" . $author;
	$xml = simplexml_load_file($query);
	$count = count($xml->document);
	$arrayOfLinks = array();
	for ($i = 0; $i < $count; $i++)
	{
		$arrayOfLinks[$i] = (string)$xml->document[$i]->mdurl;
	}
	//print_r($arrayOfLinks);
	$limit = $_SESSION['limit'];
	
	$paperArray = array();
	for ($i = 0; $i < $count; $i++)
	{
		if ($i < $limit) {
			$html = file_get_contents_curl($arrayOfLinks[$i]);
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			$metas = $doc->getElementsByTagName('meta');
			$paper = new Paper();
			for ($j = 0; $j < $metas->length; $j++)
			{
				$meta = $metas->item($j);
				$pdfLink = ' ';
				

				if ($meta->getAttribute('name') == "citation_conference")
				{
					$paper->setConference($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_author")
				{
					$paper->setAuthor($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_title")
				{
					$paper->setTitle($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_pdf_url")
				{		
					$pdfLink = $meta->getAttribute('content');
					break;
				}
			}
			

			$firstPart = substr($pdfLink, 0, 30);

			$secondPart = substr($pdfLink, 30, strlen($pdfLink));
			$pdfLink = $firstPart . "x"  . $secondPart;

			$paper->setLink($pdfLink);

			getFileIEEE($pdfLink, $i);

			$paperArray[] = $paper;
		}	
	}

	return $paperArray;

}

function searchIEEEByPublication($publication)
{
	$publication = strtolower($publication);
	$publication = preg_replace("/[\s_]/", "_", $publication);
	$query = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?jn=" . $publication;
	$xml = simplexml_load_file($query);
	$count = count($xml->document);
	$arrayOfLinks = array();
	for ($i = 0; $i < $count; $i++)
	{
		$arrayOfLinks[$i] = (string)$xml->document[$i]->mdurl;
	}
	//print_r($arrayOfLinks);
	$limit = $_SESSION['limit'];
	
	$paperArray = array();
	for ($i = 0; $i < $count; $i++)
	{
		if ($i < $limit) {
			$html = file_get_contents_curl($arrayOfLinks[$i]);
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			$metas = $doc->getElementsByTagName('meta');
			$paper = new Paper();
			for ($j = 0; $j < $metas->length; $j++)
			{
				$meta = $metas->item($j);
				$pdfLink = ' ';
				

				if ($meta->getAttribute('name') == "citation_conference")
				{
					$paper->setConference($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_author")
				{
					$paper->setAuthor($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_title")
				{
					$paper->setTitle($meta->getAttribute('content'));
				}

				if ($meta->getAttribute('name') == "citation_pdf_url")
				{		
					$pdfLink = $meta->getAttribute('content');
					break;
				}
			}
			

			$firstPart = substr($pdfLink, 0, 30);

			$secondPart = substr($pdfLink, 30, strlen($pdfLink));
			$pdfLink = $firstPart . "x"  . $secondPart;

			$paper->setLink($pdfLink);

			getFileIEEE($pdfLink, $i);

			$paperArray[] = $paper;
		}	
	}

	return $paperArray;

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

/* End IEEE Functions */

/* ACM Functions */
function getFileACM($url, $i)
{

	$cookie = 'cookiesACM.txt';
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

	// update progress bar
	$p = $_SESSION['progressbar'];

	$totalProcesses = $_SESSION['totalProcesses'];

	$processesDone = $_SESSION['processesDone'];
	$processesDone++;
	$_SESSION['processesDone'] = $processesDone;

	$p->setProgressBarProgress(($processesDone*100)/$totalProcesses);

}

function getACMBy($author)
{
	$author = strtolower($author);
	$author = preg_replace("/[\s_]/", "_", $author);
	$query = "http://dl.acm.org/results.cfm?h=&query=" . $author;
	$html = file_get_html($query);

	$localauthors = array();
	$titles = array();
	$conferences = array();
	$links = array();
	$texts = array();
	$paperArray = array();

	$limit = $_SESSION['limit'];
	$papersAdded = 0;

	// authors
	foreach ($html->find('tr td div.authors') as $e)
	{	

		$localauthor = trim($e->plaintext);
		$paperAuthors = explode(',', $localauthor);
		$paperAuthors = array_map('trim', $paperAuthors);
		$localauthors[] = $paperAuthors;
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

		if ($comparable == "PDF" && $i < $limit)
		{	
			$link = "http://dl.acm.org/" . $e->href;
			$links[$i] = $link;
			getFileACM($link, $i);
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
			foreach ($localauthors[$i] as $key => $value) {
				$paper->setAuthor($value);
			}
			//$paper->setAuthor($localauthors[$i]);
			$paper->setTitle($titles[$i]);
			$paper->setConference($conferences[$i]);
			$paper->setLink($links[$i]);
			$paperArray[] = $paper;
		}
	}

return $paperArray;

}


function searchACMByPublication($publication) {
	$publication = strtolower($publication);
	$publication = preg_replace("/[\s_]/", "_", $publication);
	$query = "http://dl.acm.org/results.cfm?adv=1&COLL=DL&DL=ACM&Go.x=0&Go.y=0&termzone=all&allofem=&anyofem=&noneofem=&peoplezone=Name&people=&peoplehow=and&keyword=&keywordhow=AND&affil=&affilhow=AND&pubin=";
	$query .= $publication;
	$query .= "&pubinhow=and&pubby=&pubbyhow=OR&since_year=&before_year=&pubashow=OR&sponsor=&sponsorhow=AND&confdate=&confdatehow=OR&confloc=&conflochow=OR&isbnhow=OR&isbn=&doi=&ccs=&subj=";
	$html = file_get_html($query);

	$localauthors = array();
	$titles = array();
	$conferences = array();
	$links = array();
	$texts = array();
	$paperArray = array();

	$limit = $_SESSION['limit'];
	$papersAdded = 0;

	// authors
	foreach ($html->find('tr td div.authors') as $e) {	

		$localauthor = trim($e->plaintext);
		$paperAuthors = explode(',', $localauthor);
		$paperAuthors = array_map('trim', $paperAuthors);
		$localauthors[] = $paperAuthors;
	}

	foreach ($html->find('tr td a.medium-text') as $e) {
		$titles[] = $e->plaintext;
	}

	$i = 0;
	
	foreach($html->find('tr td tr td tr td a') as $e) {	
		$comparable = $e->plaintext;
		$comparable = substr($comparable, 81, 84);

		if ($comparable == "PDF" && $i < $limit) {	
			$link = "http://dl.acm.org/" . $e->href;
			$links[$i] = $link;
			getFileACM($link, $i);
			$i++;
		}	
	}

	foreach($html->find('tr td div.addinfo') as $e) {
		$conferences[] = $e->plaintext;
	}

	for ($i = 0; $i < count($localauthors); $i++) {		

		if (!empty($links[$i])) {
			$paper = new Paper();
			foreach ($localauthors[$i] as $key => $value) {
				$paper->setAuthor($value);
			}
			//$paper->setAuthor($localauthors[$i]);
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
	$arrayOfPaperText = array();
	for ($i = 0; $i < count($paperArray); $i++)
	{
		$string = "Downloads1/" . $i . "file.pdf";
		if (filesize($string) <= 64886)
		{
			unset($paperArray[$i]);
		}

		else
		{
			try {
				$text = parsePDF($string);
				$text = preg_replace("/[^a-zA-Z0-9]+/", " ", $text);
			}
			catch (Exception $e) {
				//echo "There was a problem reading a paper from ACM. The file returned is invalid. Skipping...<br />";
				$text = ' ';
				displayError('There was a problem reading a paper from ACM. The file returned is invalid. Skipping...');
			}
			//$text = preg_replace("/[^a-zA-Z0-9]+/", " ", $text);
			$paperArray[$i]->setText($text);
			$arrayOfPaperText[$i] = $text;
		}


		// update progress bar
		$p = $_SESSION['progressbar'];

		$totalProcesses = $_SESSION['totalProcesses'];

		$processesDone = $_SESSION['processesDone'];
		$processesDone++;
		$_SESSION['processesDone'] = $processesDone;

		$p->setProgressBarProgress(($processesDone*100)/$totalProcesses);
		
	}

	// push paperarray to session
	$_SESSION['ACMPaperArray'] = $paperArray;

	return $arrayOfPaperText;

}


/* End of ACM Functions */



function displayError($message) {
	echo '<script>$( "#errorbox" ).show(); $( "#errorbox" ).append("' . $message . '<br />");</script>';
}


function startProcessor() {

	$processesDone = 0;
	$_SESSION['processesDone'] = $processesDone;

	// setup and display progress bar
	$p = new ProgressBar();
	//echo '<div id="progressbar" style="background-color:gray;height:90%;font-family:Verdana;color:white;padding-top: 50px;">';
	//echo '<div id="logo" style="text-align:center;margin-bottom:50px;">
	//		<img src="images/paperfloat_sm.png" alt="PaperFloat" />
	//	</div>';
	echo '<p style="text-align:center;">Our monkeys are &quot;reading&quot; the papers...</p>';
	echo '<div style="width:40%;margin:auto;">';
	$p->render();
	echo '</div>';
	//echo '</div>';
	$_SESSION['progressbar'] = $p;



	$searchTerm = $_SESSION['searchTerm'];

	if ($_SESSION['searchParameter'] == 'author'){
		$IEEEPaperArray = searchIEEEAuthor($searchTerm);
	}
	else if ($_SESSION['searchParameter'] == 'publication') {
		$IEEEPaperArray = searchIEEEByPublication($searchTerm);
	}
	else {
		$IEEEPaperArray = searchIEEEKeyWord($searchTerm);
	}

	$arrayOfIEEEResearchPapers = parseIEEE($IEEEPaperArray);


	if ($_SESSION['searchParameter'] == 'author'){
		$ACMPaperArray = getACMBy($searchTerm);
	}
	else if ($_SESSION['searchParameter'] == 'publication') {
		$ACMPaperArray = searchACMByPublication($searchTerm);
	}
	else {
		$ACMPaperArray = getACMBy($searchTerm);
	}

	$arrayOfACMResearchPapers = parseACM($ACMPaperArray);

	$arrayOfAllText = array();
	$arrayOfAllText = array_merge($arrayOfIEEEResearchPapers, $arrayOfACMResearchPapers);

	$_SESSION['textArray'] = $arrayOfAllText;

	// merge the arrays of papers from IEEE and ACM
	$ACMPaperArray = $_SESSION['ACMPaperArray'];
	$IEEEPaperArray = $_SESSION['IEEEPaperArray'];
	$AllPaperArray = array_merge($ACMPaperArray, $IEEEPaperArray);
	$_SESSION['AllPaperArray'] = $AllPaperArray;

	$p->setProgressBarProgress(100);


}

?>