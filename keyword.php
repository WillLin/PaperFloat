<?php

include('paper.php');
include 'paper_word.php';
session_start();

function printTable() {

	$keyword = $_GET['word'];

	$allPapers = array();
	$allPapers = $_SESSION['filtered_list_complete'];

	//var_dump($allPapers);


	//$alreadyAdded = array();
	//$alreadyAddedCount = 0;

	/*
	foreach ($allPapers as $paper => $what) {
		if (strtolower($what->getWord()) == strtolower($keyword)) {
			$match = FALSE;
			foreach ($alreadyAdded as $paper2 => $i) {
			 	if ($what->getTitle() == $i->getTitle()) {
			 		//$match = TRUE;
			 	}

			 	if ($match == FALSE) {
			 		$alreadyAdded[$alreadyAddedCount] = $what;
			 		$alreadyAddedCount++;
			 		echo 'adding';
			 	}
			}
		}
	}
	
	foreach ($alreadyAdded as $key => $value) {
		echo $value->getTitle();
	}
	*/

	function cmp($a, $b) {
		if ($a->getFrequency() == $b->getFrequency()) {
	        return 0;
	    }
	    return ($a->getFrequency() > $b->getFrequency()) ? -1 : 1;
	}



	//$allPapers = array_unique($allPapers, SORT_REGULAR);
	uasort($allPapers, 'cmp');
	$allPapers = array_unique($allPapers, SORT_REGULAR);

	foreach ($allPapers as $key => $paper) {
		if (strcmp($paper->getWord(), $keyword) == 0) {
			$frequency = $paper->getFrequency();
			$title = $paper->getTitle();
			$conference = $paper->getConference();
			$conference = trim($conference);
			$link = $paper->getLink();

			// separate words in title to create separate links
			$arrayOfTitleWords = array();
			$title = preg_replace("/[^a-zA-Z0-9\-]+/", " ", $title);
			$arrayOfTitleWords = explode(' ', $title);

			// print a row with paper's info
			echo "<tr><td><input type=\"checkbox\"></td><td>$frequency</td><td>";
			foreach ($arrayOfTitleWords as $key => $value) {
				echo "<a href=\"wordcloud.php?searchterm=$value&parameter=keyword\">$value</a>" . ' ';
			}
			echo "</td><td>";
			foreach ($paper->getAuthors() as $key => $value) {
				echo "<a href=\"wordcloud.php?searchterm=$value&parameter=author\">$value</a>" . ' / ';
			}
			echo "</td><td><a href=\"wordcloud.php?searchterm=$conference&parameter=publication\">$conference</a></td>";
			echo "<td><a href=\"$link\" target=\"_blank\">PDF</a></td></tr>";
		}
	}
}


?>


<html>
	<head>
		<title>PaperFloat</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css">
	</head>

	<body>
		<div id="logo">
			<a href="./"><img src="images/paperfloat_sm.png" alt="PaperFloat" /></a>
		</div>

		<div id="word_content">
			<h2 id="searchword"><?php echo $_GET['word']; ?></h2>
			<div id="paperlist">
				<table>
					<tr>
						<th>&nbsp;</th>
						<th>Freq.</th>
						<th>Title</th> 
						<th>Author</th>
						<th>Conference/Publication</th>
						<th>Link</th>
					</tr>
					<?php 
						// echo table here
						printTable();
					?>
				</table>
			</div>

				
			<div class="spacer">
				&nbsp;
			</div>

			<form action="wordcloud.php" method="get">
				<input id="backbutton" class="purplebutton" type="submit" value="Back to cloud">
			</form>
		</div>

		</body>

</html>