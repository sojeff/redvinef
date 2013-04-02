<?php

require_once("Net/URL2.php");
require_once("OpenGraph.php");

define(MIN_IMAGE_WIDTH, 50);
define(MIN_IMAGE_HEIGHT, 50);

$WHITE_LIST = array("body", "p", "a", "img", "strong", "em", "i", "b", "ol", "ul", "dl", "li", "span", "dt", "dd", "h1", "h2", "h3");
$BLACK_LIST = array("script", "nav");

function extractPageElements($URI) {
	
	// variables for statistics;
	global $images_with_dimensions_count, $images_without_dimensions_count;
	
	$url_obj = new Net_URL2($URI);
	
	// array to be filled and returned with this page's values;
	$_values = array();
	
	// first, try to use OGP;
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
		$_values['descriptions'] = array($graph_array['description']);

		// make sure that we have an absolute URL for the image;
		$image_url = $graph_array['image'];
		$image_url_obj = new Net_URL2($image_url);
		if (!$image_url_obj->isAbsolute()) {
			$image_url_obj = $url_obj->resolve($image_url_obj);
		}
		$_values['images'] = array($image_url_obj->getNormalizedURL());
		$_values['content'] = extractPageContent($URI);
	}

	// resort to manual processing if OGP fails;
	else {
		//get the DOM
		$doc = new DOMDocument();
		@$doc->loadHTML(file_get_contents($URI));

		// get the title(s);
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

		// get the description(s);
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
		
		// get the image(s);
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
					$image_size = getimagesize(str_replace(' ', "%20", $image_url));
					$image_width = intval($image_size[0]);
					$image_height = intval($image_size[1]);
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
		
		// set the _values array to be returned; trying OGP failed, so $_values['url'] and $_values['referrer_url'] will be
		// the same in this case, since we haven't been provided with any canonical URL metadata to use for $_values['url'];
		$_values['url'] = $url_obj->getNormalizedURL();
		$_values['referrer_url'] = $url_obj->getNormalizedURL();
		$_values['titles'] = $titles;
		$_values['descriptions'] = $descriptions;
		$_values['images'] = $images;
		$_values['content'] = extractPageContent($URI);
	}
	
	return $_values;
}

function extractPageContent( $URI ) {
	$domDoc = new DOMDocument();
	$domDoc->loadHTML(file_get_contents($URI));
    $bodyElem = $domDoc->getElementsByTagName("body")->item(0);
    if ( null == $bodyElem ) {
        $bodyElem = $domDoc->firstChild;
        if ( null == $bodyElem ) {
            return "";
        }
    }
    $newDoc = new DOMDocument();
    $newBodyElem = cloneFragment( $bodyElem, $newDoc );
    $newDoc->appendChild( $newBodyElem );
    $newDocStr = $newDoc->saveHTML();
    return $newDocStr;
}

function cloneFragment( $elemIn, $newDoc ) {
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

?>