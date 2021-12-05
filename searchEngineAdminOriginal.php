<html>
	<head>
		<title>Phase 3 Search Engine Admin</title>
	
		<!--The W3 Schools CSS framework for responsiveness-->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
		
		<link rel="stylesheet" href="style.css"> <!--This links to the style sheet -->
		<script src="siteScripts.js"></script>   <!--This links to the javascript code-->
		
	
		<!--Loads jquery library -->
		<script
			src="https://code.jquery.com/jquery-3.3.1.min.js"
			integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			crossorigin="anonymous">
		</script>
		
		<!-- In order to display a php variable, it must be ran before the html
		<p>test: <?php echo $test ?></p>
		-->
	
	</head>
	
	<body>
	<h1>Phase 3 Search Engine Admin Page</h1>
		
		<div id="nBar"></div>
			
			<!--Script to load navigation bar using jQuery into above div-->
			<script>
				$("#nBar").load("topnavbar.html");
			</script>
			<br>
	</body>
</html>

<?php
	$url = $_POST["urlSubmit"];	//URL submitted by user
	$urlArray = array();	//Array where URLs are stored
	
	array_push($urlArray, $url);	//Adds submitted URL to array, urlArray
	
	echo "<p><strong>URL submitted: $url</strong></p>";	//Prints submitted URL
	
	validUrl($url);	//Checks if a URL is valid
	
	crawl_page($url, 2);	//Crawls through the submitted URL for other URLs
	
	/*
	//Prints out each URL from $urlArray onto the page
	foreach($urlArray as $singleUrl) {
		echo "<p>$singleUrl</p>";
	}
	*/
	
	//Goes through each URL in $urlArray
	foreach(array_slice($urlArray,0, 3) as $singleUrl) { 		//Goes through 3 URLs
	//foreach($urlArray as $singleUrl) {						//Goes through every URL
		echo "<p><strong>$singleUrl</strong></p>";
		
		//Gets contents of a single url
		$urlHtml = file_get_contents($singleUrl);
		
		//Creates a new dom object and loads the url
		$dom = new domDocument();
		$dom->loadHTML($urlHtml);
		
		//Creats an empty array where each word will be stored
		//urlArray is already created and filled
		$wordArray = array();
		urlTitle;
		urlDesc;
		urlLastModified;
		timeSearchStarted;
		timeSearchTook;
		
		$urlTitle = preg_match($singleUrl, $urlHtml, $matches) ? $matches[1] : null;
		echo "Title: " . $urlTitle . "<br>";
		
		$tags = get_meta_tags($singleUrl);
		$urlDesc = $tags['description'];
		echo "Description: " . $urlDesc . "<br>";
		
		
		//Finds all words in a div element, places them in an array, merges with previous array, repeats for next div
		//foreach(array_slice($dom->getElementsByTagName('div'), 0, 100) as $text) {
		
		$i = 0;
		foreach($dom->getElementsByTagName('div') as $text) {
			if($i == 20) {	//Goes through 20 div elements
				break;
			}
			$tempWordArray = str_word_count($text->nodeValue, 1);	//Temporary array
			
			$wordArray = array_merge($wordArray, $tempWordArray); //Merges main array with temp array
			$i++;
		}
		
		
		//Prints out every word in the $wordArray
		foreach($wordArray as $word) {
			//echo "$word<br>";
		}
		
		//Counts the value of how many times each word was repeated in $wordArray and stores in $wordCount
		$wordCount = array_count_values($wordArray);
		
	
		//Prints each word and number of times repeated
		foreach(array_slice($wordCount,0, 100) as $word => $num) {		//Prints first 100 words
		//foreach($wordCount as $word => $num) {						//Prints all words
			echo "<p>$word : $num</p>";
		}
	
	}
	
	
	
	
	/*
	Code used from
	https://stackoverflow.com/questions/3485673/counting-words-on-a-html-web-page-using-php
	*/
	/*
	//Iterates through each url in $urlArray
	foreach($urlArray as $url) {
		echo "<p><strong>$url</strong></p>";	//Prints each URL link in bold
		
		$site1 = file_get_contents($url);		//Gets the content of the url
		$site2 = strtolower($site1);			//Converts all letters to lowercase
		$site3 = strip_tags($site2);			//Removes all html tags from the letters
		$site4 = str_word_count($site3, 1);		//Creates an array of words
		$site5 = array();						//Creates a new empty array
		$site5 = array_count_values($site4);	//Counts how many time each word was repeated and places it in an array
		
		//Array sliced starting from first element, and outputs the 20 after it
		$slicedArray = array_slice($site5, 0, 20);	
		
		//Prints the word and number of times it appears for current URL
		foreach($slicedArray as $word => $num) {
			echo "<p>$word : $num</p>";
		}
	}
	*/
	
	
	//print_r(array_count_values(str_word_count(strip_tags(strtolower($site1)), 1)));	//Everything above condensed in 1 line
	
	/*	//Prints each word and amount of time it appears in a given url
	foreach($site5 as $word => $num) {
		echo "<p>$word : $num</p>";
	}
	*/
	
	
	
	
	//Checks if URL is valid (code from PHP lecture)
	function validUrl($website) {
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
			echo "<p><strong>URL Submitted is an Invalid URL.</strong></p>";
		}
		else {
			echo "<p><strong>URL Submitted is a Valid URL.</strong></p>";
		}
	}
	
	//Crawls through a submitted URL to find more URLs (code from PHP lecture/Stackoverflow)
	//Has a depth of 2
	function crawl_page($url, $depth = 2) {
	    static $seen = array();
	    if (isset($seen[$url]) || $depth === 0) {
	        return;
	    }
	
	    $seen[$url] = true;
	
	    $dom = new DOMDocument('1.0');
	    @$dom->loadHTMLFile($url);
	
	    $anchors = $dom->getElementsByTagName('a');
	    foreach ($anchors as $element) {
	        $href = $element->getAttribute('href');
	        if (0 !== strpos($href, 'http')) {
	            $path = '/' . ltrim($href, '/');
	            if (extension_loaded('http')) {
	                $href = http_build_url($url, array('path' => $path));
	            } else {
	                $parts = parse_url($url);
	                $href = $parts['scheme'] . '://';
	                if (isset($parts['user']) && isset($parts['pass'])) {
	                    $href .= $parts['user'] . ':' . $parts['pass'] . '@';
	                }
	                $href .= $parts['host'];
	                if (isset($parts['port'])) {
	                    $href .= ':' . $parts['port'];
	                }
	                $href .= dirname($parts['path'], 1).$path;
	                
	                //Required to use the $urlArray global variable
	                global $urlArray;	//Added
	                
	                //Adds each href to the array, urlArray
	                array_push($urlArray, $href);	//Added
	                
	                //echo "<p>$href</p>";	//Added
	            }
	        }
	        crawl_page($href, $depth - 1);
	    }
	    //echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;	//Removed
	}
?>


