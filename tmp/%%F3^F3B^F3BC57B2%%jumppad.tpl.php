<?php /* Smarty version 2.6.26, created on 2012-07-29 23:54:20
         compiled from jumppad.tpl */ ?>

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
	
</head>

<body>
	<div id="page">
			
			<div id="meta" class="wrapper">
			<!-- META -->
			<div class="container clearfix">
				<ul id="nav-meta">
					<li><a href="">About SO:KNO</a></li>
					<li><a href="">Logout</a></li>
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
						<li><a href="jumppad.html" class="icon-jumppad active">JumpPad</a></li>
						<li><a href="cell.html" class="icon-cell">Cell</a></li>
						<li><a href="" class="icon-persona">Persona</a></li>
					</ul>
				</nav>
				<p><input type="text" id="field-find" name="field-find" value="Find Knowledge" /></p>
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
					<h2><span class="icon-jumppad"></span>Global JumpPad</h2>
					<ul class="switcher">
						<li><a href="" class="button"></a>
							<ul class="menu">
								<li><a href="" class="active">Global</a></li>
								<li><a href="">Personal</a></li>
								<li><a href="">Private</a></li>
							</ul>
						</li>
					</ul>
					<div>
						<p>Sort</p>
						<ul class="dropdown">
							<li><a href="" class="button">Date</a>
								<ul class="menu">
									<li><a href="#" class="active">Date</a></li>
									<li><a href="#">Alphabetical</a></li>
									<li><a href="#">Community</a></li>
									<li class="last"><a href="#">Content</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>

				<?php $_from = $this->_tpl_vars['kcells']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kcell']):
?>
				<article class="col_25 stripleft">
					<h3><a href=""><?php echo $this->_tpl_vars['kcell']; ?>
</a></h3>
					<div class="block-cell">
						<div class="block-img">
							<a href="" class="cell"><img src="img/cells/default.png" alt="Cell-" width="225" height="96"></a>
						</div>
						<div class="block-stats">
							<ul>
								<li><span class="icon-community"></span>300</li>
								<li><span class="icon-content"></span>1,450</li>
							</ul>
						</div>
					</div>
				</article>
				<?php endforeach; endif; unset($_from); ?>

			</section>
			<!-- CONTENT END -->
			</div>

			<div id="footer" class="wrapper clearfix">
			<!-- FOOTER -->
			<footer class="container">
				<p>Copyright &copy 2012 SO&middot;KNO.  All stripRights Reserved</p>
			</footer>
			<!-- FOOTER END -->
			</div>

		</div>
		
	</div>
</body>
</html>