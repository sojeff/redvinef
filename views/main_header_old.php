		
	<link rel='stylesheet' type='text/css' href='../theme/css/reset.css' />
	<link rel='stylesheet' type='text/css' href='../theme/css/layout.css' />
	<link rel='stylesheet' type='text/css' href='../theme/css/styles.css' />
	<!-- <link rel='stylesheet' type='text/css' href='../theme/css/jamesDeveloping.css' /> -->
	
	
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
	
<body>

