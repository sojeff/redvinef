	<!-- PAGE -->
<div id="page-wrap" class="clearfix">

	<!-- PAGE -->
	<div id="page" class="container_12">
	
		<!-- SIDEBAR -->
		<aside class="grid_3">
		
			<!-- KNOWLEDGE -->
			<section class="knowledge">
				<hgroup>
					<h3>Knowledge</h3>
				</hgroup>
				<ul class="nav">
					<li><a <?php echo $knocell_view_all_active;?> href="cell.php?knocell_view=All">All</a><span class="stat"><? echo number_format($count_all); ?></span></li>
					<li><a <?php echo $knocell_view_content_active;?> href="cell.php?knocell_view=Content">Content</a><span class="stat"><? echo number_format($count_content); ?></span></li>
					<li><a <?php echo $knocell_view_personas_active;?> href="cell.php?knocell_view=People">People</a><span class="stat"><? echo number_format($count_people); ?></span></li>
					<li><a <?php echo $knocell_view_threads_active;?> href="cell.php?knocell_view=Threads">Threads</a><span class="stat"><? echo number_format($count_threads); ?></span></li>
				</ul>
				<div class="button wide mtop">
					<a class="thread" href="#"><span class="label-icon"><span class="add"></span>Create Thread</span></a>
				</div>
			</section>
			
			<!-- ELITE -->
			<section class="personas">
				<?
				if(count($find_elite) > 1)
				{
				?>
				<hgroup>
					<h3>Elite Minds</h3>
					<!--<span><a href="cell.php?followall=YES">Follow All</a></span>-->
				</hgroup>
				<?php 
				$debuglabel = "";
				foreach($find_elite as $elite): 
				$eliteuser = User::find_by_id($elite->userguid);
				$following = false;
				$following = User_follows::get_followers($elite->userguid);
				//compute the level

				$level_array = calculate_level($eliteuser, $elite);
				?>
				<div class="mini-persona">
					<div class="thumb">
						<?php 
						if($eliteuser->avatar != '')
							echo '<a href="persona.php?user_id='.$elite->userguid.'"><img src="retrieveUserAvatar.php?id='.$elite->userguid.'" alt="" width="36" height="36" /></a>'; 
						else
							echo '<a href="persona.php?user_id='.$elite->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="36" height="36" /></a>';
						?>
					</div>
					<div class="info">
						<?php
						echo '<h4><a href="persona.php?user_id='.$elite->userguid.'">'.$eliteuser->name.'</a></h4>';
						echo '<p class="persona-class" title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
						?>
					</div>
					<? 
					if(!$following and $elite->userguid != $user->userguid)
						echo '<div class="button-follow square"><a href="cell.php?follow='.$elite->userguid.'">Follow</a></div>'; 
					else
						echo '<div class="button-follow square"><a class="active" href="cell.php?stopfollow='.$elite->userguid.'">Follow</a></div>'; 
					?>
				</div>
				
				<?php 
				endforeach; 
				}
				?>
				
			</section>
			
			<!-- TAGS -->
			<section class="tags">
				<hgroup>
					<h3>Top Tags</h3>
				</hgroup>
				<ul class="nav tags">
				<?php 
				$trend_class = '';
				$count_trends = count($get_tag_trends);
				$block_persona = '<div class="block-persona">';
				$i = 1; //6 elite minds.. last one changes to: <div class="block-persona last">
				if($get_tag_trends)
				{
				foreach($get_tag_trends as $tag_trend): 
					$tagstrip = strtok($tag_trend->tags, ',');
					$tagstrip = strtok($tagstrip, '|');
					$fulltag = $tagstrip;
					if(strlen($tagstrip) > 23)
						$tagstrip = substr($tagstrip, 0, 23) . '...';
					//echo '<li'.$trend_class.' title="'.$fulltag.'"><a href="viewcontent.php?contentid='.$tag_trend->contentid.'&curationid='.$tag_trend->curationid.'">'.$tagstrip.'</a><span class="stat">'.number_format(Jumppad::count_tags($tagstrip)).'</span></li>';
					echo '<li'.$trend_class.' title="'.$fulltag.'"><a href="cell.php?field-find='.$fulltag.'&curationid='.$tag_trend->curationid.'">'.$tagstrip.'</a><span class="stat">'.number_format(Jumppad::count_tags($fulltag)).'</span></li>';
					$i++;
					if($i == $count_trends)
						$trend_class = ' class="last"';
				endforeach; 
				}
				?>
				</ul>
			</section>
		</aside>
		<!-- END SIDEBAR -->
		
		<!-- CONTENT -->
		<div class="grid_9" id="content-wrap">
		
			<!-- CONTENT HEADER -->
			<div class="clearfix" id="content-header">
				<ul id="nav-crumb">
					<li>
						<a href="jumppad.php"><?php echo ucwords($jumppadcrumb);?></a>
						<ul class="menu">
							<li><a <?echo $global_active;?> href="jumppad.php?user_view=global">Global</a></li>
							<li><a <?echo $personal_active;?> href="jumppad.php?user_view=personal">Personal</a></li>
							<?php if(isset($private_exists) and $private_exists != '') { ?>							
							<li class="header">Private JumpPads</li>
							<li><a <?echo $private_active;?> href="jumppad.php?user_view=private"><?echo $private_exists;?></a></li>
				 			<?php } //end of private exists... need to loop thru all the private jumpads user belongs to... ?>
						</ul>
						<span class="icon-wedge"></span>
					</li>
					<li><span class="icon-arrow">&rsaquo;</span></li>
					<li>
						<a class="current" href="#"><? echo $mytopic; ?></a>
						<ul class="menu wide">
							<ul class="col">
							<?
							//loop thru the jumppads for global or personal or private ($topicarray)
							$i = 1;
							$j = 1;
							foreach($topicarray as $thistopic):
								if($mytopic == $thistopic->topic)
									echo '<li><a  class="current" href="cell.php?user_knocell='.$thistopic->topicid.'">'.$thistopic->topic.'</a></li>';
								else
									echo '<li><a href="cell.php?user_knocell='.$thistopic->topicid.'">'.$thistopic->topic.'</a></li>';
								$i++;
								if($i == 15)
									{
									$i = 1;
									$j++;
									echo '</ul>';
									if($j < 5)
										echo '<ul class="col">';
									}
									
							endforeach;
							if($i < 15)
								echo '</ul>';
							?>
					</ul>
					<span class="icon-wedge"></span>
					</li>
				</ul>
				<ul id="nav-sort">
					<li class="nav-label">Sort</li>
					<li><a <?php echo $sort_date_active; ?> href="cell.php?user_sort=Date">Date</a></li>
					<!-- <li><a <?php echo $sort_new_active; ?> href="cell.php?user_sort=Content">New</a></li> -->
					<li><a <?php echo $sort_alpha_active; ?> href="cell.php?user_sort=Alphabetical">Alpha</a></li>
					<li><a <?php echo $sort_relevance_active; ?> href="cell.php?user_sort=Relevance">Relevance</a></li>
				</ul>
			</div>
			
			<!-- CONTENT TILES -->
			<div id="content" class="clearfix">
			
				<?php
				$user_array = array();
				$content_array = array();
				$thread_array = array();
				$width = 0;
				$length = 0;
				$i = 1;
				foreach($cells as $cell):
					
					//get the content or the comment
					//$sql2 = "select * from curations where contentid = ".$cell->contentid." and flags < 3 order by date_modified desc limit 2";
					//echo '<br/>sql2: '.$sql2;
					//$curations = Jumppad::find_by_sql($sql2);
					//foreach($curations as $curation):		
					$not_in_my_thread_array = true;
					$commenttitle = "Blank Title";
					$maincurationid = 0;  //set up variables for hyperlinks
					$maincommentid  = 0;
					//get the full curation record...
					if($cell->contentid != 0) //get the related content
						{
						$content = Cell::find_by_id($cell->contentid); 
						}
					if(($cell->content_type == 1 or $cell->content_type == 2) and $cell->commentid != 0) //comment
						{
						$comment = Comments::find_by_id($cell->commentid);
						
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
							if($cell->content_type == 2 and $parentcomment->parentid != 0) //need to get the parent/parent thread of sub comment
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
						
						// need to handle threads and standalone threads with no content id here!!
						$not_in_my_thread_array = true;
						if($comment->content_type == 1) //then it's a main or level 1 comment.
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
						else if($comment->content_type == 2) //then it's a sub-comment.
							{
							if($parentcomment->parentid != 0 and $parentparent->parentid != 0)
								{
								if(!in_array($parentcomment->parentid, $thread_array))
									$added = array_push($thread_array, $parentcomment->parentid);
								else
									$not_in_my_thread_array = false;
								}
							}
							
						}
					if($cell->userguid != 0) //get the related user information
						{
						$persona = User::find_by_id($cell->userguid);
						}
						
					$not_in_my_array = true;
					if(!in_array($cell->userguid, $user_array))
						$added = array_push($user_array, $cell->userguid);
					else
						$not_in_my_array = false;
						
					$not_in_my_content_array = true;
					if($cell->content_type == 0) //it's content URL.
						{
						if(!in_array($cell->contentid, $content_array))
							$added = array_push($content_array, $cell->contentid);
						else
							$not_in_my_content_array = false;
						}
					
			//*** Content 		
					if($not_in_my_content_array and $cell->contentid != 0 and $cell->content_type == 0 and ($myview == 'All' or $myview == 'Content')) //display the content cell
						{
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
						echo '<h4 class="title"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'&userguid='.$cell->userguid.'">' . $cellurl . '</a></h4>';
						echo '<div class="thumb" align="center"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'">';
						if($image_id = $content->cell_image($cell->contentid)) 
							{
							$width = $content->cell_image_size($cell->contentid);
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
							echo '<p class="excerpt" title="'.$content->title.'"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'">';
							
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
								<!-- <li><span class="stat-thread"></span><?php echo $content->cell_comments($cell->contentid); ?></li> -->
								 <li><span class="stat-like"></span><?php echo $content->cell_likes($cell->contentid, $cell->curationid); ?></li> 
							</ul>
						</div>
					</div>
					<?php 
						}
					else if($not_in_my_thread_array and ($myview == 'All' or $myview == 'Threads') and ($cell->content_type == 1 or $cell->content_type == 2))
						{
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
							else if(strlen($comment->body) >= 90)
								{
								$commentbody = substr($comment->body,0,90)."...";
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
					if($not_in_my_array and $cell->userguid != 0 and ($myview == 'All' or $myview == 'People'))
						{
						//get the person related to this content or comment.
						if($i == 1)
							echo '<div class="tile persona grid_3 alpha">';
						else if($i == 2)
							echo '<div class="tile persona grid_3">';
						else
							{
							echo '<div class="tile persona grid_3 omega">';
							$i = 0;
							}
						$i++;  //counts the row of 3 cells
						
						echo '<h4 class="title"><a href="persona.php?user_id='.$persona->userguid.'">' . $persona->name . '</a></h4>';
						echo '<div class="button-follow square">';
						$following = false;
						$following = User_follows::get_followers($persona->userguid);
						if(!$following and $persona->userguid != $user->userguid)
							echo '<a href="cell.php?follow='.$persona->userguid.'">Follow</a></div>'; 
						else
							echo '<a class="active" href="cell.php?stopfollow='.$persona->userguid.'">Follow</a></div>'; 
						echo '<div class="info">';
							echo '<div class="thumb">
								<a href="persona.php?user_id='.$persona->userguid.'">';
							if($persona->avatar != '')
								echo '<a href="persona.php?user_id='.$persona->userguid.'"><img src="retrieveUserAvatar.php?id='.$persona->userguid.'" alt="" width="70" height="70" /></a>'; 
							else
								echo '<a href="persona.php?user_id='.$persona->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="70" height="70" /></a>';
							echo '</div>';
							$find_level = User_levels::find_by_id($persona->userguid);
							$level_array = calculate_level($persona, $find_level);
							?>
							<div class="level">
								<? 
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
								
								echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'">'.ucwords($level_array[2]).'</p>'; ?>
							</div>
							<div class="location">
								<span class="icon-marker"></span>
								<p><? echo $persona->currentlocationcity.' '.$persona->currentlocationcountry ?></p>
							</div> 
						</div>
						<div class="data">
							<div class="followers">
								<p>Followers</p>
								<h4><? echo number_format(User_follows::count_followers($persona->userguid, 2), 0); ?></h4>
							</div>
							<div class="following">
								<p>Following</p>
								<h4><? echo number_format(User_follows::count_followers($persona->userguid, 1), 0); ?></h4>
							</div>
							<div class="curations">
								<p>Curations</p>
								<h4><? echo number_format(Jumppad::count_jumppads('personal', $persona->userguid,0), 0); ?></h4>
							</div>
						</div>
						<div class="data">
							<div class="knowledge">
								<p>Most Knowledge</p>
								<? 
								$ktopicid = Jumppad::knowledge_interest($persona->userguid, "K"); 
								if($ktopicid != 0)
									{
									$ktopic = Topic::find_by_id($ktopicid);
									$ktopicdesc = $ktopic->topic;
									}
								else
									$ktopicdesc = "None";
									
								echo '<h4 title="'.$ktopicdesc.'">'.substr($ktopicdesc,0,10).'</h4>'; 
								?>
							</div>
							<div class="interest">
								<p>Top Interest</p>
								<? 
								$itopicid = Userview::top_interest($persona->userguid, 1); 
								if($itopicid != 0)
									{
									$itopic = Topic::find_by_id($itopicid);
									$itopicdesc = $itopic->topic;
									}
								else
									$itopicdesc = "None";
									
								echo '<h4 title="'.$itopicdesc.'">'.substr($itopicdesc,0,10).'</h4>'; 
								?>
							</div>
						</div>
						<div class="stats">
							<ul>
								<? 
								$time = diff_times($persona->updated_time); 
								if(substr($time,0,2) != '1 ')
									$add = 's';
								else
									$add = '';
								?> 
								<li><span class="stat-date"></span><? echo $time.$add; ?></li>
								<li><span class="stat-thread"></span><? echo number_format(Comments::count_user_comments($persona->userguid), 0); ?></li>
							</ul>
						</div>
					</div>
					<?

						}
											
					?>
					<?
					//endforeach;
				endforeach;
				?>
				
			</div>
			
		</div>
		<!-- END CONTENT -->
			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->user_id == 1004833642) {
				echo "<p>" . $session->message . "</p><br/>"; 
				//echo "<p>CELL: " . print_r($cell) . "</p><br/>"; 
				//echo "<p>userarray: " . print_r($user) . "</p><br/>"; 
				}?>
				<!-- <p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p> -->
			</footer>
			<!-- FOOTER END -->
	
	</div>
	<!-- END PAGE -->
	

</div>
<!-- END META -->

<!-- CREATE MODAL -->
<div id="modal-create">
	<h4>Create a New Thread</h4>
	<form method="post" action="thread.php" class="clearfix">
		<input type="hidden" name="newthread" id="newthread" value="Y">
		<input type="hidden" name="relcontent" id="relcontent" value="N">
		
		<div class="block-field">
			<input class="title" name="title" type="text" value="" />
		</div>
		
		<div class="block-field">
			<div class="button gray simplemodal-close">
				<a class="" href="#">
					<span class="label">Cancel</span>
				</a>
			</div>
			<div class="button orange input">
				<input type="submit" value="Create" />
			</div>
		</div>
		
	</form>
</div>

</body>

</html>
			
