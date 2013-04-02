	
<div id="page-wrap" class="clearfix">

	<!-- PAGE -->
	<div id="page" class="container_12">
	
		<!-- SIDEBAR -->
<aside class="grid_3">

	<!-- USER -->
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
				echo '<a href="persona.php?in=2&user_follow='.$user->userguid.'&user_id='.$persona->userguid.'&followstartstop=start&specialflip=Y"><span class="label-icon"><span class="check"></span>Not Following</span></a>';
				}
			else
				{
				echo '<div class="button wide follow">';
				echo '<a href="persona.php?in=2&user_follow='.$user->userguid.'&user_id='.$persona->userguid.'&followstartstop=stop&specialflip=Y"><span class="label-icon"><span class="check"></span>Following</span></a>';
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
		echo '<li class="current"><a class="current" href="persona.php?in=2&user_id='.$persona->userguid.'">Library</a><span class="stat">'.number_format(Jumppad::count_jumppads('personal', $persona->userguid,0), 0).'</span></li>';
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
	<h3>Library</h3>
		<ul id="nav-sort">
			<li class="nav-label">Sort</li>
			<?php echo '<li><a '.$sort_date_active.'href="persona.php?in=2&user_id='.$persona->userguid.'&user_sort=Date">Date</a></li>'; ?>
			<?php echo '<li><a '. $sort_alpha_active. 'href="persona.php?in=2&user_id='.$persona->userguid.'&user_sort=Alphabetical">Alpha</a></li>'; ?>
			<?php echo '<li><a '. $sort_relevance_active. 'href="persona.php?in=2&user_id='.$persona->userguid.'&user_sort=Relevance">Relevance</a></li>'; ?>
		</ul>
	</ul>
</div>
	
	<!-- CONTENT BLOCK -->
	<div id="content-library" class="clearfix">
				<?php

				$content_array = array();
				$thread_array = array();
				$width = 0;
				$length = 0;
				$i = 1;
				$counter = 0;
				foreach($cells as $thiscell):
					$skipthiscontent = true;
					$skipthiscomment = true;
					$not_in_my_thread_array = true;
					$commenttitle = "Blank Title";
					$maincurationid = 0;  //set up variables for hyperlinks
					$maincommentid  = 0;
					//get the full curation record...
					if($thiscell->contentid != 0) //get the related content
						{
						$content = Cell::find_by_id($thiscell->contentid); 
						$skipthiscontent = false;
						if(!$content)
							$skipthiscontent = true;
						}
					
					if(($thiscell->content_type == 1 or $thiscell->content_type == 2) and $thiscell->commentid != 0) //comment
						{
						$comment = Comments::find_by_id($thiscell->commentid);
						$skipthiscomment = false;
						if(!$comment)
							$skipthiscomment = true;
						
						$maincurationid = $comment->curationid;  //set up variables for hyperlinks
						$maincommentid  = $comment->commentid;
						if($comment->title != '')
							{
							if(strlen($comment->title) > 40)
								$commenttitle = substr($comment->title,0,40).'...'; //truncate the title on this view
							else
								$commenttitle = $comment->title;
							}
						if($comment->parentid != 0) //need parent thread
							{
							$parentcomment = Comments::find_by_id($comment->parentid);
							if($parentcomment->title != '')
								{
								if(strlen($parentcomment->title) > 40)
									$commenttitle = substr($parentcomment->title,0,40).'...';
								else
									$commenttitle = $parentcomment->title;
								}
							$maincurationid = $parentcomment->curationid;  //set up variables for hyperlinks
							$maincommentid  = $parentcomment->commentid;
							if($thiscell->content_type == 2 and $parentcomment->parentid != 0) //need to get the parent/parent thread of sub comment
								{
								$parentparent = Comments::find_by_id($parentcomment->parentid);
								if($parentparent->title != '')
									if(strlen($parentparent->title) > 40)
										$commenttitle = substr($parentparent->title,0,40).'...';
									else
										$parentparent = $parentcomment->title;
								$maincurationid = $parentparent->curationid;  //set up variables for hyperlinks
								$maincommentid  = $parentparent->commentid;
								}
							}
						}
						// need to handle threads and standalone threads with no content id here!!
						$not_in_my_thread_array = true;
						if(!$skipthiscomment and isset($comment->content_type) and $comment->content_type == 1) //then it's a main or level 1 comment.
							{
							if($comment->parentid == 0) //0 means main comment.
								{
								if(!in_array($comment->commentid, $thread_array))
									$added = array_push($thread_array, $comment->commentid);
								else
									$not_in_my_thread_array = false;
								}
							else
								{
								if(!in_array($comment->parentid, $thread_array))
									$added = array_push($thread_array, $comment->parentid);
								else
									$not_in_my_thread_array = false;
								}
							}
						else if(!$skipthiscomment and isset($comment->content_type) and $comment->content_type == 2) //then it's a sub-comment.
							{
							if($parentcomment->parentid != 0 and $parentparent->parentid != 0)
								{
								if(!in_array($parentcomment->parentid, $thread_array))
									$added = array_push($thread_array, $parentcomment->parentid);
								else
									$not_in_my_thread_array = false;
								}
							}
																			
					$not_in_my_content_array = true;
					if($thiscell->content_type == 0) //it's content URL.
						{
						if(!in_array($thiscell->contentid, $content_array))
							$added = array_push($content_array, $thiscell->contentid);
						else
							$not_in_my_content_array = false;
						}
					
			//*** Content 		
					if($counter <= $per_page and !$skipthiscontent and $not_in_my_content_array and $thiscell->contentid != 0 and $thiscell->content_type == 0) //display the content cell
						{
						$counter++;
						if($i == 1)
							echo '<div class="tile content grid_3 alpha">';
						else if($i == 2)
							echo '<div class="tile content grid_3">';
						else
							{
							echo '<div class="tile content grid_3 omega">';
							$i = 0;
							}
						$i++;  //counts the row of 3 cells
						$cellurl = substr(str_replace("www.","",parse_url($content->url,PHP_URL_HOST)),0,28);
						$cellurl = str_replace("m.youtube.com","youtube.com",$cellurl);
						echo '<h4 class="title"><a href="viewcontent.php?contentid='.$thiscell->contentid.'&curationid='.$thiscell->curationid.'&userguid='.$thiscell->userguid.'">' . $cellurl . '</a></h4>';
						echo '<div class="thumb" align="center"><a href="viewcontent.php?contentid='.$thiscell->contentid.'&curationid='.$thiscell->curationid.'">';
						if($image_id = $content->cell_image($thiscell->contentid)) 
							{
							$width = $content->cell_image_size($thiscell->contentid);
							if(1==1)
								{
								$width = str_replace('width="','',$width);
								$length = $width;
								$width = strtok($width,'"');
								$length = strtok($length, 'height="');
								$length = strtok('"');
								$length = strtok('"');
								if($length > 0 and $width > 0)
									{
									$ratio = $length/$width;
									if($length >= $width or $ratio > .72)
										{
										$x2 = round(128 * $width / $length);
										if($x2 > 228)
											$x2 = 228;
										echo '<img src="retrieveFile.php?id='.$image_id.'" alt="Cell-" width="'.$x2.'" height="128"></a>'; 
										}
									else
										{
										$y2 = round(228 * $length / $width);
										if($y2 > 128)
											$y2 = 128;
										echo '<img src="retrieveFile.php?id='.$image_id.'" alt="" width="228" height="'.$y2.'"></a>'; 
										}
									}
								else
									{
									echo '<img src="retrieveFile.php?id='.$image_id.'" alt="" width="228" height="128"></a>'; 
									}
								}
							else
								{
								echo '<img src="../theme/img/ui/placeholder-thumb.png" alt="" width="228" height="128"></a>'; 
								}
							}
						else
							echo '<img src="../theme/img/ui/placeholder-thumb.png" alt="" width="228" height="128"></a>'; 
							?>
						</div>
							<?php //fit the cell title in 1 line
							echo '<p class="excerpt" title="'.$content->title.'"><a href="viewcontent.php?contentid='.$thiscell->contentid.'&curationid='.$thiscell->curationid.'">';
							
							if(strlen($content->title) < 62)
								echo $content->title; 
							else
								echo substr($content->title,0,59) . "..."; 
							?>
							</a></p>
						<div class="stats">
							<ul>
							<? 
							$time = diff_times($content->date_created); 
							if(substr($time,0,2) != '1 ')
								$add = 's';
							else
								$add = '';
							?> 
								 <li><span class="stat-date"></span><?php echo $time.$add; ?></li> 
								<!-- <li><span class="stat-thread"></span><?php echo $content->cell_comments($thiscell->contentid); ?></li> -->
								 <li><span class="stat-like"></span><?php echo $content->cell_likes($thiscell->contentid, $thiscell->curationid); ?></li> 
							</ul>
						</div>
					</div>
					<?php 
						}
					else if($counter <= $per_page and !$skipthiscomment and $not_in_my_thread_array and $thiscell->content_type == 1 or $thiscell->content_type == 2)
						{
						$counter++;
						//display the threads cell...
						if($i == 1)
							echo '<div class="tile thread grid_3 alpha">';
						else if($i == 2)
							echo '<div class="tile thread grid_3">';
						else
							{
							echo '<div class="tile thread grid_3 omega">';
							$i = 0;
							}
						$i++;  //counts the row of 3 cells
							
							$commentbody = $comment->body;
							if(strlen($comment->body) < 35)
								$commentbody = $commentbody . '<br><br><br>';
							else if(strlen($comment->body) < 60)
								$commentbody = $commentbody . '<br><br>';
							else if(strlen($comment->body) < 90)
								$commentbody = $commentbody . '<br>';
							else if(strlen($comment->body) > 120)
								{
								$commentbody = substr($comment->body,0,160)."...";
								}
							if($commenttitle == "Blank Title" and $comment->contentid != 0)
								{
								//go get the content Title
								$thiscontent = Cell::find_by_id($comment->contentid);
								if(strlen($thiscontent->title) < 62)
									$commenttitle = $thiscontent->title;
								else
									$commenttitle = substr($thiscontent->title, 0, 59) . "...";
								}
							echo '<h4 class="title"><a href="thread.php?contentid='.$thiscell->contentid.'&curationid='.$maincurationid.'&commentid='.$maincommentid.'">'.$commenttitle.'</a></h4>';
							echo '<p class="comment"><a href="thread.php?contentid='.$thiscell->contentid.'&curationid='.$maincurationid.'&commentid='.$maincommentid.'">'.$commentbody.'</a></p>';
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
									$find_level = User_levels::find_by_id($persona->userguid);
									$level_array = calculate_level($persona, $find_level);
									echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
									?>
								</div>
								<? 
								$following = false;
								$following = User_follows::get_followers($persona->userguid);
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
									<? 
									$time = diff_times($comment->comment_date_added); 
									if(substr($time,0,2) != '1 ')
										$add = 's';
									else
										$add = '';
									?> 
									<h4><? echo  $time.$add. ' ago'; ?></h4>
								</div>
								<div class="comments">
									<p>Comments</p>
									<h4><?php echo number_format($comment->count_comments_new($maincommentid)); ?></h4>
								</div>
							</div>
							<div class="stats">
								<ul>
									<? 
									if(isset($parentparent))
										$time = diff_times($parentparent->comment_date_added); 
									else if(isset($parentcomment))
										$time = diff_times($parentcomment->comment_date_added); 
									else 
										$time = diff_times($comment->comment_date_added); 
										
									if(substr($time,0,2) != '1 ')
										$add = 's';
									else
										$add = '';
									?> 
									<li><span class="stat-date"></span><?php echo $time.$add; ?></li>
									<li><span class="stat-persona"></span><?php echo number_format($comment->count_users_on_thread($maincommentid)); ?></li>
								</ul>
							</div>
						</div>
						<?
						
						//end of thread cell view
						}
					//endforeach;
				endforeach;
				?>
		

	</div>

</div>
<!-- END CONTENT -->
	
	</div>
	<!-- END PAGE -->

</div>

</body>
</html>