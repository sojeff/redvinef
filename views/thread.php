<div id="page-wrap" class="clearfix">

	<!-- PAGE -->
	<div id="page" class="container_12">
	
		<!-- SIDEBAR -->
		<aside class="grid_3">

	<!-- SPOTLIGHT -->
			<section class="spotlight">
				<hgroup>
					<h3>Spotlight</h3>
				</hgroup>
	
	<!-- INFO -->
				<div class="spotlight-info">

					<div class="info-section">
					
						<!-- STATS -->
						<div class="info-section">
							<div class="info-quad1">
							<h5>Recent Post</h5>
							<? 
							if(isset($last_comment_date_added))
								{
								echo '<h4>'.diff_times($last_comment_date_added).' ago</h4>'; 
								}
							else
								{
								echo '<h4>None</h4>';
								}
							?>
						</div>
						
						<!-- QUADRANT 2 -->
						<div class="info-quad2">
							<h5>Comments</h5>
							<?
							if(isset($countcomments))
								echo '<h4>'. number_format($countcomments) . '</h4>';
							else
								echo '<h4>0</h4>';
							?>
						</div>
					
					</div>

					<div class="info-section">
						
						<!-- QUADRANT 3 -->
						<div class="info-quad3">
							<h5>Likes</h5>
							<?
							if(isset($countcommentlikes))
								echo '<h4>' . number_format($countcommentlikes) . '</h4>';
							else
								echo '<h4>0</h4>';
							?>
						</div>
						
						<!-- QUADRANT 4 -->
						<div class="info-quad4">
							<h5>Followers</h5>
							<?
							if(isset($countcommentfollows))
								echo '<h4>' . number_format($countcommentfollows) . '</h4>';
							else
								echo '<h4>0</h4>';
							?>
						</div>
						
					</div>

					<? if(!$create_thread and isset($discoverer))
					{
					?>
						
						<!-- PERSONA -->
						<div class="info-section persona">
							<h5>Created by</h5>
							<div class="mini-persona">
								<div class="thumb"><? echo '<a href="persona.php?user_id='.$discoverer->userguid.'">';
									if($discoverer->avatar != '')
										echo '<a href="persona.php?user_id='.$discoverer->userguid.'"><img src="retrieveUserAvatar.php?id='.$discoverer->userguid.'" alt="" width="36" height="36" /></a>'; 
									else
										echo '<a href="persona.php?user_id='.$discoverer->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="36" height="36" /></a>';
								?>
								</div>
								<div class="info">
									<? echo '<h4><a href="persona.php?user_id='.$discoverer->userguid.'">'.$discoverer->name.'</a></h4>';
									$find_level = User_levels::find_by_id($discoverer->userguid);
									$level_array = calculate_level($discoverer, $find_level);
									echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
								     ?>
								</div>
							<? 
							$following = false;
							$following = User_follows::get_followers($discoverer->userguid);
							if(!$following and $discoverer->userguid != $session->user_id)
								echo '<div class="button-follow square"><a href="thread.php?commentid='.$session->commentid.'&follow='.$discoverer->userguid.'">Follow</a></div>'; 
							else
								echo '<div class="button-follow square"><a class="active" href="thread.php?commentid='.$session->commentid.'&stopfollow='.$discoverer->userguid.'">Follow</a></div>'; 
							?>
							</div>
						</div>
					<? } ?>
					
					<div class="info-section source">
					
						<!-- TILES -->
						<? 
						if($cell_selected)
							{
							echo '<h5>Referenced Content</h5>';
							
								echo '<div class="tile content grid_3">';
								if(strlen($cell->title) > 55)
									$cell->title = substr($cell->title, 0, 55) . '...';
									$cellurl = substr(str_replace("www.","",parse_url($cell->url,PHP_URL_HOST)),0,28);					
									echo '<h4 class="title"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'">'.$cellurl.'</a></h4>';
									echo '<div class="thumb"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'">';
									if($image_id = $cell->cell_image($cell->contentid)) 
										{
										$width = $cell->cell_image_size($cell->contentid);
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
												echo '<img src="retrieveFile.php?id='.$image_id.'" alt="Cell-" width="228" height="'.$y2.'"></a>'; 
												}
											}
										else
											{
											echo '<img src="retrieveFile.php?id='.$image_id.'" alt="Cell-" width="228" height="128"></a>'; 
											}
										}
									else
										{
										echo '<img src="img/cells/thumb.png" alt="Cell-" width="228" height="128"></a>'; 
										}
									$time = diff_times($cell->date_created); 
									if(substr($time,0,2) != '1 ')
										$add = 's';
									else
										$add = '';
									echo '<p class="excerpt"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'">'.$cell->title.'</a></p>';
									echo '<div class="stats">';
										echo '<ul>';
								 			echo '<li><span class="stat-date"></span>'.$time.$add.'</li>';
								 			echo '<li><span class="stat-like"></span>'.$cell->cell_likes($cell->contentid, $session->curationid).'</li>';
										echo '</ul>';
									echo '</div>';
								echo '</div>';
								
							}
						?>
					</div>
					
				</div>
				<!-- END SPOTLIGHT INFO -->
				
			</section>
			<!-- END SPOTLIGHT -->
			
			<? if(!$create_thread)
			{
			?>
			<!-- RELATED -->
			<?
			if(isset($related_content) and $related_content !== false)
				{
				?>
					<!-- RELATED -->
				<section class="related">
					<hgroup>
						<h3>Related</h3>
						<ul id="sort-related">
							<li><a class="content current" href="#">Content</a></li>
							<li><a class="threads" href="#">Threads</a></li>
						</ul>
					</hgroup>
				
					<div class="list content">
						<div class="list-block">
							<div class="thumb">
								<a href="#"><img src="img/ui/placeholder-thumb.png" alt="" /></a>
							</div>
							<h4><a href="#">Donec ullamcorper nulla non metus auctor fringilla...</a></h4>
						</div>
						<div class="list-block">
							<div class="thumb">
								<a href="#"><img src="img/ui/placeholder-thumb.png" alt="" /></a>
							</div>
							<h4><a href="#">Etiam porta sem malesuada magna mollis euismod...</a></h4>
						</div>
						<div class="list-block">
							<div class="thumb">
								<a href="#"><img src="img/ui/placeholder-thumb.png" alt="" /></a>
							</div>
							<h4><a href="#">Nullam id dolor id nibh ultricies vehicula ut id elit...</a></h4>
						</div>
					</div>
				
					<div class="list threads">
						<div class="list-block">
							<h4><a href="#">Donec ullamcorper nulla non metus auctor fringilla...</a></h4>
						</div>
						<div class="list-block">
							<h4><a href="#">Etiam porta sem malesuada magna mollis euismod...</a></h4>
						</div>
						<div class="list-block">
							<h4><a href="#">Nullam id dolor id nibh ultricies vehicula ut id elit...</a></h4>
						</div>
					</div>
				
				</section>


			<? }
			}
			?>
			
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
					foreach($get_tag_trends as $tag_trend): 
						$tagstrip = strtok($tag_trend->tags, ',');
						//$tagstrip = strtok($tagstrip, '|');
						$fulltag = $tagstrip;
						if(strlen($tagstrip) > 23)
							$tagstrip = substr($tagstrip, 0, 23) . '...';
						//echo '<li'.$trend_class.' title="'.$fulltag.'"><a href="viewcontent.php?contentid='.$tag_trend->contentid.'&curationid='.$tag_trend->curationid.'">'.$tagstrip.'</a><span class="stat">'.number_format(Jumppad::count_tags($tagstrip)).'</span></li>';
						echo '<li'.$trend_class.' title="'.$fulltag.'"><a href="cell.php?field-find='.$fulltag.'&curationid='.$tag_trend->curationid.'">'.$tagstrip.'</a><span class="stat">'.number_format(Jumppad::count_tags($fulltag)).'</span></li>';
						$i++;
						if($i == $count_trends)
							$trend_class = ' class="last"';
					endforeach; 
				?>
				</ul>
			</section>
		</aside>
		<!-- END SIDEBAR -->
		
		<!-- CONTENT -->
		<div id="content-wrap" class="grid_9">
		
			<!-- CONTENT HEADER -->
			<div id="content-header" class="clearfix">
				<ul id="nav-crumb">
					<li class="back"><a href="cell.php">Back</a></li>
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
				<ul id="nav-curate">
					<li class="nav-label">Curate</li>
					<?php
					$current = '';
					if(isset($session->commentid) and $session->commentid != 0)
					{
					if($likeUnlike == 'unlike')
						$current = ' active';
					echo '<li title="'.$likeUnlike.'"><a class="like'.$current.'" href="thread.php?likecommentid='.$session->commentid.'&commentid='.$session->commentid.'&curationid='.$session->curationid.'&like=Y">Like</a></li>'; 
					$current = '';
					if($flagUnflag > 0)
						$current = ' active';
					echo '<li title="'.$flagUnflag.'"><a class="flag'.$current.'" href="thread.php?flagcommentid='.$session->commentid.'&commentid='.$session->commentid.'&curationid='.$session->curationid.'&flag=Y" >Flag</a></li>'; 
					}
					else
					{
					?>
					<li><a class="like" href="#">Like</a></li>
					<li><a class="flag" href="#">Flag</a></li>
					<?
					}
					?>
					<li><a class="tag" href="#">Tag</a></li>
				<!--	<li>
						<a class="share" href="#">Share</a>
						<ul class="menu">
							<li><a class="connect" href="#">Connect</a></li>
							<li><a href="mailto:name@email.com?subject=Check out this Knowledge at SO:KNO&trade;&body=">Email</a></li>
						</ul>
						<span class="icon-wedge"></span>
					</li>
					-->
				</ul>
			</div>
			<div id="form-tags" class="clearfix">
				<div class="icon-wedgetags"></div>
				<form method="post" action="thread.php">
					<p>Add Keywords as Tags.  Seperate each Keyword with a <strong>Comma</strong> or <strong>Space</strong>.</p>
					<div><input class="tags" type="text" name="addtags" id="addtags" value="" /></div>
					<input type="hidden" name="commentid" <? echo 'value="'.$session->commentid.'"'; ?> />
					<div class="button input orange"><input type="submit" value="Add Tags" /></div>
				</form>
			</div>
			
			<!-- CONTENT BLOCK -->
			<div id="content-thread" class="clearfix">

				<!-- THREAD BLOCK -->
				<h3 class="title"><? if(isset($parent_title) and $parent_title != '') echo stripslashes($parent_title); ?></h3>
				<div id="form-thread">
					<form method="post" action="thread.php">
						<div id="thread-input">
							<textarea class="comment" name="field-comment" id="field-comment" label="Enter Comment"></textarea>
							<?
							echo '<input type="hidden" name="parentid" id="parentid" value="'.$parent_commentid.'" />'; 
							echo '<input type="hidden" name="parent_title" id="parent_title" value="'.stripslashes($parent_title).'" />'; 
							echo '<input type="hidden" name="relcontent" id="relcontent" value="'.$relcontent.'" />'; 
							?>
							<input class="link" name="link" id="link" type="text" value="" />
							<input class="content" name="content" id="content" type="text" value="" />
						</div>
						<div id="thread-link">
							<input class="button-link" type="button" />
							<!-- <input class="button-content" type="button" /> -->
						</div>
						<div class="button orange input">
							<input type="submit" name="button-addcomment" id="button-addcomment" value="Add Comment" />
						</div>
					</form>
				</div>
			</div>
			
			<!-- COMMENT BLOCK -->
			<?
			if($parent_commentid != 0)
				{
				?>
			<div id="content" class="clearfix">
				<div class="comment-block">
					<div class="comment-persona">
					<?
					if($discoverer->avatar != '')
						echo '<a href="persona.php?user_id='.$discoverer->userguid.'"><img src="retrieveUserAvatar.php?id='.$discoverer->userguid.'" alt="" width="60" height="60" /></a>'; 
					else
						echo '<a href="persona.php?user_id='.$discoverer->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="60" height="60" /></a>';
					?>
					</div>
					<div class="icon-wedgethread"></div>
					<div class="comment-post">
						<div class="post-header">
							<div class="post-persona">
								<? echo '<h4><a href="persona.php?user_id='.$thecomments->userguid.'">'.$discoverer->name.'</a></h4>'; ?>
								<? echo '<p class="persona-class" title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>'; ?>
							</div>
							<ul class="nav-comment">
								 <?
								 $likeactive = '';
								 $flagactive = '';
								 $thislikeunlike = 'like';
								 $this_user_likes = User_likes_flags::comment_liked($thecomments->commentid);				 
								 if($this_user_likes and $this_user_likes->like_unlike == 1) //didn't find the user like record
									{
									$thislikeunlike = 'unlike';
									$likeactive = ' active';
									}
								 else
									{
									$thislikeunlike = 'like';
									}
								
								$following = false;
								$following = User_follows::get_followers($thecomments->userguid);
								if(!$following and $thecomments->userguid != $session->user_id and 1==2)
									echo '<div class="button-follow square"><a href="thread.php?commentid='.$thecomments->commentid.'&follow='.$thecomments->userguid.'">Follow</a></div>'; 
								else if(1==2)
									echo '<div class="button-follow square"><a class="active" href="thread.php?commentid='.$thecomments->commentid.'&stopfollow='.$thecomments->userguid.'">Follow</a></div>'; 
								
							//	 echo '<li><a class="like'.$likeactive.'" href="thread.php?likecommentid='.$thecomments->commentid.'&commentid='.$session->commentid.'&like='.$thislikeunlike.'">Like</a></li>';
							//	 echo '<li><a class="flag'.$flagactive.'" href="thread.php?flagcommentid='.$thecomments->commentid.'&commentid='.$session->commentid.'&flag=flag">Flag</a></li>';
							//	 echo '<li><a class="reply" href="#">Reply</a></li>';
							/*	 echo '<li>
										<a class="share" href="#">Share</a>
										<ul class="menu">
											<li><a href="#">Email</a></li>
										</ul>
											<span class="icon-wedge"></span>
									</li>';
							*/
								 ?>
							</ul>
						</div>
					<div class="post-content">
						<p><? if(isset($parent_body) and $parent_body != '') echo stripslashes(str_replace("\r","<br/>",$parent_body)); ?></p>
					</div>
					<? if(isset($parent_link) and $parent_link != '') 
						{
						echo '<div class="post-link">';
						echo '	<div class="link-source">';
						echo '		<span class="icon-link"></span>';
						echo '			<a href="'.$parent_link.'" target="_new">' . $parent_link . '</a>'; 
						echo '	</div>';
						echo '</div>';
						}
					else if(isset($thecomments->link) and $thecomments->link != '')
						{
						echo '<div class="post-link">';
						echo '	<div class="link-source">';
						echo '		<span class="icon-link"></span>';
						echo '			<a href="'.$thecomments->link.'" target="_new">' . $thecomments->link . '</a>'; 
						echo '	</div>';
						if(isset($thecomments->linked_content_id) and 
								 $thecomments->linked_content_id != '' and 
								 $thecomments->linked_content_id != 0)
							{
							echo '		<div class="link-content">';
							echo '			<span class="icon-content"></span>';
							echo '			<span><a href="#">{dynamic-jumppad}</a> &rsaquo;</span>';
							echo '			<span><a href="#">{dynamic-knowcell}</a> &rsaquo;</span>';
							echo '			<span>{dynamic-name}</span>';
							echo '		</div>';
							echo '</div>';
							}
						else
							echo '</div>';
						}
						
						?>
						<div class="stats">
							<ul>
								<? 
								echo '<li><span class="stat-date"></span>'.diff_times($thecomments->comment_date_added).'</li>';
								$thiscountcommentlikes = Comments::count_comment_likes($thecomments->commentid);										
								echo '<li><span class="stat-like"></span>'. number_format($thiscountcommentlikes). '</li>'; ?>
							</ul>
						</div>
					</div>
				</div>
				<?
				if(isset($thesubcomments))
				{
						
				foreach($thesubcomments as $subcomment):
				$thiscuration = Jumppad::find_by_id($subcomment->curationid);
				if($thiscuration->flags < 3)
				{
				$userthiscomment = User::find_by_id($subcomment->userguid);
				 $likeactive = '';
				 $flagactive = '';
				 $thislikeunlike = 'like';
				 $this_user_likes = User_likes_flags::comment_liked($subcomment->commentid);				 
				 if($this_user_likes and $this_user_likes->like_unlike == 1) //didn't find the user like record
					{
					$thislikeunlike = 'unlike';
					$likeactive = ' active';
					}
				 else
					{
					$thislikeunlike = 'like';
					}
				echo '<div id="content" class="clearfix">';
				echo '<div class="comment-block">';
				echo '<div class="comment-persona">';
					if($userthiscomment->avatar != '')
						echo '<a href="persona.php?user_id='.$userthiscomment->userguid.'"><img src="retrieveUserAvatar.php?id='.$userthiscomment->userguid.'" alt="" width="60" height="60" /></a>'; 
					else
						echo '<a href="persona.php?user_id='.$userthiscomment->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="60" height="60" /></a>';
				?>
					</div>
					<div class="icon-wedgethread"></div>
					<div class="comment-post">
						<div class="post-header">
							<div class="post-persona">
								<? 
								echo '<h4><a href="persona.php?user_id='.$subcomment->userguid.'">'.$userthiscomment->name.'</a></h4>';
								if($subcomment->userguid == $thecomments->userguid) //already know owner's stats
									echo '<p class="persona-class" title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
								else
									{
									$find_level = User_levels::find_by_id($subcomment->userguid);
									$level_array2 = calculate_level($subcomment, $find_level);
									echo '<p class="persona-class" title="Level '.ucwords($level_array2[0]).' Stage '.ucwords($level_array2[1]).' '.ucwords($level_array2[2]).'"><span class="level'.$level_array2[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array2[2]).'</p>';
									}
								?>
							</div>
							<ul class="nav-comment">
								<?
								$following = false;
								$following = User_follows::get_followers($subcomment->userguid);
								if(!$following and $subcomment->userguid != $session->user_id and 1==2)
									echo '<div class="button-follow square"><a href="thread.php?commentid='.$subcomment->commentid.'&follow='.$subcomment->userguid.'">Follow</a></div>'; 
								else if(1==2)
									echo '<div class="button-follow square"><a class="active" href="thread.php?commentid='.$subcomment->commentid.'&stopfollow='.$subcomment->userguid.'">Follow</a></div>'; 
								// echo '<li><a class="like'.$likeactive.'" href="thread.php?likecommentid='.$subcomment->commentid.'&like='.$thislikeunlike.'">Like</a></li>';
								// echo '<li><a class="flag'.$flagactive.'" href="thread.php?flagcommentid='.$subcomment->commentid.'&flag=flag">Flag</a></li>';
								?>
								<!--<li><a class="reply" href="#">Reply</a></li>
								<li>
									<a class="share" href="#">Share</a>
									<ul class="menu">
										<li><a href="#">Email</a></li>
									</ul>
									<span class="icon-wedge"></span>
								</li>
								-->
							</ul>
						</div>
						<div class="post-content">
							<? echo '<p>'.stripslashes(str_replace("\r","<br/>",$subcomment->body)).'</p>'; ?>
						</div>
						<? 
						if(isset($subcomment->link) and $subcomment->link != '')
						{
						?>
						<div class="post-link">
							<div class="link-source">
								<span class="icon-link"></span>
								<? echo '<a href="#">'.$subcomment->link.'</a>'; ?>
							</div>
							<div class="link-content">
								<span class="icon-content"></span>
								<span><a href="#">{dynamic-jumppad}</a> &rsaquo;</span>
								<span><a href="#">{dynamic-knowcell}</a> &rsaquo;</span>
								<span>{dynamic-name}</span>
							</div>
						</div>
						<? 
						} 
						?>
						<div class="stats">
							<ul>
								<? 
								echo '<li><span class="stat-date"></span>'.diff_times($subcomment->comment_date_added).'</li>';
								$thiscountcommentlikes = Comments::count_comment_likes($subcomment->commentid);										
								echo '<li><span class="stat-like"></span>'. number_format($thiscountcommentlikes). '</li>'; ?>
							</ul>
						</div>
					</div>
				</div>
				</div>
				<?
				}
				endforeach;
				}
			}
			?>
