<div id="page-wrap" class="clearfix">

	<!-- PAGE -->
	<div id="page" class="container_12">
	
		<!-- SIDEBAR -->
<aside class="grid_3">

	<!-- USER -->
<section class="user">
	<hgroup>
		<h3><? echo $persona->name; ?></h3>
	</hgroup>
	<div class="block persona clearfix">
		<div class="info">
			<div class="thumb"><a href="#"><? echo $avatar; ?></a></div>
			<div class="level">
			<? if($level_array[0] == 1)
					echo '<span class="level1 stage'.$level_array[1].'">{#}</span>';
			   else
					echo '<span class="level1 stage0">1</span>';
			   if($level_array[0] == 2)
					echo '<span class="level2 stage'.$level_array[1].'">{#}</span>';
			   else
					echo '<span class="level2 stage0">2</span>';
			   if($level_array[0] == 3)
					echo '<span class="level3 stage'.$level_array[1].'">{#}</span>';
			   else
					echo '<span class="level3 stage0">3</span>';
			
				echo '<p>'.ucwords($level_array[2]).'</p>';
				?>
			</div>
			<div class="location">
				<span class="icon-marker"></span>
				<p><? echo $persona->currentlocationcity.' '.$persona->currentlocationcountry; ?></p>
			</div>
		</div>
	</div>
	<? if($user->userguid != $persona->userguid)
		  {
			if(!$following_thisperson)
				{
				echo '<div class="button wide unfollow">';
				echo '<a href="persona.php?in=3&user_follow='.$user->userguid.'&user_id='.$persona->userguid.'&followstartstop=start&specialflip=Y"><span class="label-icon"><span class="check"></span>Not Following</span></a>';
				}
			else
				{
				echo '<div class="button wide follow">';
				echo '<a href="persona.php?in=3&user_follow='.$user->userguid.'&user_id='.$persona->userguid.'&followstartstop=stop&specialflip=Y"><span class="label-icon"><span class="check"></span>Following</span></a>';
				}
			echo '</div>';
		  }
	?>
</section>

	<!-- CONNECTIONS -->
<section class="connections">
	<ul class="nav">
		<?
		echo '<li><a href="persona.php?in=1&user_id='.$persona->userguid.'">PersonaGraf</a></li>';
		echo '<li><a href="persona.php?in=2&user_id='.$persona->userguid.'">Library</a><span class="stat">'.number_format(Jumppad::count_jumppads('personal', $persona->userguid,0), 0).'</span></li>';
		echo '<li class="current"><a class="current" href="persona.php?in=3&user_id='.$persona->userguid.'">Connections</a><span class="stat">'.number_format($connections).'</span></li>';
		?>
	</ul>
</section>

	<!-- SPOTLIGHT -->
<section class="spotlight">
	<hgroup>
		<h3>Spotlight</h3>
	</hgroup>
	
	<!-- INFO -->
	<div class="spotlight-info">
		
		<!-- STATS -->
		<div class="info-section">
			<div class="info-quad1">
				<h5>Lifetime</h5>
				<h4><? echo $time.$add; ?> Ago</h4>
			</div>
			<div class="info-quad2">
				<h5>Curations</h5>
				<h4><? echo $curations; ?></h4>
			</div>
		</div>

		<div class="info-section">
			<div class="info-quad3">
				<h5>Followers</h5>
				<h4><? echo $followers_count; ?></h4>
			</div>
			<div class="info-quad4">
				<h5>Following</h5>
				<h4><? echo $following_count; ?></h4>
			</div>
		</div>
		
		<!-- SOURCE -->
		<div class="info-section thread">
			<h5>Recent Curation</h5>
			<?
			//need to display differently if the recent curation is a thread and not content
			if(isset($cell->content_type) and $cell->content_type == 0)
				{
				echo '<div class="tile content grid_3 alpha">';
				echo '<h4 class="title"><a href="'.$cellhref.'">'.$celltitle.'</a></h4>';
				echo '<div class="thumb"><a href="'.$cellhref.'">'.$image_display.'</a></div>';
				echo '<p class="excerpt">'.$body.'</p>';
				echo '<div class="stats">';
					echo '<ul>';
						echo '<li><span class="stat-date"></span>'.$time3.$add3.'</li>';
						echo '<li><span class="stat-like"></span>'.$content->cell_likes($cell->contentid, $cell->curationid).'</li>';
					echo '</ul>';
				echo '</div>';
				}
			else if(isset($cell->contentid))//thread
				{
				echo '<div class="tile thread grid_3 alpha">';
				echo '<h4 class="title"><a href="thread.php?contentid='.$cell->contentid.'&curationid='.$maincurationid.'&commentid='.$maincommentid.'">'.$commenttitle.'</a></h4>';
				echo '<p class="comment"><a href="thread.php?contentid='.$cell->contentid.'&curationid='.$maincurationid.'&commentid='.$maincommentid.'">'.$commentbody.'</a></p>';
				?>							
				<div class="mini-persona">
					<div class="thumb">
						<?php 
						if($persona->avatar != '')
							echo '<a href="persona.php?user_id='.$persona->userguid.'"><img src="retrieveUserAvatar.php?id='.$persona->userguid.'" alt="" width="36" height="36" /></a>'; 
						else
							echo '<a href="persona.php?user_id='.$persona->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="36" height="36" /></a>';
						?>
					</div>
					<div class="info">
						<?php
						echo '<h4><a href="persona.php?user_id='.$persona->userguid.'">'.$persona->name.'</a></h4>';
						echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
						?>
					</div>
					<? 
					if(!$following and $persona->userguid != $session->user_id)
						echo '<div class="button-follow square">
								<a href="cell.php?follow='.$persona->userguid.'">Follow</a>
							  </div>'; 
					else
						echo '<div class="button-follow square">
								<a class="active" href="cell.php?stopfollow='.$persona->userguid.'">Follow</a>
							  </div>'; 
				?>
				</div>
				<div class="data">
					<div class="recent">
						<p>Recent Post</p>
						<h4><? echo  $time2.$add2. ' ago'; ?></h4>
					</div>
					<div class="comments">
						<p>Comments</p>
						<h4><?php echo number_format($comment->count_comments_new($maincommentid)); ?></h4>
					</div>
				</div>
				<div class="stats">
					<ul>
						<li><span class="stat-date"></span><?php echo $time3.$add3; ?></li>
						<li><span class="stat-persona"></span><?php echo number_format($comment->count_users_on_thread($maincommentid)); ?></li>
					</ul>
				</div>
			 <?	} ?>
			
			</div>
		</div>
		
	</div>
	<!-- END INFO -->

