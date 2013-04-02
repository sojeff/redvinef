<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="description" content="">
		
        <title>So KNO | Communities!</title>
        
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/main.css">

		<script type="text/javascript" src="js/libs/jquery-1.7.2.min.js"></script>
		<script type="text/javascript">
		
		$(document).ready(function() {
			clearTextField( jQuery("#field-find") );
			
			$('.tile').hover(function() {
				$(this).children('.tile-cover').animate({top: '+=120px'}, 200);
				$(this).children('h3').animate({top: '-=72px'}, 200).css("color","#ffffff");
				$(this).children('.tile-tags').animate({top: '-=104px'}, 200);
			}, function() {
				$(this).children('.tile-cover').animate({top: '-=120px'}, 200);
				$(this).children('h3').animate({top: '+=72px'}, 200).css("color","#797d7f");
				$(this).children('.tile-tags').animate({top: '+=104px'}, 200);
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
<?php

require_once('Smarty.class.php');
require_once('../models/jumppad.php');

$view = new Smarty();
$view->template_dir = '../views';
$view->compile_dir = '../tmp';

$model = new jumpPadModel();
$model->loadData("this is my query or args");

$view->assign('kcells', 	$model->kcells );

$view->display('jumppad.tpl');
?>
</body>
</html>