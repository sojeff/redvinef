<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>So:KNO Login</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="description" content="">
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/main.css">
	
	<script type="text/javascript" src="js/libs/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
		
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
