<?PHP if(!$session->is_logged_in()) { redirect_to("login.php"); }     
       
if (isset($display_admin) && $display_admin==TRUE){
echo <<<DISPLAY_ADMIN
<h3 class="title">Admin Content</h3>
        <form id="form-admin" class="clearfix" method="post" action="settings.php"  enctype="multipart/form-data">

            <div class="section">
                <!-- IMAGE UPLOAD -->
                <h3>File</h3>
                <p class="note">Upload a content file to a Knowledge Cell. <strong>Accepted files are PDF and Images</strong></p>
                <div>
                    <label class="main" for="">Content File</label>
                    <input name="admindoc" class="persona" type="file" />
                </div>
            </div>
            <div class="section">
                <h3>Knowledge Cell</h3>
                
                            <p>
                               Title <input name="title"  type="text" maxlength="250" value="" />
                            </p>
                <p class="note">Select the Knowledge Cell where this content file will be posted.</p>
                <div>
				<select id="private" name="private">
			<option disabled value="Select a Private Knowledge Cell" selected="selected">Select a Private Knowledge Cell</option>
DISPLAY_ADMIN;
				foreach($pvtarray as $pa)
					{
					echo '<option value="'.$pa.'">'.$pa.'</option>';
					}

echo <<<DISPLAY_ADMIN2
					</select>

                </div>
            </div>

            <div class="input button">
                <input class="" type="submit" name="document_upload" value="Upload Document" />
            </div>

        </form>
DISPLAY_ADMIN2;
}
?> 