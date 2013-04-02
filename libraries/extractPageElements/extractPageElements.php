<?php

require_once("Net/URL2.php");
require_once("OpenGraph.php");

define('MIN_IMAGE_WIDTH', 50);
define('MIN_IMAGE_HEIGHT', 50);

//global $MIN_ARTICLE_TITLE_MATCH_PERCENTAGE;
//global $WHITE_LIST;
//global $BLACK_LIST;
//global $SOKNO_SNIPPET_ID;
//global $SOKNO_SNIPPET_TEMP;

$MIN_ARTICLE_TITLE_MATCH_PERCENTAGE = 45;

$WHITE_LIST = array("body", "p", "a", "strong", "em", "i", "b", "ol", "ul", "dl", "li", "span", "dt", "dd", "h1", "h2", "h3");
$BLACK_LIST = array("script", "nav", "style", "img");

$SOKNO_SNIPPET_ID = "SOKNO_BOOKMARKLET_SNIPPET_SPAN";
$SOKNO_SNIPPET_TEMP = "SOKNO_BOOKMARKLET_SNIPPET_TEMP_SPAN";

function extractPageElements($URI) {
	// variables for statistics; used purely for testing
	global $images_with_dimensions_count, $images_without_dimensions_count;
	
	// object for resolving/normalizing URLs and relative URLs;
	$url_obj = new Net_URL2($URI);
	
	// array to be filled and returned with this page's values;
	$_values = array();
	
	// OGP branch
	$graph = OpenGraph::fetch($URI);
	if ($graph) {
		$graph_array = iterator_to_array($graph, true);
		// guarantee that $_values['url'] will never be empty, which could happen on pages that have a single OGP image tag
		// or something like that, but don't have URL, title nor description OGP metadata filled-in (way out-of-spec, but 
		// not uncommon);
		if (!empty($graph_array['url'])) {
			$_values['url'] = $graph_array['url'];
		}
		else {
			$_values['url'] = $url_obj->getNormalizedURL();
		}
		$_values['referrer_url'] = $url_obj->getNormalizedURL();
		$_values['titles'] = array($graph_array['title']);
		if(isset($graph_array['description']))
			$_values['descriptions'] = array($graph_array['description']);
		else
			$_values['descriptions'] = array($graph_array['title']);
		$_values['images'] = array();
		if (!empty($graph_array['image'])) {
			// make sure that we have an absolute URL for the image;
			$image_url_obj = new Net_URL2($graph_array['image']);
			if (!$image_url_obj->isAbsolute()) {
				$image_url_obj = $url_obj->resolve($image_url_obj);
			}
			array_push($_values['images'], $image_url_obj->getNormalizedURL());
		}
	}

	// MANUAL branch
	else {
		// get the DOM
		$doc = new DOMDocument();
		@$doc->loadHTML(file_get_contents($URI));	
		// set the _values array to be returned; trying OGP failed, so $_values['url'] and $_values['referrer_url'] will be
		// the same in this case, since we haven't been provided with any canonical URL metadata to use for $_values['url'];
		$_values['url'] = $url_obj->getNormalizedURL();
		$_values['referrer_url'] = $url_obj->getNormalizedURL();
		$_values['titles'] = extractPageTitles($doc);
		$_values['descriptions'] = extractPageDescriptions($doc);
	}
	
	// UNIVERSAL branch: done with both OGP and MANUAL extraction
	// do manual image and content extraction on all pages, *** whether OGP is present or not ***
	$doc = new DOMDocument();
	@$doc->loadHTML(file_get_contents($URI));
	// make sure we don't clobber the image specified by OGP if it's there
	if (isset($_values['images'])) {
		$all_images = array_merge($_values['images'], extractPageImages($doc, $url_obj));
	}
	else {
		$all_images = extractPageImages($doc, $url_obj);
	}
	$_values['images'] = $all_images;
	$_values['content'] = extractPageContent($doc, $url_obj);
	$_values['iframe_needed'] = false;
	if (!$_values['content']) {
		$_values['iframe_needed'] = true;
	}
	
	return $_values;
}

function extractPageTitles($doc) {
	$tags = $doc->getElementsByTagName('title');
	$titles = array();
	foreach ($tags as $tag) {
		$innerHTML = '';
		$children = $tag->childNodes;
		foreach ($children as $child) {
			$innerHTML .= $child->ownerDocument->saveXML($child);
		}
		array_push($titles, $innerHTML);
	}
	return $titles;
}

function extractPageDescriptions($doc) {
	$tags = $doc->getElementsByTagName('meta');
	$descriptions = array();
	foreach ($tags as $tag) {
		if (($tag->hasAttribute('property') && strcasecmp($tag->getAttribute('property'),'description') == 0) ||
			($tag->hasAttribute('http-equiv') && strcasecmp($tag->getAttribute('http-equiv'), 'description') == 0) ||
			($tag->hasAttribute('name') && strcasecmp($tag->getAttribute('name'), 'description') == 0)) {
			if ($tag->hasAttribute('content')) {
				array_push($descriptions, $tag->getAttribute('content'));
			}
		}
	}
	return $descriptions;
}
	
