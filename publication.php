<?php
	// includes
	include 'processor.php';
	include 'paper_word.php';
	include 'subsetsearch.php';
	//start session
	session_start();

	
	function printPublicationTable() {

		// if search term parameter is present in URL, else error
		if (isset($_GET['searchterm'])) {
			$term = $_GET['searchterm'];
		}
		else {
			//error
		}

		$paperArray = searchIEEEByPublication($term);
		// skipping ACM due to bad API support

		foreach ($paperArray as $key => $paper) {

			$title = $paper->getTitle();
			$conference = $paper->getConference();
			$conference = trim($conference);

			
			// print a row with paper's info
			echo "<tr><td>" . $title . "</td>";
			echo "<td>";
			foreach ($paper->getAuthors() as $key => $value) {
				echo $value . ' / ';
			}
			echo "</td>";
			echo "<td>$conference</td>";
			echo "</tr>";

		}
	}

?>
<html>
	<head>
		<title>PaperFloat</title>

		<!-- Stylesheet -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">

		<!-- jQuery -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

	</head>

	<body>
		<!-- <div id="fb-root"></div> -->
		
		<div id="logo">
			<a href="./"><img src="images/paperfloat_sm.png" alt="PaperFloat" /></a>
		</div>

		<div id="errorbox" style="padding: 20px; background-color: salmon; width: 50%; margin: auto; margin-top: 20px; margin-bottom: 20px;">
			Error:
			<br />
		</div>
		<script>
			$( "#errorbox" ).hide();
		</script>

		<div id="word_content">
			<h2 id="searchword"><?php echo $_GET['searchterm']; ?></h2>
			<div id="paperlist">
				<table>
					<tr>
						<th>Title</th> 
						<th>Author</th>
						<th>Publication/Conference</th>
					</tr>
					<?php 
						// echo table here
						printPublicationTable();
					?>
				</table>
				<div>
					&nbsp;
				</div>
			</div>

				
			<div class="spacer">
				&nbsp;
			</div>

			<form action="keyword.php" method="get">
				<?php
					$fromKeyword = $_GET['from'];
					echo "<input type=\"hidden\" name=\"word\" value=\"$fromKeyword\">";
				?>
				
				<input id="wordbutton" class="purplebutton" type="submit" value="Back to keyword">
			</form>

			<form action="wordcloud.php" method="get">
				<input id="backbutton" class="purplebutton" type="submit" value="Back to cloud">
			</form>

			<div class="spacer">
				&nbsp;
			</div>
		</div>


	</body>
	
</html>