<!--			
		<div class="comment-block reply">
				<div class="comment-persona"><img src="../views/img/persona/image-persona6-m.png" alt="" /></div>
				<div class="icon-wedgethread"></div>
				<div class="comment-post">
					<div class="post-header">
						<div class="post-persona">
							<h4><a href="#">{dynamic-name}</a></h4>
							<p class="persona-class"><span class="level3 stage2">{#}</span>{dynamic-class}</p>
						</div>
						<ul class="nav-comment">
							<li><a class="like" href="#">Like</a></li>
							<li><a class="flag" href="#">Flag</a></li>
							<li><a class="reply" href="#">Reply</a></li>
							<li class="last"><a class="share" href="#">Share</a></li>
						</ul>
					</div>
					<div class="post-content">
						<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, #Tropical Fish adipiscing elit. Nullam quis risus eget urna mollis ornare vel eu leo. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
					</div>
					<div class="post-link">
						<div class="link-source">
							<span class="icon-link"></span>
							<a href="#">http://so.kno.com/123456</a>
						</div>
						<div class="link-content">
							<span class="icon-content"></span>
							<span><a href="#">{dynamic-jumppad}</a> &rsaquo;</span>
							<span><a href="#">{dynamic-knowcell}</a> &rsaquo;</span>
							<span>{dynamic-name}</span>
						</div>
					</div>
					<div class="post-stats">
						<ul>
							<li><span class="stat-date"></span>{##}</li>
							<li><span class="stat-like"></span>{##}</li>
						</ul>
						<div class="button-followsmall"><a href="#">Follow</a></div>
					</div>
				</div>
			</div>
	-->				
		</div>
		<!-- END CONTENT -->
	</div>
	
	</div>
	<!-- END PAGE -->
	</div>	
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->user_id == 1004833642) {
				echo "<p>" . $session->message . "</p><br/>"; 
				echo "<p>session->commentid: " . $session->commentid . "</p><br/>"; 
				//echo "<p>thread->commentid: " . $thread->commentid . "</p><br/>"; 
				echo "<p>cell_selected: " . $cell_selected . "</p><br/>"; 
				echo "<p>parent commentid: " . $parent_commentid . "</p><br/>"; 
				if(isset($thecomments))
					echo "<p>CELL: " . print_r($thecomments) . "</p><br/>"; 
				}?>
				<!-- <p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p> -->
			</footer>
			<!-- FOOTER END -->

<!-- CREATE MODAL -->
<div id="modal-create">
	<h4>Create a New Thread</h4>
	<div class="create-form">
		<form method="post" action="thread.php">
			<input class="field-title" type="text" name="field-title" value="" />
			<div class="alert">That Thread Title Already Exists. <a href="#">View that Thread Now</a></div>
			<textarea class="field-post" name="field-post"></textarea>
			<input class="field-link" name="field-link" type="text" value="" />
			<input class="field-content" name="field-content" type="text" value="" />
			<div class="create-buttons">
				<!--<div class="create"><a href="#">Create</a></div>-->
				<div class="create"><input type="submit" name="createthread" value="Create"></div>
				<div class="cancel simplemodal-close"><a href="">Cancel</a></div>
			</div>
		</form>
	</div>
</div>



</body>

</html>
