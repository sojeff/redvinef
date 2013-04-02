<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>SO:KNO&trade; | Harvester Tool</title>
	
	<link rel='stylesheet' type='text/css' href='redvinef/theme/css/reset.css'>
	<link rel='stylesheet' type='text/css' href='redvinef/theme/css/harvester.css'>
	
	
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js'></script>

	<script type='text/javascript' src='redvinef/theme/js/libs/jquery.watermark.min.js'></script>

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
			<?php 
				$url = str_replace("\\","",$url);
				$url = str_replace('"','',$url);
				$title = str_replace("\\","",$title);
				$title = str_replace('"',"",$title);
				  echo '<form method="post" action="'.$harvester_path.'" >'; 
				  echo '<input type="hidden" name="guid" id="guid" value="'.$userguid.'" />';
				  echo '<input type="hidden" name="url" id="url" value="'.$url.'" />';
				  echo '<input type="hidden" name="title" id="title" value="'.$title.'" />';
			?>
				
				<div id="photo" class="block-harvest">
					<h4>Choose an Image</h4>
					<p>Select the image that will be used as the primary image for this content</p>
					<div id="image">
					<?
					$mypageExtractImages0 = '';
					if(isset($mypageExtract['images'][0]))
						{
						$mypageExtractImages0 = $mypageExtract['images'][0];
						echo '<input type="radio" id="image1" name="image" checked="checked" value="1" />';
						echo '<label for="image1"><img src="'.$mypageExtractImages0.'" width="172" height="128" alt="" /></label>';
						}
						
					$mypageExtractImages1 = '';
					if(isset($mypageExtract['images'][1]))
						{
						$mypageExtractImages1 = $mypageExtract['images'][1];
						echo '<input type="radio" id="image2" name="image" value="2" />';
						echo '<label for="image2"><img src="'.$mypageExtractImages1.'" width="172" height="128" alt="" /></label>';
						}

					$mypageExtractImages2 = '';
					if(isset($mypageExtract['images'][2]))
						{
						$mypageExtractImages2 = $mypageExtract['images'][2];
						echo '<input type="radio" id="image3" name="image" value="3" />';
						echo '<label for="image3"><img src="'.$mypageExtractImages2.'" width="172" height="128" alt="" /></label>';
						}
					?>
					</div>
				</div>
				
				<div id="jump" class="block-harvest">
					<h4>Place Content</h4>
					<p>Select the JumpPad where you want this content to be placed</p>
				
					<div id="jumppad">
						<input type="radio" id="jumppad1" name="jumppad" value="personal" />
						<label for="jumppad1">Personal</label>
		            	<? if(count($pvtarray) > 0) { ?>
						<input type="radio" id="jumppad2" name="jumppad" value="private" />
						<label for="jumppad2">Private</label>
						<? } ?>
					</div>

					<div id="knowcell">
						<select id="personal" name="personal">
							<option disabled value="Select a Personal Knowledge Cell" selected="selected">Select a Personal Knowledge Cell</option>
							<option disabled value="">---------------------------</option>
							<option value="knowtitle">+ Add New Knowledge Cell</option>
							<option value="">---------------------------</option>
							<? 
							foreach($personaltopics as $ptopic)
								{
								echo '<option value="'.$ptopic.'">'.$ptopic.'</option>';
								}
							?>
						</select>
					 
						<select id="private" name="private">
							<option disabled value="" selected="selected">Select a Private Knowledge Cell</option>
							<?
							if($can_create_private == 'Y') {
							echo '<option disabled value="">---------------------------</option>';
						 	echo '<option value="knowtitle">+ Add New Private Knowledge Cell</option>';
							}
							?>
							<option disabled value="">---------------------------</option>
							<? 
							foreach($pvtarray as $pa)
								{
								echo '<option value="'.$pa.'">'.$pa.'</option>';
								}
							?>
						</select>
					</div>

					<div id="knowtitle">
						<p>Enter in the Name of this Knowledge Cell.  For example, <strong>Algebra</strong>, <strong>Golf</strong>, <strong>Research</strong> etc</p>
						<input type="text" id="knowcell_name" name="knowcell_name" value="" />
					</div>

					<div id="knowtitlepvt">
						<p>Enter in the Name of this Knowledge Cell.  For example, <strong>Algebra</strong>, <strong>Golf</strong>, <strong>Research</strong> etc</p>
						<input type="text" id="knowcell_name_pvt" name="knowcell_name_pvt" value="" />
					</div>

				</div>

				<div id="knowtopics" class="block-harvest">
					<h4>Add Topic <em>(REQUIRED)</em></h4>
					<p>Select a Topic to help Categorize this content</p>
					<select name="topics" id="topics">
						<option disabled value="Select a Topic" selected="selected">Select a Topic</option>
						<? 
						foreach($tagsarray as $topic)
							{
							echo '<option value="'.$topic.'">'.$topic.'</option>';
							}
						?>
					</select>
				</div>

				<div id="knowtags" class="block-harvest">
					<h4>Add Tags <em>(OPTIONAL)</em></h4>
					<p>Add Keywords as Tags. Seperate each Keyword with a <strong>Comma</strong> or <strong>Space</strong></p>
					<input type="text" id="tag-content" name="tag-content" value="" />
				</div>
				
				<div id="knowadd">
					<div class="button input orange"><input type="submit" value="Add Knowledge" /></div>
				</div>
				
			</form>
		</div>
		
	</div>
	<!-- END BOOKMARKLET -->
<?
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
//echo '<br>title: '.$title;
//echo '<br>url:   '.$url;
?>
</body>
</html>