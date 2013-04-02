<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>SO&middot;KNO | JumpPad</title>
	<meta name="author" content="">
	<meta name="description" content="">
	
	<link rel="stylesheet" type="text/css" href="../views/theme/css/reset.css">
	<link rel="stylesheet" type="text/css" href="../views/theme/css/layout.css">
	<link rel="stylesheet/less" type="text/css" href="../views/theme/css/styles.less">
	
	<script type="text/javascript" src="../views/theme/js/libs/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../views/theme/js/libs/less-1.3.0.min.js" ></script>
	<script>less.watch();</script>
	
	<script type="text/javascript">
		
		$(document).ready(function() {
			clearTextField( jQuery("#field-find, #field-tag, #field-curcomm, #field-comment, #field-link") );

			var iFrames = document.getElementsByTagName('iframe');
	
			function iResize() {
				for (var i = 0, j = iFrames.length; i < j; i++) {
					iFrames[i].style.height = iFrames[i].contentWindow.document.body.offsetHeight + 'px';
				}
				}
	
				if ($.browser.safari || $.browser.opera) {
					$('iframe').load(function() {
						setTimeout(iResize, 0);
					}
				);
		
				for (var i = 0, j = iFrames.length; i < j; i++) {
					var iSource = iFrames[i].src;
					iFrames[i].src = '';
					iFrames[i].src = iSource;
				}
				}
				else {
					$('iframe').load(function() {
						this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
					}
				);
			}	
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
						<li><a href="cell.php" class="icon-cell">Cell</a></li>
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
					<h2><span class="icon-back"></span><a href="cell.php">Back</a></h2>
					<h2 class="last"><span class="icon-content"></span><? echo $topic->topic; ?></h2>
					<div>
						<p>Curate</p>
						<ul class="curation">
							<li><a href="" class="icon-comment">Comment</a></li>
							<?php echo '<li title="'.$likeUnlike.'"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'&like=Y" class="icon-like">Like</a></li>'; ?>
							<?php echo '<li title="'.$flagUnflag.'"><a href="viewcontent.php?contentid='.$cell->contentid.'&curationid='.$session->curationid.'&flag=Y" class="icon-flag">Flag</a></li>'; ?>
							<li>
								<a href="" class="icon-tag">Tag</a>
								<ul class="dropdown-tag">
									<li>
										<form action="viewcontent.php" method="post" id="form-tag">
											<input type="text" name="field-tag" id="field-tag" value="Add a Tag Keyword" />
											<input type="hidden" name="field-contentid" id="contentid" <?php echo 'value="'.$cell->contentid.'" />'; ?>
											<input type="submit" name="submit-tag" id="submit-tag" value="Add Tags" />
										</form>
									</li>
								</ul>
							</li>
							<li class="last">
								<a href="" class="icon-share">Share</a>
								<ul class="dropdown-share">
									<li><a href=""><span class="icon-email"></span>Email</a></li>
									<li><a href=""><span class="icon-facebook"></span>Facebook</a></li>
									<li><a href=""><span class="icon-twitter"></span>Twitter</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>

				<article class="container">
					<h3><?php echo $cell->title; ?></h3>
					<div class="block-article">
						<? echo $myframe; ?>
					</div>
				</article>

			</section>
			<!-- CONTENT END -->
			</div>
			
			<!-- COMMENTS -->
<!--			<div id="comments">
				<a name="Comments"></a>
				<div id="comments-header" class="clearfix">
					<h2>Comments</h2>
					
				<div id="comments-main" class="clearfix">
					<div id="comment-section">
				
						<div id="comment-input" class="clearfix">
						<form action="viewcontent.php" method="post">
							<p><textarea name="field-comment" id="field-comment">Add Comments</textarea></p>
							<p><textarea name="field-link" id="field-link" style="height: 20px !important">Add Link</textarea></p>
							<p class="count"><span>300</span>/300</p>
							<? echo '<input type="hidden" name="field-contentid" id="field-contentid" value="'.$cell->contentid.'" /> '; ?>
							<? echo '<input type="hidden" name="field-curationid" id="field-curationid" value="'.$cell->url.'" /> '; ?>
							<? echo '<input type="hidden" name="field-userguid" id="field-userid" value="'.$session->user_id.'" /> '; ?>
							<p class="button"><input type="submit" name="field-add" id="field-add" value="Add Comment/Link" /></p>
						</form>
						</div>
	
				
					</div>
				</div>
			</div>
-->
			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->message != "" and $session->user_id == 1004833642) echo "<p>" . $session->message . "</p><br/>"; ?>
				<?php if($session->debug != "" and $session->debug == 1004833642) echo "<p>" . $session->debug . "</p><br/>"; ?>
				<?php if($session->user_id == 1004833642) echo "<p><pre>" . print_r($user_likes_cell) . "</pre></p><br/>"; ?>
				<p>Copyright &copy <?echo date('Y'); ?> SO&middot;KNO.  All Rights Reserved</p>
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
		
	</div>
</body>
</html>
