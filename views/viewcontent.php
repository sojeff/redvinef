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
				
				<!-- SPOTLIGHT INFO -->
				<div class="spotlight-info">
					
					<div class="info-section">
					
						<!-- QUADRANT 1 -->
						<div class="info-quad1">
							<h5>Discover Date</h5>
							<h4>
							<? 
							$time = diff_times($cell->date_created); 
							if(substr($time,0,2) != '1 ')
								$add = 's';
							else
								$add = '';
							echo $time.$add;
							?> ago</h4>
						</div>
						
						<!-- QUADRANT 2 -->
						<div class="info-quad2">
							<h5>Comments</h5>
							<h4><?php echo number_format($cell->cell_comments($cell->contentid)); ?></h4>
						</div>
					</div>

					<div class="info-section">
						
						<!-- QUADRANT 3 -->
						<div class="info-quad3">
							<h5>Likes</h5>
							<h4><?php echo number_format($cell->cell_likes($cell->contentid, $session->curationid)); ?></h4>
						</div>
						
						<!-- QUADRANT 4 -->
						<div class="info-quad4">
							<h5>Tags</h5>
							<h4><?php echo number_format($thiscuration->tag_count); ?></h4>
						</div>
						
					</div>

					<!-- PERSONA -->
					<div class="info-section persona">
						<h5>Discovered by</h5>
						<div class="mini-persona">
							<div class="thumb">
									<?php 
									if($discoverer->avatar != '')
										echo '<a href="persona.php?user_id='.$discoverer->userguid.'"><img src="retrieveUserAvatar.php?id='.$discoverer->userguid.'" alt="" width="36" height="36" /></a>'; 
									else
										echo '<a href="persona.php?user_id='.$discoverer->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="36" height="36" /></a>';
									?>
								</div>
							<div class="info">
								<? echo '<h4><a href="persona.php?user_id='.$discoverer->userguid.'">'.Jumppad::get_user_name($cell->contentid); ?></a></h4>
								<? echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>'; ?>
							</div>
							<div class="button-follow square">
							<? 
							$following = false;
							$following = User_follows::get_followers($discoverer->userguid);
							if(!$following)
								echo '<a href="viewcontent.php?follow='.$discoverer->userguid.'">Follow</a>'; 
							else
								echo '<a class="active" href="viewcontent.php?stopfollow='.$discoverer->userguid.'">Follow</a>'; 
							
							?>
						</div>
						</div>
						
					</div>

	
						<? 
							$celltitle = $cell->title;
							if(strlen($celltitle) > 62)
								$celltitle = substr($celltitle, 0, 60) . '...';
							//  ***** liar ****** don't need to cut the title here because it will just stretch down the screen
							//if(strlen($celltitle) > 103)
							//	$celltitle = substr($celltitle,0,100) . '...';
						?>
						<!-- SOURCE -->
						<div class="info-section source">
							<h5>Original Source</h5>
						<!--	<? if($cell->source_website != '') 
								echo '<h4>'.$cell->source_website.'</h4>'; ?> -->
							
							<h4><span class="icon-link"></span><? echo '<a href="'.$cell->url.'" target="new">'; echo $celltitle; ?></a></h4>
						</div>
						

						<div class="info-section thread">
						<?
						if($thecomments)
						{
						foreach($thecomments as $thecomment):
							$whoemail = User::find_by_id($thecomment->userguid);
							$whoemail_levels = User_levels::find_by_id($thecomment->userguid);
							$level_array = calculate_level($whoemail, $whoemail_levels);
							?>
							<!-- TILES -->
								<h5>Discussion Thread</h5>
								<div class="tile thread grid_3">
									<?
									if($thecomment->title == '')
										$thecomment->title = $cell->title;
									if(strlen($thecomment->title) > 55)
										$thecomment->title = substr($thecomment->title,0,52) . '...';
										
									$commentbody = $thecomment->body;
									if(strlen($commentbody) < 35)
										$commentbody = $commentbody . '<br><br>';
									else if(strlen($commentbody) < 70)
										$commentbody = $commentbody . '<br>';
									else if(strlen($commentbody) < 105)
										$commentbody = $commentbody . '';
									if(strlen($commentbody) > 109)
										$commentbody = substr($commentbody,0,109) . '...';
									?>
									<? echo '<h4 class="title"><a href="thread.php?commentid='.$thecomment->commentid.'&contentid='.$cell->contentid.'&curationid='.$session->curationid.'">'. $thecomment->title; ?></a></h4>
									<? echo '<p class="comment"><a href="thread.php?commentid='.$thecomment->commentid.'&contentid='.$cell->contentid.'&curationid='.$session->curationid.'">'. $commentbody; ?></a></p>
									<div class="mini-persona">
										<div class="thumb">
											<?php 
											if($whoemail->avatar != '')
												echo '<a href="persona.php?user_id='.$whoemail->userguid.'"><img src="retrieveUserAvatar.php?id='.$whoemail->userguid.'" alt="" width="36" height="36" /></a>'; 
											else
												echo '<a href="persona.php?user_id='.$whoemail->userguid.'"><img src="../theme/ui/icons/icon-avatar.png" alt="" width="36" height="36" /></a>';
											?>
										</div>
										<div class="info">
											<? echo '<h4><a href="persona.php?user_id='.$whoemail->userguid.'">'.$whoemail->name ?></a></h4>
											<? echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>'; ?>
										</div>
										<div class="button-follow square">
											<a href="#">Follow</a>
										</div>
									</div>
										<?
										$time = diff_times($thecomment->comment_date_added); 
										if(substr($time,0,2) != '1 ')
											$add = 's';
										else
											$add = '';
										
										?>
									<div class="data">
										<div class="recent">
											<p>Recent Post</p>
											<h4><? echo diff_times($thecomment->comment_date_added).$add; ?> ago</h4>
										</div>
										<div class="comments">
											<p>Comments</p>
											<h4><?php echo number_format($cell->cell_comments($cell->contentid)); ?></h4>
										</div>
									</div>
									<div class="stats">
										<ul>
											<li><span class="stat-date"></span><?php echo $time.$add; ?></li>
											<li><span class="stat-persona"></span><?php echo number_format($thecomment->count_users_on_thread($thecomment->commentid)); ?></li>
										</ul>
									</div>
								</div>
								
						<?
						endforeach;
						}
						?>
					</div>
					
				</div>
				<!-- END SPOTLIGHT INFO -->
				<div class="button wide mtop">
				<? 
				if(!$thecomments)
					echo '<a class="thread" href="#"><span class="label-icon"><span class="add"></span>Create Threads</span></a>';
				?>
				</div>
			</section>
			<!-- END SPOTLIGHT -->
	<!-- RELATED -->
	<!--
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
		-->

			<!-- TAGS -->
			<section class="tags">
				<hgroup>
					<h3>Top Tags</h3>
				</hgroup>
				<ul class="nav tags">
					<?php 
					$trend_class = '';
					$count_trends = count($get_tag_trends);
					$i = 1; //6 elite minds.. last one changes to: <div class="block-persona last">
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
						if($likeUnlike == 'unlike')
							$current = ' active';
					 	echo '<li title="'.$likeUnlike.'"><a class="like'.$current.'" href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'&like=Y">Like</a></li>'; 
						$current = '';
						if($flagUnflag > 0)
							$current = ' active';
						echo '<li title="'.$flagUnflag.'"><a class="flag'.$current.'" href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'&flag=Y" >Flag</a></li>'; 
						?>
					<li><a class="tag" href="#">Tag</a></li>
			<!--		<li>
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
				<form method="post" action="viewcontent.php">
					<p>Add Keywords as Tags.  Seperate each Keyword with a <strong>Comma</strong> or <strong>Space</strong>.</p>
					<div><input class="tags" name="addtags" type="text" value="" /></div>
					<div class="button input orange"><input type="submit" value="Add Tags" /></div>
				</form>
			</div>
			
			<!-- CONTENT BLOCK -->
			<div id="content-article" class="clearfix">
				<?
				//if(!$h3tag)
				//	{
					echo '<h3 class="title">';
					echo $cell->title; 
					echo '</h3>';
				//	}
				?>
				<div section="txt" contentscore="7439">
				<?
				echo $myframe; 
				//echo $imageURL;
				?>
				</div>
			</div>
			
		</div>
		<!-- END CONTENT -->
		<div id="footer" class="wrapper clearfix">
		<!-- FOOTER -->
			<footer class="container">
				<?php if($session->user_id == 1004833642) {
				echo "<p>msg: " . $session->message . "</p><br/>"; 
				echo "<p>sql: " . $sql_topics . "</p><br/>"; 
				}?>
				<!-- <p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p> -->
			</footer>
			<!-- FOOTER END -->
	
		</div>
	<!-- END PAGE -->
	

	</div>
