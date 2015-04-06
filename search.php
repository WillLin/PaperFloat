<?php
session_start();


function updateProgress() {

	updateProgressTest();


}


?>


<html>
	<head>
		<title>Search in Progress... | PaperFloat</title>

		<!-- Stylesheet -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		
		<!-- jQuery -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

		<script>
		
			$(function() {
				$( "#progressbar" ).progressbar({
					value: 0
				});
			});
			/*
			$(function( $ ) {
				$.fn.setProgressBar = function() {
					$( "#progressbar" ).progressbar({
						value: 30
					});
				};
			})(jQuery);
			*/
			var counter = 0;

			function updateProgressTest() {
				counter++;
				updateProgress(counter);
			}

			function updateProgress(value) {
				$( "#progressvalue" ).html("<span>" + value + "</span>");
				$(function() {
					$( "#progressbar" ).progressbar({
						value: value
					});
				});
			}
		</script>

	</head>
	
	<body>

		<div id="largelogo">
			<a href="./"><img src="images/paperfloat.png" alt="PaperFloat" /></a>
		</div>

		<!-- Add param to php session -->

		<div style="height:25px;">
		</div>

		<div id="progress">
			<p onclick="updateProgressTest()">Our monkeys are &quot;reading&quot; the papers...</p>
			<div id="progressbar"></div>
			<div id="progresstext"><span id="progressvalue">0</span>% complete</div>
		</div>

		<?php
			//$counter = 0;

		/*
        for ($i = 0; $i <= 100; $i++) {
				echo '<script type="text/javascript">'
					,'updateProgress('.$i.');'
					,'</script>'
				;
		}
		*/


		$term = $_GET['searchterm'];

		echo $term;

		$_SESSION['searchTerm'] = $term;

		$limit = $_GET['limit'];
		$_SESSION['limit'] = $limit;

		?>

	</body>
	
</html>