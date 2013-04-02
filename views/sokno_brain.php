	<!-- PAGE -->
	<div id="page" class="container_12 clearfix">
		<div id="content" class="grid_9">
			<div id="content-tiles" class="clearfix">

			<p><br/><br/><?php echo $user->name; ?>, here is your personalized So-KNO Knowledge Harvester.</p>
			<p>Please drag or save this harvesting icon onto your address book (Bookmark) of your browser.<br/>
			</p><br/>
			<?php echo '<p><a href="javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&notes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()">
			  <img src="../theme/harvest2.png" alt="" align="middle" /></a></p>';
			?>
			<br/>
			<?php 
			$urlnow = 'javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?'.html_entity_decode('guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&notes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()');
			echo ' Or, copy ALL of the text below and save to a bookmark on your browser.';
			//echo '<br/><br/><textarea class="none" rows="8" cols="90">javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&notes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()</textarea><br/>';
			echo '<br><br>**************** copy javascript below ***************<br>';
			echo '<br><br><form class="none"><textarea rows="6" cols="80">javascript:(function()%7Bf=\'http://'.$_SERVER['HTTP_HOST'].'/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&ntes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()</textarea><br/>';
			echo '</form><br><br>';
			?>
			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
				<?php if($session->message != "" and $session->user_id == 1004833642) echo "<p>" . $session->message . "</p><br/>"; ?>
				<p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p>
			<!-- FOOTER END -->
			</div>
			</div>
								
		</div>
	</div>

</div>
</body>
</html>
