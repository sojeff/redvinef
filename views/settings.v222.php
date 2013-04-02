<?PHP // $uploaded_results_string="nnnnnnnnnn"; ?>
<div id="page-wrap" class="clearfix">

    <!-- PAGE -->
    <div id="page" class="container_12">

        <!-- SIDEBAR -->
        <aside class="grid_3">

            <!-- USER -->
			<section class="user">
				<hgroup>
					<h3><? echo $user->name; ?></h3>
				</hgroup>
				<div class="block persona clearfix">
					<div class="info">
						<div class="thumb"><a href="#"><? echo $avatar; ?></a></div>
						<div class="level">
						<? if($level_array[0] == 1)
								echo '<span class="level1 stage'.$level_array[1].'">{#}</span>';
						   else
								echo '<span class="level1 stage0">1</span>';
						   if($level_array[0] == 2)
								echo '<span class="level2 stage'.$level_array[1].'">{#}</span>';
						   else
								echo '<span class="level2 stage0">2</span>';
						   if($level_array[0] == 3)
								echo '<span class="level3 stage'.$level_array[1].'">{#}</span>';
						   else
								echo '<span class="level3 stage0">3</span>';
						
							echo '<p>'.ucwords($level_array[2]).'</p>';
							?>
						</div>
						<div class="location">
							<span class="icon-marker"></span>
							<p><? echo $user->currentlocationcity.' '.$user->currentlocationcountry; ?></p>
						</div>
					</div>
				</div>
				<? if($user->userguid != $user->userguid)
					  {
						echo '<div class="button wide follow">';
						if(!$following)
							echo '<a href="persona.php?user_id='.$user->userguid.'&follow=stop"><span class="label-icon"><span class="check"></span>Following</span></a>';
						else
							echo '<a href="persona.php?user_id='.$user->userguid.'&follow=start"><span class="label-icon"><span class="check"></span>Not Following</span></a>';
						echo '</div>';
					  }
				?>
			</section>

            <!-- CONNECTIONS -->
            <section class="connections">
                <ul class="nav">
                    <li><a href="persona.php">PersonaGraf</a></li>
                    <li><a href="persona.php?in=2">Library</a><span class="stat"><?php echo number_format(Jumppad::count_jumppads('personal', $user->userguid), 0); //echo $userlevel->curations_count; ?></span></li>
                    <li><a href="persona.php?in=3">Connections</a><span class="stat"><?php echo User_follows::count_followers($user->userguid, 1) + User_follows::count_followers($user->userguid, 2); //($userlevel->followed_count + $userlevel->following_count); ?></span></li>
                </ul>
            </section>

            <!-- SPOTLIGHT -->

            <section class="spotlight">
                <hgroup>
                    <h3>Spotlight</h3>
                </hgroup>

                <!-- INFO -->
                <div class="spotlight-info">

                    <!-- STATS -->
                    <div class="info-section">
                        <div class="info-quad1">
                            <h5>Lifetime</h5>
                            <?php

                            function returnLifetime($user) {
                                if ($user->updated_time=='0000-00-00 00:00:00'){return "???";}
                                $datetimenow = new DateTime("now");
                                $datetimebeginning = new DateTime($user->updated_time);
                                $interval = $datetimebeginning->diff($datetimenow);

                                //echo diff_times($user->updated_time);
                                return $interval->format('%a days');
                            }
                            ?>
                            <h4><?PHP echo returnLifetime($user); ?></h4>
                        </div>
                        <div class="info-quad2">
                            <h5>Curations</h5>
                            <h4><?php echo number_format(Jumppad::count_jumppads('personal', $user->userguid), 0); //echo $userlevel->curations_count; ?></h4>
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-quad3">
                            <h5>Followers</h5>
                            <h4><?php echo User_follows::count_followers($user->userguid, 2); ?></h4>
                        </div>
                        <div class="info-quad4">
                            <h5>Following</h5>
                            <h4>
                                <?php
                                //$following =$following;
                                echo User_follows::count_followers($user->userguid, 1); //echo $userlevel->following_count;
                                ?></h4>
                        </div>
                    </div>

					<!-- SOURCE -->
					<div class="info-section thread">
						<h5>Recent Curation</h5>
						<?
						//need to display differently if the recent curation is a thread and not content
						if(isset($cell->content_type) and $cell->content_type == 0)
							{
							echo '<div class="tile content grid_3 alpha">';
							echo '<h4 class="title"><a href="'.$cellhref.'">'.$celltitle.'</a></h4>';
							echo '<div class="thumb"><a href="'.$cellhref.'">'.$image_display.'</a></div>';
							echo '<p class="excerpt">'.$body.'</p>';
							echo '<div class="stats">';
								echo '<ul>';
									echo '<li><span class="stat-date"></span>'.$time3.$add3.'</li>';
									echo '<li><span class="stat-like"></span>'.$content->cell_likes($cell->contentid, $cell->curationid).'</li>';
								echo '</ul>';
							echo '</div>';
							}
						else if(isset($cell->commentid) and $cell->commentid > 0)//thread
							{
							echo '<div class="tile thread grid_3 alpha">';
							echo '<h4 class="title"><a href="thread.php?contentid='.$cell->contentid.'&curationid='.$maincurationid.'&commentid='.$maincommentid.'">'.$commenttitle.'</a></h4>';
							echo '<p class="comment"><a href="thread.php?contentid='.$cell->contentid.'&curationid='.$maincurationid.'&commentid='.$maincommentid.'">'.$commentbody.'</a></p>';
							?>							
							<div class="mini-persona">
								<div class="thumb">
									<?php 
									if($persona->avatar != '')
										echo '<a href="persona.php?user_id='.$persona->userguid.'"><img src="retrieveUserAvatar.php?id='.$persona->userguid.'" alt="" width="36" height="36" /></a>'; 
									else
										echo '<a href="persona.php?user_id='.$persona->userguid.'"><img src="../theme/img/ui/icon-avatar.png" alt="" width="36" height="36" /></a>';
									?>
								</div>
								<div class="info">
									<?php
									echo '<h4><a href="persona.php?user_id='.$persona->userguid.'">'.$persona->name.'</a></h4>';
									echo '<p title="Level '.ucwords($level_array[0]).' Stage '.ucwords($level_array[1]).' '.ucwords($level_array[2]).'"><span class="level'.$level_array[0].' stage'.$level_array[1].'">{#}</span>'.ucwords($level_array[2]).'</p>';
									?>
								</div>
								<? 
								if(!$following and $persona->userguid != $session->user_id)
									echo '<div class="button-follow square">
											<a href="cell.php?follow='.$persona->userguid.'">Follow</a>
										  </div>'; 
								else
									echo '<div class="button-follow square">
											<a class="active" href="cell.php?stopfollow='.$persona->userguid.'">Follow</a>
										  </div>'; 
							?>
							</div>
							<div class="date">
								<div class="recent">
									<p>Recent Post</p>
									<h4><? echo  $time2.$add2. ' ago'; ?></h4>
								</div>
								<div class="comments">
									<p>Comments</p>
									<h4><?php echo number_format($comment->count_comments_new($maincommentid)); ?></h4>
								</div>
							</div>
							<div class="stats">
								<ul>
									<li><span class="stat-date"></span><?php echo $time3.$add3; ?></li>
									<li><span class="stat-persona"></span><?php echo number_format($comment->count_users_on_thread($maincommentid)); ?></li>
								</ul>
							</div>
						 <?	} ?>
						
					</div>
					
				</div>
				<!-- END INFO -->

            </section>
            <!-- END SPOTLIGHT -->

        </aside>
        <!-- END SIDEBAR -->































        <!-- CONTENT -->
        <div id="content-wrap" class="grid_9">


            <!-- CONTENT HEADER -->
            <div id="content-header" class="clearfix">
                <h3>Settings</h3>
            </div>

            <!-- CONTENT BLOCK -->
            <div id="content-article" class="clearfix">
                <?PHP
                if (isset($validation_errors)) {
                    echo <<< VALIDATION_OUTPUT
        <div width="350px">
          <!--  <p style="font-size:1em; color:red">This Settings Page Is Under Construction</p> -->
            <p style="font-size:1em; color:red">$validation_errors</p>
         </div>
VALIDATION_OUTPUT;
                }
                ?>


                <?php include_once ('settings.v.admin.php'); ?>
                <?php 


                include_once ('settings.v.crop.php'); ?>
                <div>

                    <h3 class="title">Persona Information</h3>

                    <form  id="form-image" method="post" action="settings.php"  enctype="multipart/form-data">

                        <h3>Image</h3>     

                        <div class="section">

                            <!-- IMAGE UPLOAD -->

                            <p class="note">Upload an image to use for your Persona. <strong>Accepted files are PNG, GIF and JPG</strong></p>
                            <div>
                                <label class="main" for="">Persona Image</label>
                                <input name="personaimage" class="persona" type="file" />
                            </div>
                            <div class="input button">
                                <input class="" type="submit" name="image_upload" value="Upload Persona Image" />
                            </div>

                        </div>




                    </form> 















                    <h3>General</h3>
                    <form  id="form-info" method="post" action="settings.php" >  
                        <div class="section">


                            <p class="note">Update or add your general persona information.</p>

                            <!-- NAME -->
                            <div>
                                <label class="main" for="">Name</label>
                                <input name="first_name" class="firstname" type="text" maxlength="255" value="<?php echo $user->first_name; ?>" />
                                <input name="last_name" class="lastname" type="text" maxlength="255" value="<?php echo $user->last_name; ?>" />
                            </div>

                            <!-- BIRTHDAY -->
                            <div>
                                <?php $bdayArray = explode('-', $user->birthday); ?>
                                <label class="main" for="">Birthday</label>
                                <span>
                                    <input name="month" class="month" size="2" maxlength="2" value="<?php echo $bdayArray[1]; ?>" type="text">/
                                </span>
                                <span>
                                    <input name="day" class="day" size="2" maxlength="2" value="<?php echo $bdayArray[2]; ?>" type="text">/
                                </span>
                                <span>
                                    <input name="year" class="year" size="4" maxlength="4" value="<?php echo $bdayArray[0]; ?>" type="text">
                                </span>
                            </div>

                            <!-- GENDER -->
                            <div>
                                <label class="main" for="">Gender</label>
                                <span class="gender">
                                    <input name="gender" class="male" type="radio" value="male" <?php if ($user->gender == 'male') echo "checked"; ?>/>
                                    <label for="male">Male</label>
                                    <input name="gender" class="female" type="radio" value="female" <?php if ($user->gender == 'female') echo "checked"; ?>/>
                                    <label for="female">Female</label>
                                </span>
                            </div>


                            <!-- LOCATION -->
                            <div>
                                <label class="main" for="">Location</label>
                                <input name="city" class="city" value="<?php echo $user->currentlocationcity; ?>" type="text">
                                <input name="zip" class="zip" maxlength="7" value="<?php echo $user->currentlocationzip; ?>" type="text">
                                <select class="state" name="state">

                                    <?php
                                    include_once(LIB_PATH . DS . 'html_functions.php');
                                    showSelectedState($state = "$user->currentlocationstate");
                                    ?>

                                </select>
                                <label for="">State</label>
                            </div>

                        </div>

                        <div class="input button">
                            <input class="" type="submit" name="info_update" value="Update Information" />
                        </div>    
                    </form> 










                    <form  id="form-email" method="post" action="settings.php" >
                        <div class="email">
                            <h3>Email Address</h3>
                            <p class="note">Update your Email (Username) to your Account.</p>
                            <div>
                                <input name="email" class="email" type="text" maxlength="255" value="<?php echo $user->email; ?>" />
                            </div>




                            <div class="input button">
                                <input class="" type="submit" name="email_update" value="Update Email Address" />
                            </div>
                        </div>
                    </form>            

                    <form  id="form-email" method="post" action="settings.php" >
                        <div class="section last">                
                            <div class="updatepw">    
                                <h3>Password</h3>
                                <p class="note">Update your Password.</p>
                                <!---<input name="currentpassword" class="password" type="password" maxlength="60" value="" /> -->
                            </div>
                            <div>
                                <input name="newpassword" class="password" type="password" maxlength="60" value="" />
                            </div>
                            <div>
                                <input name="passwordconfirm" class="passwordconfirm" type="password" maxlength="60" value="" />
                            </div>
                        </div>

                        <div class="input button">
                            <input class="" type="submit" name="passwd_update" value="Update Password" />
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- END CONTENT -->

    </div>
    <!-- END PAGE -->

</div>

</body>
</html>