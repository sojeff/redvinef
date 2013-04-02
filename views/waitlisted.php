<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>SO:KNO&trade; | Login</title>
		
		<link rel='stylesheet' type='text/css' href='../theme/css/reset.css'>
		<link rel='stylesheet' type='text/css' href='../theme/css/layout.css'>
		<link rel='stylesheet' type='text/css' href='../theme/css/styles.css'>

	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js'></script>
	<script type='text/javascript' src='../theme/js/libs/jquery.watermark.min.js'></script>
	<script type='text/javascript' src='../theme/js/libs/jquery.simplemodal.1.4.3.min.js'></script>
	<script type='text/javascript' src='../theme/js/libs/jquery.autosize-min.js'></script>
	<script type='text/javascript' src='../theme/js/scripts.js'></script>

	<script>
  setInterval(function(){
    try {
		  if(typeof ws != 'undefined' && ws.readyState == 1){return true;}
		  ws = new WebSocket('ws://'+(location.host || 'localhost').split(':')[0]+':35353')
		  ws.onopen = function(){ws.onclose = function(){document.location.reload()}}
		  ws.onmessage = function(){
			var links = document.getElementsByTagName('link'); 
			  for (var i = 0; i < links.length;i++) { 
			  var link = links[i]; 
			  if (link.rel === 'stylesheet' && !link.href.match(/typekit/)) { 
				link.href = link.href.split('?')[0]+'?'+(new Date()).getTime()
			  }
			}
		  }
		}catch(e){}
  	}, 500)
	</script>
</head>
	
<body class="landing">

<!-- META -->
<div id="landing-wrap">

	<div id="landing">
		
		<!-- GREETING -->
		<div id="mission" class="clearfix">
			<h1>SO:KNO&trade;</h1>
			<h2>A World of Knowledge.<br />Infinite Possibilities.</h2>
			<div class="button">
			<a class="signup" href="#"><span class="label">Signup Now</span></a>
			</div>
		</div>
		<!-- END GREETING -->
		
		<!-- LOGIN -->
		<div id="window">
			<div id="form" class="clearfix">
				<div id="left">
					<form id="login" method="post" action="login.php">
						<div><input class="email" type="text" name="useremail" id="useremail" maxlength="100" value="" /></div>
						<div><input class="password" type="password" name="passweird2" id="passweird2" maxlength="20" value="" /></div>
						<p><a class="forgot" href="#">Forgot Your Password?</a></p>
						<div class="button input"><input  name="login-button" id="login-button" type="Submit" value="Login"/></div>
					</form>
					
					<form id="signup" method="post" action="login.php?signup=YES">
						<div><input class="firstname" name="firstname" type="text" value="" /></div>
						<div><input class="lastname" name="lastname" type="text" value="" /></div>
						<div><input class="email" name="useremail" type="text" value="" /></div>
						<div><input class="emailconfirm" name="useremail_confirm" type="text" value="" /></div>
						<div><input class="password" name="passweird2" type="password" value="" /></div>
						<div class="note">
							<input id="female" name="gender" type="radio" value="female" />
							<label for="female">Female</label>
							<input id="male" name="gender" type="radio" value="male" />
							<label for="male">Male</label>
						</div>
						<p class="note">By creating an account, I accept SO:KNO's <a href="">Terms of Service</a> and <a href="">Privacy Policy</a>.</p>
						<p><a class="login" href="#">Already have an Account?</a></p>
						<div class="button input"><input type="submit" value="Signup" /></div>
					</form>

					<form id="forgot" method="post" action="login.php?forgot=YES">
						<div><input class="email" name="useremail" type="text" value="" /></div>
						<input name="forgot_login" type="hidden" value="" />
						<p class="note">Enter in the Email Address used when signing up for this account.</p>
						<p><a class="back" href="#">Back to Login?</a></p>
						<div class="button input"><input type="submit" value="Reset" /></div>
					</form>
				</div>
				<div id="right">
					<h3>Account Request Received!</h3>
					<p>Your SO:KNO account request will be reviewed shortly. You will receive a confirmation email when BETA approvals are completed. Thank you for signing up.</p>
				</div>
			</div>
		</div>
		<!-- END LOGIN -->
		
		<!-- FOOTER -->
		<div id="footer" class="clearfix">
			<p>&copy; Copyright 2012 SO:KNO, Inc.</p>
			<ul id="nav-footer">
				<li><a class="contact-us" href="#">Contact Us</a>
				<!--
				<span>&bull;</span></li>
				<li>Follow us on</li>
				<li><a class="icon-facebook" href="#">Facebook</a></li>
				<li class="last"><a class="icon-twitter" href="#">Twitter</a></li>
				-->
			</ul>
		</div>
		<!-- FOOTER END -->
		
		
	</div>
	
	<div id="background"></div>

</div>
<!-- END META -->
<!-- CREATE MODAL -->
<div id="modal-contact">
	<div class="block-modal clearfix">
		<h3 class="title">Contact Us</h3>
		
		<h3>Submit an Inquiry</h3>
		<p>For general inquiries, partnerships, press inquires, or business and educational licensing, send e-mail at: <a href="mailto:info@getsokno.com">info@getsokno.com</a>.</p>
		
		<h3>Technical and Account Support</h3>
		<p>If you encounter problems with your account or SO:KNO services, please email Support at <a href="mailto:support@getsokno.com">support@getsokno.com</a>.</p>
		
		<h3>App Feedback</h3>
		<p>If you have suggestions or feedback regarding the SOKNO App, send e-mail to <a href="mailto:suggest@getsokno.com">suggest@getsokno.com</a>.</p>
		
		<h3>Report Abuse or Offensive Content</h3>
		<p>To report a violation of the SO:KNO Terms of Service or other abusive, offensive, or illegal content, please contact our Support team Support at <a href="mailto:support@getsokno.com">support@getsokno.com</a>.</p>
		
		<h3>Information</h3>
		<p>SO:KNO Corporate Headquarters<br>
			1121 White Rock Road, Suite 205<br>
			El Dorado Hills, CA 95762<br>
			United States</p>
		
		<div class="button gray simplemodal-close">
			<a class="" href="">
				<span class="label">Close</span>
			</a>
		</div>
	</div>
</div>
</body>
</html>