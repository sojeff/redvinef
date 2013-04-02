<?PHP if(!$session->is_logged_in()) { redirect_to("login.php"); }     
       //I have to put the literal variables in the script below
       $preview='$preview';
       $pcnt='$pcnt';
       $pimg='$pimg';
       
       
       
if (isset($display_crop) && $display_crop==TRUE){
    
    // IMPORTANT NEED TO FIX ROOT FOLDER FOR CSS LINK and for image placement
    
$scrtempimage="/redvinef/userdata/files/tempupload/" . $user->userguid . ".jpg";    
echo '<script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>';    
echo '<link rel="stylesheet" href="/redvinef/theme/css/Jcrop.v0.9.12.js.css" type="text/css" />';    

echo <<<DISPLAY_CROP
<h3 class="title">Crop Picture</h3>
             <div class="section">
                <h3>Please Crop Your Persona Image as a Square</h3>
                
        <img src="$scrtempimage" alt="Image to be Cropped" id="uploadedforcropping"/>
<script language="Javascript">
$(window).on( "load", function() {
    //Code to run after window has loaded
    

     jQuery(function($){

    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
        boundx,
        boundy,

        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),

        xsize = $pcnt.width(),
        ysize = $pcnt.height();
    
    console.log('init',[xsize,ysize]);
    $('#uploadedforcropping').Jcrop({

      onChange: updatePreview,
      onSelect: updatePreview,
      aspectRatio: xsize / ysize
    },function(){
      // Use the API to get the real image size
      
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;

      // Move the preview into the jcrop container for css positioning
      $preview.appendTo(jcrop_api.ui.holder);
    });

    function updatePreview(c)
    {
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
      }
    };

  });
 

			$(function(){

				$('#uploadedforcropping').Jcrop({
					aspectRatio: 1,
					onSelect: updateCoords
				});

			});

			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function checkCoords()
			{
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press submit.');
				return false;
			};
                         });

</script>
<style type="text/css">

/* Apply these styles only when #preview-pane has
   been placed within the Jcrop widget */
.jcrop-holder #preview-pane {
  display: block;
  position: absolute;
  z-index: 2000;
  top: 10px;
  right: -280px;
  padding: 6px;
  border: 1px rgba(0,0,0,.4) solid;
  background-color: white;

  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;

  -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
}

/* The Javascript code will set the aspect ratio of the crop
   area based on the size of the thumbnail preview,
   specified here */
#preview-pane .preview-container {
  width: 250px;
  height: 250px;
  overflow: hidden;
}

</style>
<div id="preview-pane">
    <div class="preview-container">
  <img src="$scrtempimage" class="jcrop-preview" alt="Preview" />
    </div>
  </div>    
DISPLAY_CROP;

echo <<<DISPLAY_CROP2
		<form action="settings.php" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
                        <input type="hidden" id="cropped" name="cropped" value="cropped" />
			<input type="submit" value="Crop Image" />
		</form>
   </div>
DISPLAY_CROP2;
}
?> 