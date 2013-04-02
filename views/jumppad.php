	<div class="clearfix" id="page-wrap">
	<!-- PAGE -->
		<div class="container_12" id="page">
		
		<!-- SIDEBAR -->
		<aside class="grid_3">
		
			<!-- JUMPPADS -->
			<section class="jumppads">
				<hgroup>
					<h3>Knowledge</h3>
				</hgroup>
				<ul class="nav">
					<li>
						<a href="jumppad.php?user_view=global" <?php echo $global_current;?> >Global</a>
						<span class="stat"><?php echo number_format($global_count); ?></span>
					</li>
					<li>
						<a href="jumppad.php?user_view=personal" <?php echo $personal_current;?> >Personal</a>
						<span class="stat"><?php echo number_format($personal_count); ?></span>
					</li>
					<?php if(isset($private_exists) and $private_exists != '') { ?>
					<li class="header">Private</li>
					<li><a href="jumppad.php?user_view=private" <?php echo $private_current .' >'.$private_exists; ?></a>
						<?php if(isset($private_edit) and $private_edit != '') { ?>						
							<a class="edit" href="edit_private.php">edit</a>
				 		<?php } //end of private edit...  ?>
						<span class="stat"><?php echo number_format($private_count); ?></span>
					</li>
				 	<?php } //end of private exists... need to loop thru all the private jumpads user belongs to... ?>
				</ul>
				<!--
				<div class="button wide mtop">
					<a class="thread" href="#"><span class="label-icon"><span class="add"></span>Create Jumppad</span></a>
				</div>
				-->
			</section>
			<!-- ELITE -->
			<section class="personas">
				<hgroup>
					<h3>Elite Minds</h3>
					<span><a class="followall" href="jumppad.php?followall=YES">Follow All</a></span>
				</hgroup>
				<?php 
				$debuglabel = "";
				$i = 1; //6 elite minds.. last one changes to: <div class="block-persona last">
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
					echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
					?>
				</div>
				<? 
				if(!$following and $elite->userguid != $user->userguid)
					echo '<div class="button-follow square"><a href="jumppad.php?follow='.$elite->userguid.'">Follow</a></div>'; 
				else if($following and $elite->userguid != $user->userguid)
					echo '<div class="button-follow square"><a class="active" href="jumppad.php?stopfollow='.$elite->userguid.'">Follow</a></div>'; 
				?>
				</div>
				
				<?php 
				$i++;
				if($i >= 6)
					$block_persona = '<div class="mini-persona">';
				endforeach; 
				?>
				
			</section>
						
			<!-- TOPICS -->
			<section class="topics">
				<hgroup>
					<h3>Trending</h3>
					<ul id="sort-topics">
						<li><a href="#" class="content current">Content</a></li>
						<li><a href="#" class="threads">Threads</a></li>
					</ul>
				</hgroup>
				<ul class="list content">
				<?
				//if($session->trending == 'content')							
					//echo '<ul class="list content">';
				//else
					//echo '<ul class="list threads">';
					$i = 0;
					foreach($find_trending as $trends): 
					$trend = Topic::find_by_id($trends->topicid);
					if($i == 5)
						echo '<li class="last"><a href="cell.php?user_knocell='.$trends->topicid.'">'; 
					else
						echo '<li><a href="cell.php?user_knocell='.$trends->topicid.'">'; 
						if(strlen($trend->topic) > 25) 
						{ echo substr($trend->topic,0,25) ."..."; } 
						else { echo $trend->topic; }
						//if($session->trending == 'content')
						//	{
							$content_type = 0; //0 = content URL
							echo '</a><span class="stat">'.number_format(Jumppad::count_curations($trends->topicid, $content_type)).'</span></li>';
						//	}
						//else
						//	{
						//	$content_type = 1; //1 = Comments / Threads
						//	echo '</a><span class="stat">'.number_format(Jumppad::count_threads($trends->topicid)).'</span></li>';
						//	}
						$i++;
					endforeach;
					?>
				</ul>
				
				<ul class="list threads">
				<?
					$i = 0;
					foreach($find_trending as $trends): 
					$trend = Topic::find_by_id($trends->topicid);
					if($i == 5)
						echo '<li class="last"><a href="cell.php?user_knocell='.$trends->topicid.'">'; 
					else
						echo '<li><a href="cell.php?user_knocell='.$trends->topicid.'">'; 
						if(strlen($trend->topic) > 25) 
						{ echo substr($trend->topic,0,25) ."..."; } 
						else { echo $trend->topic; }
					//	if($session->trending == 'content')
					//		{
					//		$content_type = 0; //0 = content URL
					//		echo '</a><span class="stat">'.number_format(Jumppad::count_curations($trends->topicid, $content_type)).'</span></li>';
					//		}
					//	else
					//		{
							$content_type = 1; //1 = Comments / Threads
							echo '</a><span class="stat">'.number_format(Jumppad::count_threads($trends->topicid)).'</span></li>';
					//		}
						$i++;
					endforeach;
					?>
				</ul>
				
				<!--<ul class="nav-topics threads">
					<li><a href="#">{dynamic-topic}</a><span class="stat">{##}</span></li>
					<li><a href="#">{dynamic-topic}</a><span class="stat">{##}</span></li>
					<li><a href="#">{dynamic-topic}</a><span class="stat">{##}</span></li>
					<li><a href="#">{dynamic-topic}</a><span class="stat">{##}</span></li>
					<li class="last"><a href="#">{dynamic-topic}</a><span class="stat">{##}</span></li>
				</ul>  -->
			</section>
		</aside>
		<!-- END SIDEBAR -->
			
		<!-- CONTENT -->
		<div class="grid_9" id="content-wrap">
		
			<!-- CONTENT HEADER -->
			<div class="clearfix" id="content-header">
				<h3><?php echo ucwords($myview); ?></h3>
				<ul id="nav-sort">
					<li class="nav-label">Sort</li>
					<li><a <?php echo $sort_date_active; ?> href="jumppad.php?user_sort=Date">Date</a></li>
					<!--<li><a <?php echo $sort_new_active; ?> href="jumppad.php?user_sort=Content">New</a></li> -->
					<li><a <?php echo $sort_alpha_active; ?> href="jumppad.php?user_sort=Alphabetical">Alpha</a></li>
					<li><a <?php echo $sort_relevance_active; ?> href="jumppad.php?user_sort=Relevance">Relevance</a></li>
				</ul>
			</div>
			<!-- CONTENT TILES -->
			<div class="clearfix" id="content">
			
			
				<?php $counttopics = 0; ?>
				<?php foreach($jumppads as $jumppad): ?>
				<?php $topic = Topic::find_by_id($jumppad->topicid); ?>  
				<?php 
					$counttopics++;
					
					if($counttopics == 1) 
						$stripleft = '<div class="tile cell grid_3 alpha">';
					else if($counttopics == 2) 
						$stripleft = '<div class="tile cell grid_3">';
					else if($counttopics == 3) 
						{
						$stripleft = '<div class="tile cell grid_3 omega">';
						$counttopics = 0;
						}
						
					echo $stripleft;
				?>
					<h4><?php echo "<a href=\"cell.php?user_knocell={$jumppad->topicid}\" title=\"{$topic->topic}\" >"; if(strlen($topic->topic) > 25) { echo substr($topic->topic,0,25) ."..."; } else { echo $topic->topic; } ?></a></h4>
					<div class="thumb">
						<?php echo "<a href=\"cell.php?user_knocell={$jumppad->topicid}\" >";
							if($topic->topic_image == '') 
								echo '<img src="img/cells/default.png"'; 
							else
								echo "<img src=\"../../knocell_images/{$topic->topic_image}\""; 
							?> 
									alt="Cell-" width="228" height="128"></a> 
					</div>
					<div class="stats">
						<ul>
							<li title="# of Threads on this JumpPad."><span class="stat-thread"></span><?php echo number_format(Jumppad::count_threads($jumppad->topicid)); ?></li>
							<li title="# of Content pages on this JumpPad."><span class="stat-content"></span><?php echo number_format(Jumppad::count_curations($jumppad->topicid, 0)); ?></li>
							<li title="# of Personas curated to this JumpPad."><span class="stat-persona"></span><?php echo number_format(Jumppad::count_people($jumppad->topicid)); ?></li>
						</ul>
					</div>
				</div>
				<?php endforeach; ?>

			<!-- CONTENT END -->
			</div>
			</div>
			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->message != "" and ($session->user_id == 1004833642 or $session->user_id == 700000949195850)) {
				echo "<p>" . $session->message . "</p><br/>"; 
				echo "<p>User View: " . $session->user_view . "</p><br/>"; 
				echo "<p>User Sort: " . $session->user_sort . "</p><br/>"; 
				echo "<p> Elite: " . $debuglabel . "</p><br/>"; 
				echo "<p><pre>";
				print_r($_POST);
				echo "</pre></p>";
				}?>
				<!-- <p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p> -->
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
		</div>
	</div>
<!-- CREATE MODAL -->
<div id="modal-create">
	<h4>Create a New JumpPad</h4>
	<div class="form-modal">
		<form method="post" action="jumppad.php">
			<div class="block-field">
				<input class="field-title" name="field_jumppad" id="field_jumppad" type="text" value="" />
				<div class="alert">That JumpPad Already Exists. <a href="#">View that JumpPad Now</a></div>
			</div>
			<div class="block-field">
					<input class="radio-jumppad" type="radio" name="jumppad" value="Personal" />
					<label for="radio-jumppad">Personal</label>

					<input class="radio-jumppad" type="radio" name="jumppad" value="Private" />
					<label for="radio-jumppad">Private</label>
			</div>
			<div class="block-field">
				<div class="button gray simplemodal-close">
				<a href="" class="">
					<span class="label">Cancel</span>
				</a>
				</div>
			
				<div class="button orange input">
					<input type="submit" name="submitjp" value="Create" /></div>
				</div>
			</div>
		</form>
	</div>
</div>

</body>
</html>