<!-- END META -->
</div>


<!-- CREATE MODAL -->
<div id="modal-create">
	<h4>Create a New Thread</h4>
	<form method="post" action="thread.php" class="clearfix">
		<input type="hidden" name="newthread" id="newthread" value="Y">
		<input type="hidden" name="relcontent" id="relcontent" <? echo 'value="'.$cell->contentid.'"'; ?> >
		
		<div class="block-field">
			<input class="title" name="title" type="text" <? echo 'value="'.$cell->title.'"'; ?> />
		</div>
		
		<div class="block-field">
			<div class="button gray simplemodal-close">
				<a class="" href="">
					<span class="label">Cancel</span>
				</a>
			</div>
			<div class="button orange input">
				<input type="submit" value="Create" />
			</div>
		</div>
		
	</form>
</div>
<!-- CREATE MODAL -->
<div id="modal-connect">
	<h4>Connect this Content to Another Topic</h4>
	<form method="" action="" class="clearfix">
		
		<div class="block-field">
			<input class="topic" name="topic" type="text" value="" />
		</div>
		
		<div class="block-field">
			<div class="button gray simplemodal-close">
				<a class="" href="">
					<span class="label">Cancel</span>
				</a>
			</div>
			<div class="button orange input">
				<input type="submit" value="Connect" />
			</div>
		</div>
		
	</form>
</div>

<!-- CREATE MODAL  -->
</body>

</html>

