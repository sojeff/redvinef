<!doctype html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>SO&middot;KNO | About SO&middot;KNO</title>
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
						<li><a href="cell.php" class="icon-cell">Cell</a></li>
						<li><a href="persona.php" class="icon-persona active">Persona</a></li>
					</ul>
				</nav>
				<form action="persona.php" method="post" name="search_cell">
				<p><input type="text" id="field-find" name="field-find"  <?php echo 'value="'.$find_knowledge.'"'; ?> /></p>
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
			
				<div id="section-header-low" class="clearfix">
					<h2><span class="icon-jumppad"></span>Bookmarklet</h2>
				</div>
				<div class="about">
					<p><?php echo $user->name; ?>, here is your personalized So-KNO Knowledge Collector.</p>
				<p>Please drag or save this brain onto your address book (Bookmark) of your browser.</p><br/>
				<?php echo '<p><a href="javascript:(function()%7Bf=\'http://23.23.245.230/saveit.php?guid='.$user->userguid.'&url=\'+encodeURIComponent(window.location.href)+\'&title=\'+encodeURIComponent(document.title)+\'&notes=\'+encodeURIComponent(\'\'+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+\'&v=6&\';a=function()%7Bif(!window.open(f+\'noui=1&jump=doclose\',\'soKno6\',\'location=1,links=0,scrollbars=0,toolbar=0\'))location.href=f+\'jump=yes\'%7D;if(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)()">
				  <img src="img/sokno.jpg" alt="" align="middle" /></a></p>' ?>
				<br/>
				</div>
								
			</section>
			<!-- CONTENT END -->
			</div>

			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<?php if($session->message != "" and $session->user_id == 1004833642) echo "<p>" . $session->message . "</p><br/>"; ?>
				<p>Copyright &copy 2012 SO&middot;KNO.  All Rights Reserved</p>
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
		
	</div>
</body>
</html>
