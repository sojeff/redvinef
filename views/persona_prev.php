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
					<li><a href="">Welcome: <?php echo $user->name?></a></li>
					<li><a href="">About SO:KNO</a></li>
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
					<h2><?php echo '<img src="'.$avatar.'" align="middle" /> '. $myview; ?> </h2>
				</div>

				<div>
				<!-- print the persona --!>
					<img src="img/personagraph.png"></p>

				</div>
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
