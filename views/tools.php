
<div id="page-wrap" class="clearfix">

	<!-- PAGE -->
	<div id="page" class="container_12">
	
		<!-- SIDEBAR -->
<aside class="grid_3">

	<!-- ABOUT -->
<section class="jumppads">
	<hgroup>
		<h3>Learn More</h3>
	</hgroup>
	<ul class="nav">
		<li><a href="about.php?in=1">What is SO:KNO?</a></li>
		<li><a href="about.php?in=2">Guide</a></li>
		<li class='current'><a class='current' href="about.php?in=3">Tools</a></li>
		<li><a href="about.php?in=4">The Team</a></li>
		<li><a href="about.php?in=5">Contact &amp; Support</a></li>
	</ul>
</section>
	<!-- ABOUT -->
<section class="jumppads">
	<hgroup>
	</hgroup>
	<ul class="nav">
		<li><a href="about.php?in=6">Terms of Service</a></li>
		<li><a href="about.php?in=7">Privacy Policy</a></li>
	</ul>
</section>

</aside>
<!-- END SIDEBAR -->
		<!-- CONTENT -->
<div id="content-wrap" class="grid_9">

	
	<!-- CONTENT HEADER -->
<div id="content-header" class="clearfix">
	<h3>Tools</h3>
</div>
	
	<!-- CONTENT BLOCK -->
	<div id="content-article" class="clearfix">
		<h3 class="title">The SO:KNO Harvester</h3>
		<div id="harvester">
		<?
			//echo '<a onclick="alert(\'Drag me to the bookmarks bar\'); return false;" href="'.$urlnow.'" title="SO:KNO Harvest" class="button">';
			echo '<a href="javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&notes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()">
			  <span><img src="../theme/img/ui/button-harvest.png" alt="SO:KNO Harvester" align="middle" />
			  </span></a>';
		$isiPad = false;
		$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
		$isiPod = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPod');
		$isiPhone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
		$isAndroid = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Android');
		?>
		</div>
		<h3>Installing the "SO:KNO | Harvester" button on  your browser bookmark</h3>
		<?
		if(!$isiPad and !$isiPod and !$isiPhone and !$isAndroid)
		{
		?>
		<h3>Desktop Installation</h3>
		<ol>
			<li>Drag and drop the image above to your <strong>browser bookmark bar.</strong></li>
			<li>Click anywhere on the screen and the SO:KNO | Harvester button will be installed.</li>
		</ol>
		<p>Now that your Harvester button is installed, you are may surf the web like you do everyday and anytime you find an article or video you’d like to save, simply click on the button while viewing the page.</p>
		<?
		}
		if($isiPad or $isiPod or $isiPhone)
			{
			echo '<br><p>***    copy javascript below for iPad     ***</p>';
			echo '<p><form class="none"><textarea rows="6" cols="80">javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&ntes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()</textarea><br/>';
			echo '</form></p>';
			if($isiPad)
				echo '<h3>iPad Installation</h3>';
			else if($isiPod)
				echo '<h3>iPod Installation</h3>';
			else if($isiPhone)
				echo '<h3>iPhone Installation</h3>';
			?>	
			<p>If you have Safari and share your bookmarks to your iOS Device, the SO:KNO | Harvester will copy over automatically.</p>
			<ol>
				<li>Touch and hold your finger over the text box until you see an orange highlight around the box</li>
				<li>Touch and hold your finger on top of the text box until you see the following menu</li>
				<li>Choose "select all"</li>
				<li>Copy</li>
				<li>Bookmark the current page</li>
				<li>Change the name to "SO:KNO | Harvester" then click save.</li>
				<li>Click the bookmark icon</li>
				<li>Select bookmarks bar</li>
				<li>Click edit bookmarks</li>
				<li>Select "SO:KNO Harvest"</li>
				<li>Touch and hold the text in the address field and select all</li>
				<li>Select paste</li>
				<li>Go back to the edit bookmarks section</li>
				<li>Select Done</li>
			</ol>
			<? } 
		if($isAndroid)
			{
			echo '<br><p>***    copy javascript below for iPad     ***</p>';
			echo '<p><form class="none"><textarea rows="6" cols="80">javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&ntes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()</textarea><br/>';
			echo '</form></p>';
			echo '<h3>Android Installation</h3>';
			?>	
			<ol>
				<li>Touch and hold your finger over the text box until you see an orange highlight around the box</li>
				<li>Touch and hold your finger on top of the text box until you see the following menu</li>
				<li>Choose "select all"</li>
				<li>Copy</li>
				<li>Bookmark the current page</li>
				<li>Change the name to "SO:KNO | Harvester" then click save.</li>
				<li>Click the bookmark icon</li>
				<li>Select bookmarks bar</li>
				<li>Click edit bookmarks</li>
				<li>Select "SO:KNO Harvest"</li>
				<li>Touch and hold the text in the address field and select all</li>
				<li>Select paste</li>
				<li>Go back to the edit bookmarks section</li>
				<li>Select Done</li>
			</ol>
			<? } ?>
		<h3>Training Videos</h3>
		<ol>
			<li>Installing the "SO:KNO Harvester" tool in your Browser <strong><a href="../userdata/files/InstallingtheHarvesterButton.mov" target="SK Video1">Click to View</a></strong></li>
			<li>Using the "SO:KNO Harvester" tool in your Browser <strong><a href="../userdata/files/UsingtheHarvesterButton.mov" target="SK Video2">Click to View</a></strong></li>
			<li>SO:KNO Quick Start Tool <strong><a href="../userdata/files/soknoquicktour.mov" target="SK Video3">Click to View</a></strong></li>
			<li>The "SO:KNO Harvester" tool <strong><a href="../userdata/files/theharvesttool.mov" target="SK Video4">Click to View</a></strong></li>
			<li>The PersonaGraf <strong><a href="../userdata/files/thepersonagraf.mov" target="SK Video5">Click to View</a></strong></li>
			<li>How to start a discussion <strong><a href="../userdata/files/whatarethreads.mov" target="SK Video6">Click to View</a></strong></li>
		</ol>
	</div>

</div>
<!-- END CONTENT -->
	
	</div>
	<!-- END PAGE -->

</div>

</body>
</html>