function extractPageImages($doc, $url_obj) {
	global $images_with_dimensions_count, $images_without_dimensions_count;
	$tags = $doc->getElementsByTagName('img');
	$images = array();
	foreach ($tags as $tag) {
		$image_url = false;
		// normalize the image src URL, fix relative URLs;
		if ($tag->hasAttribute('src')) {
			$image_url_obj = new Net_URL2($tag->getAttribute('src'));
			if (!$image_url_obj->isAbsolute()) {
				$image_url_obj = $url_obj->resolve($image_url_obj);
			}
			$image_url = $image_url_obj->getNormalizedURL();
		}
			
		// testing to determine whether this image is big enough to be important;
		if ($image_url) {
			$image_height = 0;
			$image_width = 0;
			// if the image tag has height & width attributes, use those for determining whether it's big enough to keep;
			if ($tag->hasAttribute('height') && $tag->hasAttribute('width')) {
				$images_with_dimensions_count++;
				// get rid of 'px' on the end of the image's height attribute if present;
				$image_height_str = $tag->getAttribute('height');
				if (substr($image_height_str, -2) == "px") {
					$image_height_str = substr($image_height_str, 0, strlen($image_height_str) - 2);
				}
				// get rid of the '%' on the end of the image's height attribute if present;
				else if (substr($image_height_str, -1) == "%") {
					$image_height_str = substr($image_height_str, 0, strlen($image_height_str) - 1);
				}
				$image_height = intval($image_height_str);
					
				// get rid of 'px' on the end of the image's width attribute if present;
				$image_width_str = $tag->getAttribute('width');
				if (substr($image_width_str, -2) == "px") {
					$image_width_str = substr($image_width_str, 0, strlen($image_width_str) - 2);	
				}
				// get rid of the '%' on the end of the image's width attribute if present;
				else if (substr($image_width_str, -1) == "%") {
					$image_width_str = substr($image_width_str, 0, strlen($image_width_str) - 1);
				}
				$image_width = intval($image_width_str);
			}
			// this image doesn't have height/width attributes, so grab it and determine its dimensions manually;
			else {
				$images_without_dimensions_count++;
				if($image_size = getimagesize(str_replace(' ', "%20", $image_url)))
					{
					$image_width = intval($image_size[0]);
					$image_height = intval($image_size[1]);
					}
				else
					{
					$image_width = 0;
					$image_height = 0;
					}
			}
			
			// if this image is large enough, add it to the list, ***along with its area in a 2-element array***;
			if ($image_height > MIN_IMAGE_HEIGHT && $image_width > MIN_IMAGE_WIDTH) {
				array_push($images, array($image_url, ($image_height * $image_width)));
			}
		}
	}
	// sort the images array in order of descending image area, then strip the image areas out of the images array, and 
	// make it a single-dimensional array containing only the image urls;	
	usort($images, function($a, $b) {
		if ($a[1] == $b[1]) { return 0; }
		return ($a[1] > $b[1]) ? -1 : 1;
	});
	for ($i = 0; $i < sizeof($images); $i++) {
		$images[$i] = $images[$i][0];
	}
	return $images;
}

