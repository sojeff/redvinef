<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>SO:KNO&trade; | Harvester Tool</title>
	
	<link rel='stylesheet' type='text/css' href='../theme/css/reset.css'>
	<link rel='stylesheet' type='text/css' href='../theme/css/harvester.css'>
	
	
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js'></script>

	<script type='text/javascript' src='../theme/js/libs/jquery.watermark.min.js'></script>

	<script>
		$(function() {
			
			//SETUP BUTTON UI
			$("#image, #jumppad").buttonset();

			//HIDE DIVs
			$("#knowtitle, #knowtitlepvt, #knowtags, #personal, #private, #knowtopics").hide();

			//JUMPPAD SELECTION MENU SHOW/HIDE
			$("input[name=jumppad]").change(function(){
    			$("#knowtitle, #knowtitlepvt #knowtags, #knowtopics, #personal, #private").hide();
    			$('#personal, #private').prop('selectedIndex',0);
    			$('#topics').prop('selectedIndex',0);
				$('#' + $("input:radio[name='jumppad']:checked").val()).show();
			});

			$("#personal").change(function(){
				$("#knowtitle").hide();
				$("#" + $(this).val()).show();
			});

			$("#private").change(function(){
				$("#knowtitlepvt").hide();
				$("#" + $(this).val()).show();
			});

			//TITLE AND TAG FIELDS SHOW/HIDE
			$("#personal").change(function(){
				if($(this).val() === "knowtitle") {
					$("#knowtitle, #knowtopics, #knowtags").show();
				}
				if($(this).val() === "") {
					$("#knowtitle, #knowtopics, #knowtags").hide();
				}
				else {
					$("#knowtopics, #knowtags").show();
				}
			});

			$("#private").change(function(){
				if($(this).val() === "knowtitlepvt") {
					$("#knowtitlepvt, #knowtags").show();
				}
				if($(this).val() === "") {
					$("#knowtitlepvt, #knowtags").hide();
				}
				else {
					$("#knowtags").show();
				}
			});

			//WATERMARKS
			//var $addTitle = 'Enter Knowledge Cell Name';
			//var $addTags = 'Enter Tags';
			//$("#title").watermark($addTitle, {useNative: false});
			//$("#tags").watermark($addTags, {useNative: false});

		});
	</script>
	
	<script>
  setInterval(function(){
    try {
      if(typeof ws != 'undefined' && ws.readyState == 1){return true;}
      ws = new WebSocket('ws://'+(location.host || 'localhost').split(':')[0]+':35353')
      ws.onopen = function(){ws.onclose = function(){document.location.reload()}}
      ws.onmessage = function(){
        var links = document.getElementsByTagName('link'); 
          for (var i = 0; i < links.length;i++) { 
          var link = links[i]; 
          if (link.rel === 'stylesheet' && !link.href.match(/typekit/)) { 
            link.href = link.href.split('?')[0]+'?'+(new Date()).getTime()
          }
        }
      }
    }catch(e){}
  }, 500)
</script>
	<script type="text/javascript">
		$(function() { 
			$("#knowcell_name").autocomplete({
			source: 'redvinef/libraries/db/autocomplete.php', minLength:2
			});
			});
	</script>

</head>

<body>

	<!-- BOOKMARKLET -->
	<div id="bookmarklet">

		<div id="header">
			<h1>SO:KNO&trade;</h1>
			<h3>A World of Knowledge. Infinite Possibilities.</h3>
		</div>
		
		<div id="content" class="clearfix">
		<p>Your content has been saved. You can close this window.</p>
		<?
		if(SERVERPD == 'Prod')
			echo '<p><a href="https://getsokno.com">Click to log into SO:KNO</a></p>';
		else
			echo '<p><a href="http://23.23.245.230/">Click to log into SO:KNO Dev...</a></p>';
		?>
		</div>
		
	</div>
	<!-- END BOOKMARKLET -->

</body>
</html>