</section>
<!-- END SPOTLIGHT -->

</aside>
<!-- END SIDEBAR -->
		<!-- CONTENT -->
<div id="content-wrap" class="grid_9">

	<!-- CONTENT HEADER -->
<div id="content-header" class="clearfix">
	<h3>Connections</h3>
	<ul id="nav-connections">
		<li class="nav-label">Sort</li>
		<?
		echo $followers;
		echo $following;
		?>
	</ul>
</div>
	
	<!-- CONTENT BLOCK -->
	<div id="content-connections" class="clearfix">
		<?
		$session->message = '';
		$i=1;
		foreach($followers_array as $follow_me)
		{
		if($followers_filter == 'followers')
			{
			$session->message .= '<br>follow_me->userguid_follower:'.$follow_me->userguid_follower;
			$classactive = '';
			$follower_user = User::find_by_id($follow_me->userguid_follower);
			$following_this_user = false;
			$following_this_user = User_follows::get_persona_followers($follow_me->userguid_follower, $persona->userguid);
			if($following_this_user)
				$classactive = ' class="active"';
				
			if($following_this_user and $persona->userguid == $session->user_id)
				{
				$follow_display = '<a href="persona.php?in=3&follower='.$followers_filter.'&user_id='.$persona->userguid.'&user_follow='.$follow_me->userguid_follower.'&followstartstop=stop"'.$classactive.'>Follow</a>';
				}
			else if($persona->userguid == $session->user_id)
				{
				$follow_display = '<a href="persona.php?in=3&follower='.$followers_filter.'&user_id='.$persona->userguid.'&user_follow='.$follow_me->userguid_follower.'&followstartstop=start"'.$classactive.'>Follow</a>';
				}
			else
				$follow_display = '<a href="#"'.$classactive.'>Follow</a>';
			}
		else
			{
			$classactive = ' class="active"';
			$follower_user = User::find_by_id($follow_me->userguid_followed);
			if($persona->userguid == $session->user_id)
				$follow_display = '<a href="persona.php?in=3&follower='.$followers_filter.'&user_id='.$persona->userguid.'&user_follow='.$follow_me->userguid_followed.'&followstartstop=stop"'.$classactive.'>Follow</a>';
			else
				$follow_display = '<a href="#"'.$classactive.'>Follow</a>';
			}
			
		if($i == 1)
			echo '<div class="micro persona grid_3 alpha">';
		else if($i == 2)
			echo '<div class="micro persona grid_3">';
		else
			echo '<div class="micro persona grid_3 omega">';
			
			echo '<h4 class="title"><a href="persona.php?user_id='.$follower_user->userguid.'">'.$follower_user->name.'</a></h4>';
			echo '<div class="button-follow square">';
				
				echo $follow_display;
			echo '</div>';
			echo '<div class="info">';
				echo '<div class="thumb">';
				if($follower_user->avatar != '')
					echo '<a href="persona.php?user_id='.$follower_user->userguid.'"><img src="retrieveUserAvatar.php?id='.$follower_user->userguid.'" alt="" width="60" height="60" /></a>'; 
				else
					echo '<a href="persona.php?user_id='.$follower_user->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="60" height="60" /></a>';
				echo '</div>';
				$find_level = User_levels::find_by_id($follower_user->userguid);
				$level_array = calculate_level($follower_user, $find_level);
				echo '<div class="level">';
				if($level_array[0] == 1)
					echo '<span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'; 
				else
					echo '<span class="level1 stage0">1</span>';
				if($level_array[0] == 2)
					echo '<span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'; 
				else
					echo '<span class="level2 stage0">2</span>';
				if($level_array[0] == 3)
					echo '<span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'; 
				else
					echo '<span class="level3 stage0">3</span>';
					echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'">'.ucwords($level_array[2]).'</p>';
				echo '</div>';
				echo '<div class="location">';
					echo '<span class="icon-marker"></span>';
					$location = '';
					if(isset($follower_user->currentlocationcity) and $follower_user->currentlocationcity != '')
						$location = $follower_user->currentlocationcity;
					//if(isset($follower_user->currentlocationcountry) and $follower_user->currentlocationcountry != '')
					//	$location = $location . ', '.$follower_user->currentlocationcountry;
					if(strlen($location) > 20)
						$location = substr($location,0,20);
					echo '<p>'.$location.'</p>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		$i++; //1,2,3 defines grid_3
		if($i>3)
			$i=1;
		}
		
		//echo $session->message;
		?>

	</div>

</div>
<!-- END CONTENT -->
	
	</div>
	<!-- END PAGE -->

</div>

</body>
</html>