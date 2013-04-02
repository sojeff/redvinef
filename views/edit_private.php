	<div class="clearfix" id="page-wrap">
	<!-- PAGE -->
		<div class="container_12" id="page">
		<?
		echo '<div>';
		echo '<ul>';
		foreach($private_groups as $pg):
			
			echo '<li>'.$pg->privateid.' '.$pg->private_group_title.' <a href="edit_private?privateid='.$pg->privateid.'">Edit Members</a></li>';
			if(isset($myprivateid) and $myprivateid == $pg->privateid)
				{
				echo '<ul>';
				foreach($private_groups_members as $pgm):
					echo '<div>';
					$thisuser = User::find_by_id($pgm->userguid);
					if($thisuser)
						echo ' &nbsp;&nbsp;<li>'.$thisuser->first_name.' '.$thisuser->last_name.' <a href="edit_private?userguid='.$pgm->userguid.'&pgmprivateid='.$pgm->memberid.'&delete=YES">Delete</a></li>';
					echo '</div>';
				endforeach;
				
				echo '</ul>';
				}
		
		endforeach;
		echo '</ul>';
		echo '</div>';
		?>
			

			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->message != "" and $session->user_id == 1004833642) {
				echo "<p>" . $session->message . "</p><br/>"; 
				echo "<p>User View: " . $session->user_view . "</p><br/>"; 
				echo "<p><pre>";
				print_r($_POST);
				echo "</pre></p>";
				}?>
				<p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p>
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
	</div>

</body>
</html>
