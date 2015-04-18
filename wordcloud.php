<?php
	//start session
	session_start();
	include 'processor.php';

	// if search term parameter is present in URL, else get from cache
	if (isset($_GET['searchterm'])) {
		$term = $_GET['searchterm'];
	}
	else {
		$term = $_SESSION['searchTerm'];
	}
		
	// if limit parameter is present in URL, else get from cache
	if (isset($_GET['limit'])) {
		$limit = $_GET['limit'];
	}
	else {
		$limit = $_SESSION['limit'];
	}

	// if search parameter is present in URL, else get from cache
	if (isset($_GET['parameter'])) {
		$searchParameter = $_GET['parameter'];
	}
	else {
		$searchParameter = $_SESSION['searchParameter'];
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

		<!-- AutoComplete -->
		<!-- <script src="scripts/autocomplete.js"></script> -->
		
		<!-- Facebook Share  -->
		<!--<script>
			(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=410610055735538&version=v2.0";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>-->
	</head>

	<body>
		<!-- <div id="fb-root"></div> -->
		
		<div id="logo">
			<a href="./"><img src="images/paperfloat_sm.png" alt="PaperFloat" /></a>
		</div>

		<div id="progressbar" style="padding-top: 50px;">
			<?php 

				if ($term != $_SESSION['searchTerm'] || $limit != $_SESSION['limit'] || $searchParameter!= $_SESSION['searchParameter']) {

					$_SESSION['searchTerm'] = $term;
					
					$_SESSION['limit'] = $limit;

					$_SESSION['searchParameter'] = $searchParameter;

					$totalProcesses = $limit*4; // total processes=(paper limit*2)*2  //two papers each source(2), with two processes each paper (download and parse)
					$_SESSION['totalProcesses'] = $totalProcesses;

					startProcessor();
				}

			?>
		</div>

		<div class="center" id="wordcloud">
			<?php

				/*
				$artistsArray = array();
				$artistsArray = $_SESSION['artistsArray'];

				for($i=0; $i < count($artistsArray); $i++) {
					$stopwords = strtolower($artistsArray[$i]);
					$stopwords .= ",";
				}
				*/

				$stopwords = "able,about,above,according,accordingly,across,actually,after,afterwards,again,against,ain't,all,allow,allows,almost,alone,along,already,also,although,always,am,among,amongst,an,and,another,any,anybody,anyhow,anyone,anything,anyway,anyways,anywhere,apart,appear,appreciate,appropriate,are,aren't,around,as,aside,ask,asking,associated,at,available,away,awfully,be,became,because,become,becomes,becoming,been,before,beforehand,behind,being,believe,below,beside,besides,best,better,between,beyond,both,brief,but,by,c'mon,c's,came,can,can't,cannot,cant,cause,causes,certain,certainly,changes,clearly,co,com,come,comes,concerning,consequently,consider,considering,contain,containing,contains,corresponding,could,couldn't,course,currently,definitely,described,despite,did,didn't,different,do,does,doesn't,doing,don't,done,down,downwards,during,each,edu,eg,eight,either,else,elsewhere,enough,entirely,especially,et,etc,even,ever,every,everybody,everyone,everything,everywhere,ex,exactly,example,except,far,few,fifth,first,five,followed,following,follows,for,former,formerly,forth,four,from,further,furthermore,get,gets,getting,given,gives,go,goes,going,gone,got,gotten,greetings,had,hadn't,happens,hardly,has,hasn't,have,haven't,having,he,he's,hello,help,hence,her,here,here's,hereafter,hereby,herein,hereupon,hers,herself,hi,him,himself,his,hither,hopefully,how,howbeit,however,i'd,i'll,i'm,i've,ie,if,ignored,immediate,in,inasmuch,inc,indeed,indicate,indicated,indicates,inner,insofar,instead,into,inward,is,isn't,it,it'd,it'll,it's,its,itself,just,keep,keeps,kept,know,knows,known,last,lately,later,latter,latterly,least,less,lest,let,let's,like,liked,likely,little,look,looking,looks,ltd,mainly,many,may,maybe,me,mean,meanwhile,merely,might,more,moreover,most,mostly,much,must,my,myself,name,namely,nd,near,nearly,necessary,need,needs,neither,never,nevertheless,new,next,nine,no,nobody,non,none,noone,nor,normally,not,nothing,novel,now,nowhere,obviously,of,off,often,oh,ok,okay,old,on,once,one,ones,only,onto,or,other,others,otherwise,ought,our,ours,ourselves,out,outside,over,overall,own,particular,particularly,per,perhaps,placed,please,plus,possible,presumably,probably,provides,que,quite,qv,rather,rd,re,really,reasonably,regarding,regardless,regards,relatively,respectively,right,said,same,saw,say,saying,says,second,secondly,see,seeing,seem,seemed,seeming,seems,seen,self,selves,sensible,sent,serious,seriously,seven,several,shall,she,should,shouldn't,since,six,so,some,somebody,somehow,someone,something,sometime,sometimes,somewhat,somewhere,soon,sorry,specified,specify,specifying,still,sub,such,sup,sure,t's,take,taken,tell,tends,th,than,thank,thanks,thanx,that,that's,thats,the,their,theirs,them,themselves,then,thence,there,there's,thereafter,thereby,therefore,therein,theres,thereupon,these,they,they'd,they'll,they're,they've,think,third,this,thorough,thoroughly,those,though,three,through,throughout,thru,thus,to,together,too,took,toward,towards,tried,tries,truly,try,trying,twice,two,un,under,unfortunately,unless,unlikely,until,unto,up,upon,us,use,used,useful,uses,using,usually,value,various,very,via,viz,vs,want,wants,was,wasn't,way,we,we'd,we'll,we're,we've,welcome,well,went,were,weren't,what,what's,whatever,when,whence,whenever,where,where's,whereafter,whereas,whereby,wherein,whereupon,wherever,whether,which,while,whither,who,who's,whoever,whole,whom,whose,why,will,willing,wish,with,within,without,won't,wonder,would,would've,wouldn't,yes,yet,you,you'd,you'll,you're,you've,your,yours,yourself,yourselves,zero,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,,,,";
				$stopwords .= ",0,1,2,3,4,5,6,7,8,9,10,introduction";

				function filter_stopwords($words, $stopwords) {
					for($i = 0; $i < count($words); $i++) {
						if (!in_array(strtolower($words[$i]), $stopwords, TRUE)) {
							$filtered_words [] = $words[$i];
						}
					}

					return $filtered_words;
				}
				function word_freq($words) {
					$frequency_list = array();
					foreach ($words as $pos => $word) {
							if (!in_array(strtolower($word), $stopwords, TRUE)) {
								$filtered_words[$pos] = $word;
							}
					}
				}

				function multiexplode ($delimiters,$string) {
    
		    $ready = str_replace($delimiters, $delimiters[0], $string);
		    $launch = explode($delimiters[0], $ready);
		    return  $launch;
				}
				
				function word_cloud($words, $div_size = 600) {
					$cloud = "<div style=\"width: {$div_size}px\">";
					$fmax = 160; /* Maximum font size */
					$fmin = 8; /* Minimum font size */
					$counted = array_count_values($words);
					
					// arsort($counted);
					// var_dump($counted);
					$tmin = min($counted); /* Frequency lower-bound */
					$tmax = max($counted); /* Frequency upper-bound */
					$count = 0;
					$i = 0;
					foreach ($counted as $word => $frequency) {
							if ($frequency > $tmin) {
								$count += 1;
								
								
								$font_size = ceil(( $fmax * ($frequency - $tmin) ) / (( $tmax - $tmin )));
							
								/*$r = $g = 0; */
								$r = ($frequency * $tmax * $count) % 250;
								$g = floor( 50 * ($frequency / $tmax));
								$b = floor( 255 * ($frequency / $tmax) );
								$color = '#' . sprintf('%02s', dechex($r)) . sprintf('%02s', dechex($g)) . sprintf('%02s', dechex($b));
							} 
							else {

								$font_size = 10;
								$r = mt_rand ( 0 , 255 );
								$g = mt_rand ( 0 , 255 );
								$b = mt_rand ( 0 , 255 );
								$color=  "rgb($r,$g,$b)";
							}
							if ($font_size >= $fmin) {
								if ($i < 250) {
									$cloud .= "<a href=\"word.php?word=$word\" style=\"font-size: {$font_size}px; color: $color;\">$word</a> ";
									$i++;
								}
							}
						}
						$cloud .= "</div>";
						return $cloud;
				}

				$stopwords = explode(',', $stopwords);
				
				$paperArray = array();
				$paperArray = $_SESSION['paperArray'];

				$wordsArray = array();

				
				foreach ($paperArray as $element) {	
					$exploded = multiexplode(array(' ', '-', '.', '[', ']', '(', ')'), $element);
					$wordsArray = array_merge($wordsArray, $exploded);
				}
				

				$filtered = filter_stopwords($wordsArray, $stopwords);
				

				echo word_cloud($filtered, 600);
			?>
		 </div>

		<div style="height:50px;">
			&nbsp;
		</div>

		<div id="inputarea">
			<form action="wordcloud.php" method="get" >
				<input id="searchterm" class="ui-widget" type="text" name="searchterm" placeholder="Enter search term" size="35" >
				<br />
				Search by
				<input type="radio" name="parameter" value="keyword" checked>Keyword(s) 
				<input type="radio" name="parameter" value="author">Author 
				<br />
				<br />
				Limit search to <input id="searchlimit" type="number" name="limit" value="10"> articles
				<br />
				<div class="floatright">
					<!-- <div class="fb-share-button sharebutton" data-layout="button"></div> -->
					<input id="submitbutton" class="purplebutton marginleft10" type="submit" value="Submit">
				</div>
			</form>
		</div>

		<script>
			$( "#progressbar" ).hide();
		</script>

	</body>
	
</html>