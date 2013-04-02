<!doctype html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>SO&middot;KNO | JumpPad</title>
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
						<li><a href="jumppad.php" class="icon-jumppad active">JumpPad</a></li>
						<li><a href="cell.php" class="icon-cell">Cell</a></li>
						<li><a href="persona.php" class="icon-persona">Persona</a></li>
					</ul>
				</nav>
				<form action="jumppad.php" name="search" method="post">
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
					<h2><span class="icon-jumppad"></span><?php echo ucwords($myview);?> JumpPad</h2>
					<ul class="switcher">
						<li><a href="" class="button"></a>
							<ul class="menu">
								<li><a href="jumppad.php?user_view=global" <?php echo $global_active;?> >Global</a></li>
								<li><a href="jumppad.php?user_view=personal" <?php echo $personal_active;?> >Personal</a></li>
								<li><a href="jumppad.php?user_view=private" <?php echo $private_active;?> >Private</a></li>
							</ul>
						</li>
					</ul>
					<div>
						<p>Sort</p>
						<ul class="dropdown">
							<li><a href="" class="button"><?php echo $session->user_sort;?></a>
								<ul class="menu">
									<li><a href="jumppad.php?user_sort=Date" <?php echo $sort_date_active;?> >Date</a></li>
									<li><a href="jumppad.php?user_sort=Alphabetical" <?php echo $sort_alpha_active;?> >Alphabetical</a></li>
								<!--	<li><a href="jumppad.php?user_sort=Content" <?php echo $sort_content_active;?> >Content</a></li>  --!>
									<li class="last"><a href="jumppad.php?user_sort=Relevance" <?php echo $sort_relevance_active;?> >Relevance</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<?php $counttopics = 0; ?>
				<?php foreach($jumppads as $jumppad): ?>
				<?php $topic = Topic::find_by_id($jumppad->topicid); ?>  
				<?php 
					$counttopics++;
					
					if($counttopics == 1) 
						{
						$stripleft = '<article class="col_25 stripleft">';
						}
					else $stripleft = '<article class="col_25">';
										
					if($counttopics == 4) 
						{
						$stripleft = '<article class="col_25 stripright">';
						$counttopics = 0;
						}
						
					echo $stripleft;
				?>
						<h3><a href=""><?php if(strlen($topic->topic) > 20) { echo substr($topic->topic,0,20) ."..."; } else { echo $topic->topic; } ?></a></h3>
						<div class="block-cell">
							<div class="block-img">
								<?php echo "<a href=\"cell.php?user_knocell={$jumppad->topicid}\" class=\"cell\">"; ?>
									<?php 
									if($topic->topic_image == '') 
										echo '<img src="img/cells/default.png"'; 
									else
										echo "<img src=\"../../knocell_images/{$topic->topic_image}\""; 
									?> 
										alt="Cell-" width="225" height="96"></a>
							</div>
							<div class="block-stats">
								<ul>
									<li title="# of people who have curated to this knowledge cell."><span class="icon-community"></span><?php echo number_format(Jumppad::count_people($jumppad->topicid)); ?></li>
									<li title="# of curations to this knowledge cell."><span class="icon-content"></span><?php echo number_format(Jumppad::count_curations($jumppad->topicid)); ?></li>
								</ul>
							</div>
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
				<p>Copyright &copy 2012 SO&middot;KNO.  All stripRights Reserved</p>
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
		
	</div>
</body>
</html>
