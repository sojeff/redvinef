Files:

	extractPageElements.php		- main application source file;
	OpenGraph.php				- open source implimentation of the Open Graph Protocol;
	Net/URL2.php				- open source library for normalizing/processing URLs;
	
	
Example Output Data Structure:

	Array
	(
	    [url] => http://www.apple.com/
	    [referrer_url] => http://www.apple.com/
	    [titles] => Array
	        (
	            [0] => Apple
	        )

	    [descriptions] => Array
	        (
	            [0] => Apple designs and creates iPod and iTunes, Mac laptop and desktop computers, the OS X operating system, and the revolutionary iPhone and iPad.
	        )

	    [images] => Array
	        (
	            [0] => http://images.apple.com/home/images/macbookpro_hero.jpg
	            [1] => http://images.apple.com/home/images/macbookpro_title.png
	            [2] => http://images.apple.com/home/images/promo_macbookair.png
	            [3] => http://images.apple.com/home/images/promo_mountainlion.png
	            [4] => http://images.apple.com/home/images/promo_backtoschool_july_2012.png
	            [5] => http://images.apple.com/home/images/promo_allonipad.png
	            [6] => http://images.apple.com/home/images/macbookpro_ad_hero.jpg
	            [7] => http://images.apple.com/home/images/macbookpro_video_hero.jpg
	        )

	    [content] => { pages-long string of HTML content here }