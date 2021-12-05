<?php
	$url = $_POST["urlSubmit"];	//URL submitted by user
	$urlArray = array();	//Array where URLs are stored
	
	array_push($urlArray, $url);	//Adds submitted URL to array, urlArray
	
	echo "<p>URL submitted: $url</p>";	//Prints submitted URL
	
	//validUrl($url);	//Checks if a URL is valid
	
	crawl_page($url, 2);
	
	
	
	//Prints out $urlArray to page
	foreach($urlArray as $value) {
		echo "<p>$value</p>";
	}
	
	$testURL = 'https://en.wikipedia.org/wiki/Main_Page';
	
	$urlHtml = file_get_contents($testURL);
	$dom = new domDocument();
	$dom->loadHTML($url);
	
	$divs = $dom->getElementsByTagName('div');
	
	//echo "$divs";
	
	
	/*
	foreach($urlArray as $url) {
		echo "<p><strong>$url</strong></p>";	//Prints each URL link in bold
		
		
			//echo "test";
			$site1 = file_get_contents($url);
			$site2 = strtolower($site1);
			$site3 = strip_tags($site2);
			$site4 = str_word_count($site3, 1);
			$site5 = array();
			$site5 = array_count_values($site4);
			
			//echo "<p>$word : $num</p>";
			
			$slicedArray = array_slice($site5, 0, 20);	//Array sliced starting from first element, and outputs the 20 after it
			foreach($slicedArray as $word => $num) {
				echo "<p>$word : $num</p>";
			}
		
	}
	*/
	
	
	/*
	$site = "https://en.wikipedia.org/wiki/Adventures_of_Huckleberry_Finn";
	
	$site1 = file_get_contents($site);		//Gets the content of the url
	$site2 = strtolower($site1);			//Converts all letters to lowercase
	$site3 = strip_tags($site2);			//Removes all html tags from the letters
	$site4 = str_word_count($site3, 1);		//Creates an array of words 
	$site5 = array();						
	$site5 = array_count_values($site4);	//Counts how many time each word was repeated and places it in an array
	*/
	
	//print_r($site5);
	
	//print_r(array_count_values(str_word_count(strip_tags(strtolower($site1)), 1)));	//Everything above condensed in 1 line
	
	/*	//Prints each word and amount of time it appears in a given url
	foreach($site5 as $word => $num) {
		echo "<p>$word : $num</p>";
	}
	*/
	
	
	
	
	//Checks if URL is valid (code from PHP lecture)
	function validUrl($website) {
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
			echo "<p>invalid url</p>";
		}
		else {
			echo "<p>valid url</p>";
		}
	}
	
	//validUrl($url);
	
	//Crawls through a submitted URL to find more URLs (code from PHP lecture/Stackoverflow)
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


