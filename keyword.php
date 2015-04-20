<?php

include('paper.php');
include 'paper_word.php';
session_start();
function countFreq($word)
{

	$songs = array();
	$songs = $_SESSION['songsArray'];

	$data_array = array();




	$lists=new Songlist($word);
	$lists->setList($songs);
	$songlist=$lists->getFrequencyList();
	$artistList = $lists->getArtistMap();
	foreach ($songlist as $songName => $frequency) {
		$artistName = $artistList[$songName];
		$formattedArtistName = str_replace(' ', '+', $artistName);
		$formattedSongName = str_replace(' ', '+', $songName);
		$url = "songlyrics.php?artist=";
		$url .= $formattedArtistName;
		$url .= "&amp;word=";
		$url .= $word;
		$url .= "&amp;song=";
		$url .= $formattedSongName;
		echo "<p><a href=\"$url\" style=\"color:white;\">";
		echo $songName . " ................................ "  . $frequency  . ' ';
		echo '</a></p>';
	}
}



function printTable() {

	$keyword = $_GET['word'];

	$allPapers = array();
	$allPapers = $_SESSION['filtered_list_complete'];

	//var_dump($allPapers);


	$alreadyAdded = array();
	$alreadyAddedCount = 0;

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

	$allPapers = array_unique($allPapers, SORT_REGULAR);

	foreach ($allPapers as $key => $paper) {
		if (strcmp($paper->getWord(), $keyword) == 0) {
			$title = $paper->getTitle();
			$conference = $paper->getConference();
			$link = $paper->getLink();
			echo "<tr><td><input type='checkbox'></td><td>$title</td><td>";
			foreach ($paper->getAuthors() as $key => $value) {
				echo $value . ' ';
			}
			echo "</td><td>$conference</td><td><a href='$link'>PDF</a></td></tr>";
		}
	}
	// foreach
	//echo "<tr><td>&nbsp;</td><td>Title</td><td>Author</td><td>Conference</td><td>Link</td></tr>"
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
						<th>Title</th> 
						<th>Author</th>
						<th>Conference</th>
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