function extractPageContent($doc, $url_obj) {
		
	return true;
	
	global $MIN_ARTICLE_TITLE_MATCH_PERCENTAGE;
	global $SOKNO_SNIPPET_ID;
	global $SOKNO_SNIPPET_SPAN_ID;
	global $SOKNO_SNIPPET_TEMP;
	$linkElems = $doc->getElementsByTagName("link");
	$pageTitle = $doc->getElementsByTagName("title")->item(0)->nodeValue;
	$feedLinkElem = false;
	// use the first RSS-feed <link /> element we find
	foreach ( $linkElems as $le ) {
		if ($le->hasAttribute("type") && (
			(strcasecmp($le->getAttribute("type"), "application/rss+xml") == 0) ||
			(strcasecmp($le->getAttribute("type"), "application/atom+xml") == 0) ||
			(strcasecmp($le->getAttribute("type"), "application/xml") == 0) ||
			(strcasecmp($le->getAttribute("type"), "text/rss+xml") == 0) ||
			(strcasecmp($le->getAttribute("type"), "text/xml") == 0)))
		{
			$feedLinkElem = $le;
			break;
		}
	}
	// does this page have a feed? if not, return false, which tells the bookmarklet to use an
	// iframe instead
	if (!$feedLinkElem) {
		return false;
	}
	
	// this page has a feed, so extract the feed URL from its <link ... /> element and grab that feed
	$feedUrl = $url_obj->resolve($feedLinkElem->getAttribute("href"));
	$feedSrc = file_get_contents($feedUrl);
	$feedXml = new SimpleXmlElement($feedSrc);
	
	// figure out the most likely matching RSS-feed item, based on comparing the <title> element on the 
	// HTML page and the <title> element inside the RSS-item
	$titleMatches = array();
	$index = 0;
	foreach( $feedXml->channel->item as $i ) {
		foreach( $i->title as $t ) {
			$nt = array($index, similar_text($pageTitle, $t, $percent));
			array_push($titleMatches, $nt);
			$index++;
		}
	}
	usort($titleMatches, function($a, $b) {
		if ($a[1] == $b[1]) { return 0; }
		return ($a[1] > $b[1]) ? -1 : 1;
	});
	// the best article to choose is now at the front of the array, so make sure it's a close-enough match
	// to the <title> element on the actual page; if it isn't, return false so that the bookmarklet uses
	// an iframe instead
	if ($titleMatches[0][1] < $MIN_ARTICLE_TITLE_MATCH_PERCENTAGE) {
		return false;
	}
	$feedItemIndex = $titleMatches[0][0];
	$feedItem = $feedXml->channel->item[$feedItemIndex];
		
	$doc = new DOMDocument();
	$snippet = "<span id=\"" . $SOKNO_SNIPPET_ID . "\">";
	// get the RSS-feed-item's <title ... /> element
	$snippet .= "<h1>" . $feedItem->title . "</h1>";
	$snippet .= "<br>";
	// get the RSS-feed-item's <description ... /> element
	$snippet .= $feedItem->description;
	$snippet .= "<br>";
	$snippet .= "</span>";
		
	// DOM to hold the unfixed version of the snippet to-be-processed
	$doc = new DOMDocument();
	@$doc->loadHTML($snippet);
	// new DOM that will get the fixed version of the snippet
	$newDoc = new DOMDocument();
	$newDoc->appendChild(cloneFragment($doc->getElementById($SOKNO_SNIPPET_SPAN_ID), $newDoc));
	
	// append the new fixed src to the $content string, after cleaning up the temp <span> elements as much as possible
	$content = $newDoc->saveHTML();
	$content = str_ireplace("<span class=\"" . $SOKNO_SNIPPET_TEMP . "\"></span>", "", $content);
	$content = str_ireplace("<span class=\"" . $SOKNO_SNIPPET_TEMP . "\">", "<span>", $content);
	// get rid of some common left-over empty HTML elements
	$content = str_ireplace("<a></a>", "", $content);
	$content = str_ireplace("<a> </a>", "", $content);
	$content = str_ireplace("<p></p>", "", $content);
	$content = str_ireplace("<p> </p>", "", $content);
		
	return $content;
}

function cloneFragment($elemIn, $newDoc) {
	global $WHITE_LIST, $BLACK_LIST;
    $elemOut = null;
	$evaluateChildren = true;
    if ($elemIn->nodeType == XML_ELEMENT_NODE) {
        if (in_array(strtolower($elemIn->nodeName), $WHITE_LIST)) {
			$elemOut = $newDoc->createElement( $elemIn->nodeName, '');
		}
		else {
			if (in_array(strtolower($elemIn->nodeName), $BLACK_LIST)) {
				$evaluateChildren = false;
			}
			$elemOut = $newDoc->createElement('span', '');
			$elemOut->setAttribute('class', $SOKNO_SNIPPET_TEMP);
        }

        if ($evaluateChildren && $elemIn->hasChildNodes()) {
            foreach ($elemIn->childNodes as $child) {
                $tmpNode = cloneFragment($child, $newDoc);
				$elemOut->appendChild( $newDoc->importNode($tmpNode, true) );
			}
        }
    }
    else {
        return $elemIn->cloneNode( true );
    }
    return $elemOut;
}

/* 
 * old extractPageContent() - replaced by RSS scraping for pages that have a feed, and an iFrame for those that don't
 *
function extractPageContent($doc, $url_obj) {
	$bodyElem = $doc->getElementsByTagName("body")->item(0);
	if ( null == $bodyElem ) {
		$bodyElem = $doc->firstChild;
		if ( null == $bodyElem ) {
			return "";
		}
	}
	$newDoc = new DOMDocument();
	$newBodyElem = cloneFragment( $bodyElem, $newDoc );
	$newDoc->appendChild( $newBodyElem );
	// strip out excess whitespace
	// $newDocStr = preg_replace('/\s\s+/', ' ', $newDoc->saveHTML());
	// append the new fixed src to the $content string, after cleaning up the temp <span> elements as much as possible
	$newDocStr = $newDoc->saveHTML();
	$newDocStr = str_ireplace("<span class=\"" . $SOKNO_SNIPPET_TEMP . "\"></span>", "", $newDocStr);
	$newDocStr = str_ireplace("<span class=\"" . $SOKNO_SNIPPET_TEMP . "\">", "<span>", $newDocStr);
	// get rid of some common left-over empty HTML elements
	$newDocStr = str_ireplace("<a></a>", "", $newDocStr);
	$newDocStr = str_ireplace("<a> </a>", "", $newDocStr);
	$newDocStr = str_ireplace("<p></p>", "", $newDocStr);
	$newDocStr = str_ireplace("<p> </p>", "", $newDocStr);
	
	return $newDocStr;
}
*/

// $results = extractPageElements("http://news.blogs.cnn.com/2012/10/29/hurricane-sandy-strengthens-to-85-mph/?hpt=hp_t1");
// print_r($results);

?>