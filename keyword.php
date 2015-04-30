<?php

include('paper.php');
include 'paper_word.php';
session_start();

function printTable() {

	$keyword = $_GET['word'];

	$allPapers = array();
	$allPapers = $_SESSION['filtered_list_complete'];

	function cmp($a, $b) {
		if ($a->getFrequency() == $b->getFrequency()) {
	        return 0;
	    }
	    return ($a->getFrequency() > $b->getFrequency()) ? -1 : 1;
	}

	uasort($allPapers, 'cmp');
	$allPapers = array_unique($allPapers, SORT_REGULAR);

	$papersWithKeyword = array();
	$rowID = 0;

	foreach ($allPapers as $key => $paper) {
		if (strcmp($paper->getWord(), $keyword) == 0) {
			$papersWithKeyword[] = $paper;

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
			echo "<tr><td><input type=\"checkbox\" name=\"paper[]\" value=\"$rowID\"></td><td>$frequency</td><td>";
			foreach ($arrayOfTitleWords as $key => $value) {
				echo "<a href=\"wordcloud.php?searchterm=$value&parameter=keyword\">$value</a>" . ' ';
			}
			echo "</td><td>";
			foreach ($paper->getAuthors() as $key => $value) {
				echo "<a href=\"wordcloud.php?searchterm=$value&parameter=author\">$value</a>" . ' / ';
			}
			echo "</td><td><a href=\"wordcloud.php?searchterm=$conference&parameter=publication\">$conference</a></td>";
			echo "<td><a href=\"$link\" target=\"_blank\">PDF</a></td></tr>";

			$rowID++;
		}
	}

	$_SESSION['papersWithKeyword'] = $papersWithKeyword;
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
				<form action="wordcloud.php" method="get">
					<input type="hidden" name="subset" value="true">
					<table>
						<tr>
							<th>&nbsp;</th>
							<th>Freq.</th>
							<th>Title</th> 
							<th>Author</th>
							<th>Publication/Conference</th>
							<th>Link</th>
						</tr>
						<?php 
							// echo table here
							printTable();
						?>
					</table>
					<div>
						&nbsp;
					</div>
					<input id="subsetbutton" class="purplebutton" type="submit" value="Subset Search">
				</form>
			</div>

				
			<div class="spacer">
				&nbsp;
			</div>

			<form action="pdf_creator.php" method="get" target="_blank">
				<input id="pdfbutton" class="purplebutton" type="submit" value="Export list as PDF">
			</form>

			<form action="wordcloud.php" method="get">
				<input id="backbutton" class="purplebutton" type="submit" value="Back to cloud">
			</form>
		</div>

		</body>

</html>