<!doctype html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>SO&middot;KNO | Knowledge Cell</title>
	<meta name="author" content="">
	<meta name="description" content="">
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/layout.css">
	<link rel="stylesheet/less" type="text/css" href="css/styles.less">
	
	<script type="text/javascript" src="js/libs/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/libs/less-1.3.0.min.js" ></script>
	<script>less.watch();</script>
	
	<script type="text/javascript">
		
		$(document).ready(function() {
			clearTextField( jQuery("#field-find") );

			$('.block-img').hover(function() {
				$(this).children('a').animate({opacity: '0.5'}, 200);
			}, function() {
				$(this).children('a').animate({opacity: '1.0'}, 200);
			});

		});
		
		function clearTextField( field ){
			field.focus( function(){
				jQuery(this).addClass('active');
				if ( this.value == this.defaultValue ){
					this.value = '';
				}
			});

			field.blur( function(){
				jQuery(this).removeClass('active');
				if( this.value == '' ){
					this.value = this.defaultValue;
				}
			});
		}
			
	</script>
</head>

<body>
	<div id="page">
			
			<div id="meta" class="wrapper">
			<!-- META -->
			<div class="container clearfix">
				<ul id="nav-meta">
					<li><a href="persona.php">Welcome: <?php echo $user->name?></a></li>
					<li><a href="sokno_brain.php">About SO:KNO</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>
			<!-- META END -->
			</div>

			<div id="header" class="wrapper">
			<!-- HEADER -->
			<header class="container clearfix">
				<h1>SO&middot;KNO&trade;</h1>
				<nav>
					<ul id="nav-header">
						<li><a href="jumppad.php" class="icon-jumppad">JumpPad</a></li>
						<li><a href="cell.php" class="icon-cell active">Cell</a></li>
						<li><a href="persona.php" class="icon-persona">Persona</a></li>
					</ul>
				</nav>
				<form action="cell.php" method="post" name="search_cell">
				<p><input type="text" id="field-find" name="field-find" <?php echo 'value="'.$find_knowledge.'"';?> /></p>
				</form>
			</header>
			<!-- HEADER END -->
			</div>
			
			<!-- DASHBOARD -->
			<div id="dashboard" class="wrapper">
			
			</div>
			<!-- DASHBOARD END -->

			<div id="content" class="wrapper">
			<!-- CONTENT -->
			<section class="container clearfix">

				<div id="section-header" class="clearfix">
					<h2><span class="icon-cell"></span><?php echo $mytopic;?></h2>
					<ul class="switcher">
						<li><a href="" class="button"></a>
							<ul class="menu">
					<!-- need the list of communities --!>
								<?php
								if(isset($session->topics))
									{
									foreach($session->topics as $mytopic):
										if($mytopic == $topic->topicid)
											echo '<li><a href="cell.php?user_knocell='.$topic->topicid.'" class="active">'.$topic->topic.'</a></li>';
										else
											echo '<li><a href="cell.php?user_knocell='.$mytopic.'">'.Topic::find_by_id($mytopic)->topic.'</a></li>';
									endforeach;
									}
								else
									echo '<li><a href="cell.php?user_knocell='.$topic->topic.'" class="active">'.$topic->topic.'</a></li>';
							?>
							
							</ul>
						</li>
					</ul>
					<div>
						<p>Sort</p>
						<ul class="dropdown">
							<li><a href="" class="button"><?php echo $session->user_sort;?></a>
								<ul class="menu">
									<li><a href="cell.php?user_sort=Date" <?php echo $sort_date_active;?> >Date</a></li>
									<li><a href="cell.php?user_sort=Alphabetical" <?php echo $sort_alpha_active;?> >Alphabetical</a></li>
								<!--	<li><a href="cell.php?user_sort=Content" <?php echo $sort_content_active;?> >Content</a></li>  --!>
									<li class="last"><a href="cell.php?user_sort=Relevance" <?php echo $sort_relevance_active;?> >Relevance</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div>
						<p>View</p>
						<ul class="dropdown">
							<li><a href="" class="button"><?php echo $myview;?></a>
								<ul class="menu">
									<li><a href="cell.php?knocell_view=All" <?php echo $knocell_view_all_active;?> >All</a></li>
									<li><a href="cell.php?knocell_view=Content" <?php echo $knocell_view_content_active;?> >Content</a></li>
									<li><a href="cell.php?knocell_view=Threads" <?php echo $knocell_view_threads_active;?> >Threads</a></li>
									<li><a href="cell.php?knocell_view=QandA" <?php echo $knocell_view_qanda_active;?> >Q&A</a></li>
									<li class="last"><a href="cell.php?knocell_view=Personas" <?php echo $knocell_view_personas_active;?> >Personas</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>

				<?php 
				$countcells = 0; 
				foreach($cells as $cell): 
					$countcells++;
					
					if($countcells == 1) 
						{
						$stripleft = '<article class="col_25 stripleft">';
						}
					else $stripleft = '<article class="col_25">';
										
					if($countcells == 4) 
						{
						$stripleft = '<article class="col_25 stripright">';
						$countcells = 0;
						}
						
					echo $stripleft;
				?>
						<h3><span class="favicon"></span>
						<?php
						if($myview != 'Personas')
						 	echo '<a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'">' . substr(str_replace("www.","",parse_url($cell->url,PHP_URL_HOST)),0,18) . '</a></h3>';
						 else
						 	{
						 	echo '<a href="persona.php?user_id='.$cell['userguid'].'">' . $cell['name'] . '</a></h3>';
						 	}
						?>
						<div class="block-content">
							<div class="block-img">
								<?php 
								if($myview != 'Personas')
									{
									echo '<a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$cell->curationid.'" class="cell">';
									if($image_id = $cell->cell_image($cell->contentid)) 
										echo '<img src="retrieveFile.php?id='.$image_id.'" alt="Cell-" width="225" height="144"></a>'; 
									else
										echo '<img src="img/cells/thumb.png" alt="Cell-" width="225" height="144"></a>'; 
								?>
							</div>
								<?php //fit the cell title in 1 line
								echo '<h4 title="'.$cell->title.'">';
								
								if(strlen($cell->title) < 44)
									echo $cell->title; 
								else
									echo substr($cell->title,0,44) . "..."; 
								
								?>
								</h4>
							<div class="block-stats">
								<ul>
									<li><span class="icon-date"></span><?php echo '<a href="persona.php?user_id='.$cell->cell_curator($cell->contentid).'">'.diff_times($cell->date_created); ?></a></li>
									<li><span class="icon-comments"></span><?php echo $cell->cell_comments($cell->contentid); ?></li>
									<li><span class="icon-likes"></span><?php echo $cell->cell_likes($cell->contentid, $cell->curationid); ?></li>
								</ul>
							</div>
								<?php 
									}
								else
									{
									echo '<a href="persona.php?user_id='.$cell['userguid'].'">';
									if(file_exists("img/avatar_70x70/".$cell['userguid']."_cell.jpg"))
										echo '<img src="img/avatar_70x70/'.$cell['userguid'].'_cell.jpg"></a>'; 
									else if(file_exists("img/avatar_70x70/".$cell['userguid'].".jpg"))
										echo '<img src="img/avatar_70x70/'.$cell['userguid'].'.jpg"></a>'; 
									else
										echo '<img src="img/avatar_70x70/avatar_cell.jpg"></a>'; 
									//echo $user->find_by_id($cell->cell_curator($cell->contentid));
								?>
							</div>
							<div class="block-stats">
							</div>
								<?php 
									}
							?>
						</div>
					</article>
				<?php endforeach; ?>				

			</section>
			<!-- CONTENT END -->
			</div>

			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->message != "" and $session->user_id == 1004833642) echo "<p>" . $session->message . "</p><br/>"; ?>
				<?php if($session->user_id == 1004833642 and isset($cell)) echo "<p><pre>" . print_r($cell) . "</pre></p><br/>"; ?>
				<p>Copyright &copy 2012 SO&middot;KNO.  All stripRights Reserved</p>
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
		
	</div>
</body>
</html>
