<?php
require_once('../../libraries/initialize.php');
if (!$session->is_logged_in()) { redirect_to("admin".DS."login.php"); }
?>
<?php
	$max_file_size = 10485760;   // expressed in bytes
	                            //     10240 =  10 KB
	                            //    102400 = 100 KB
	                            //   1048576 =   1 MB
	                            //  10485760 =  10 MB
	
	if(isset($_POST['submit'])) {
		$photo = new Photograph();
		$photo->caption = $_POST['caption'];
		$photo->attach_file($_FILES['file_upload']);
		if($photo->save()) 
			{
			// Success
      		$session->message("Photograph uploaded successfully.");
			redirect_to('list_photos.php');
			} 
		else 
			{
			// Failure
      		$message = join("<br />", $photo->errors);
			}
	}
	
//views
?>

<?php include_layout_template('admin_header.php'); ?>

<?php include_layout_template('photo_upload_view.php'); ?>

<?php include_layout_template('admin_footer.php'); ?>
		
