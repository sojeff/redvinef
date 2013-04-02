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
				echo '<a href="persona.php?user_follow='.$user->userguid.'&user_id='.$persona->userguid.'&followstartstop=start&specialflip=Y"><span class="label-icon"><span class="check"></span>Not Following</span></a>';
				}
			else
				{
				echo '<div class="button wide follow">';
				echo '<a href="persona.php?user_follow='.$user->userguid.'&user_id='.$persona->userguid.'&followstartstop=stop&specialflip=Y"><span class="label-icon"><span class="check"></span>Following</span></a>';
				}
			echo '</div>';
		  }
	?>
</section>

	<!-- CONNECTIONS -->
<section class="connections">
	<ul class="nav">
		<?
		echo '<li class="current"><a class="current" href="persona.php?in=1&user_id='.$persona->userguid.'">PersonaGraf</a></li>';
		echo '<li><a href="persona.php?in=2&user_id='.$persona->userguid.'">Library</a><span class="stat">'.number_format(Jumppad::count_jumppads('personal', $persona->userguid,0), 0).'</span></li>';
		echo '<li><a href="persona.php?in=3&user_id='.$persona->userguid.'">Connections</a><span class="stat">'.number_format($connections).'</span></li>';
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
			else if(isset($cell->commentid) and $cell->commentid > 0)//thread
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
	<h3>PersonaGraf</h3>
</div>
	
	<!-- CONTENT BLOCK -->
	<div id="content-persona" class="clearfix">
		
		<div class="persona-block">
			<h3>Activity</h3>
			<div class="persona-info knowledge">
				<h3 class="title know">Knowledge</h3>
				<div>
					<h5>Knowledge Cell</h5>
					<h3><? echo $ktopicdesc; ?></span></h3>
				</div>
				<div>
					<h5>Rank</h5>
					<h3><? echo $know_level[1];; ?></h3>
				</div>
				<div>
					<h5>Next Rank</h5>
					<h3><? echo $next_know_rank; ?></h3>
				</div>
			<!--	<div>
					<h5>Global Rank</h5>
					<h3>234th</h3>
				</div> -->
			</div>

			<div id="cp1" class="persona-chart">
				<h3>Rank Completed</h3>
				<? echo '<div class="cht-knowledge" data-percent="'.number_format($know_level[0],1).'">'; ?> 
					<span class="percent"><? echo number_format($know_level[0],1)."%"; ?></span>
				</div>
				<? echo '<div class="cht-interest" data-percent="'.number_format($interest_level[0],1).'">'; ?> 
					<span class="percent"><? echo number_format($interest_level[0],1)."%"; ?></span>
				</div>
			</div>

			<div class="persona-info interest">
				<h3 class="title interest">Interest</h3>
				<div>
					<h5>Knowledge Cell</h5>
					<h3><? echo $itopicdesc; ?></h3>
				</div>
				<div>
					<h5>Rank</h5>
					<h3><? echo $interest_level[1]; ?></h3>
				</div>
				<div>
					<h5>Next Rank</h5>
					<h3><? echo $next_interest_rank; ?></h3>
				</div>
			<!--	<div>
					<h5>Global Rank</h5>
					<h3>1,237th</h3>
				</div> -->
			</div>

			<div class="persona-nav">
				<ul>
					<?
					echo $choice1;
					echo $choice2;
					echo $choice3;
					?>
				</ul>
			</div>

		</div>

		<div class="persona-block">
			<h3 class="title know">Knowledge Ranking</h3>
			<div class="persona-stats">
				<div>
					<h5>Rank</h5>
					<h3><? echo $know_level[1]; ?></h3>
				</div>
				<div>
					<h5>Rankings</h5>
					<ul class="rankings know">
						<?
						echo $krankings1;
						echo $krankings2;
						echo $krankings3;
						echo $krankings4;
						?>
					</ul>
				</div>
				<div>
					<h5>Level</h5>
					<ul class="level know">
						<?
						echo $k_level_ul1;
						echo $k_level_ul2;
						echo $k_level_ul3;
						?>
					</ul>
				</div>
			</div>
			<div class="persona-stats curation">
				<div>
					<h5>Knowledge Cell</h5>
					<h3><span><? echo $ktopicdesc; ?></span></h3>
				</div>
				<div>
					<span>
						<h5>Likes</h5>
						<h3><? echo number_format($kcount_likes,0) ?></h3>
					</span>
					<span>
						<h5>Tags</h5>
						<h3><? echo number_format($count_know_tags,0) ?></h3>
					</span>
				</div>
				<div>
					<span>
						<h5>Comments</h5>
						<h3><? echo number_format($count_know_comments,0) ?></h3>
					</span>
					<span>
						<h5>Contributions</h5>
						<h3><? echo number_format($count_know_curations,0); ?></h3>
					</span>
				</div>
			</div>
		</div>

		<div class="persona-block">
			<h3 class="title interest">Interest Ranking</h3>
			<div class="persona-stats">
				<div>
					<h5>Rank</h5>
					<h3><? echo $interest_level[1]; ?></h3>
				</div>
				<div>
					<h5>Rankings</h5>
					<ul class="rankings interest">
						<?
						echo $irankings1;
						echo $irankings2;
						echo $irankings3;
						echo $irankings4;
						?>
					</ul>
				</div>
				<div>
					<h5>Level</h5>
					<ul class="level interest">
						<?
						echo $i_level_ul1;
						echo $i_level_ul2;
						echo $i_level_ul3;
						?>
					</ul>
				</div>
			</div>
			<div class="persona-stats curation">
				<div>
					<h5>Knowledge Cell</h5>
					<h3><? echo $itopicdesc; ?></h3>
				</div>
				<div>
					<span>
						<h5>Likes</h5>
						<h3><? echo number_format($icount_likes,0) ?></h3>
					</span>
					<span>
						<h5>Tags</h5>
						<h3><? echo number_format($count_interest_tags,0) ?></h3>
					</span>
				</div>
				<div>
					<span>
						<h5>Comments</h5>
						<h3><? echo number_format($count_interest_comments,0) ?></h3>
					</span>
					<span>
						<h5>Contributions</h5>
						<h3><? echo number_format($count_interest_curations,0); ?></h3>
					</span>
				</div>
			</div>
		</div>

		<div class="persona-block">
			<h3 class="title">Totals</h3>
			<div class="persona-stats">
				<div>
				<!--
					<span>
						<h5>Global Rank</h5>
						<h3>26th</h3>
					</span>
				-->
					<span>
						<h5>Knowledge Cells</h5>
						<h3><? echo number_format($count_total_knowcells,0); ?></h3>
					</span>
				</div>
				<div>
					<span>
						<h5>Likes</h5>
						<h3><? echo number_format($count_total_likes,0); ?></h3>
					</span>
					<span>
						<h5>Tags</h5>
						<h3><? echo number_format($count_total_tags,0); ?></h3>
					</span>
				</div>
				<div>
					<span>
						<h5>Comments</h5>
						<h3><? echo number_format($count_total_comments,0); ?></h3>
					</span>
					<span>
						<h5>Contributions</h5>
						<h3><? echo number_format($total_contributions,0); ?></h3>
					</span>
				</div>
			</div>
		</div>

	</div>

</div>
<!-- END CONTENT -->
	
	</div>
	<!-- END PAGE -->

</div>
				<?php 
				if($session->user_id == 1004833642) {
				echo "<pre>msg: " . print_r($session->message) . "</pre><br/>"; 
				}
				?>
</body>
</html>