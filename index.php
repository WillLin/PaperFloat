<html>
	<head>
		<title>PaperFloat</title>

		<!-- Stylesheet -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		
		<!-- jQuery -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

		<!-- AutoComplete -->
		<script src="scripts/autocomplete.js"></script>

	</head>
	
	<body>

		<div id="largelogo">
			<a href="./"><img src="images/paperfloat.png" alt="PaperFloat" /></a>
		</div>


		<div style="height:25px;">
		</div>

		<div id="inputarea">
			<form action="search.php" method="get" >
				<input id="searchterm" class="ui-widget" type="text" name="searchterm" placeholder="Enter search term" size="35" >
				<br />
				Search by
				<input type="radio" name="parameter" value="keyword" checked>Keyword 
				<input type="radio" name="parameter" value="author">Author 
				<br />
				<br />
				Limit search to <input id="searchlimit" type="number" name="limit" placeholder="number"> articles
				<br />
				<div class="floatright">
					<input id="submitbutton" class="purplebutton marginleft10" type="submit" value="Submit">
				</div>
			</form>
		</div>

	</body>
	
</html>