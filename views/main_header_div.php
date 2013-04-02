	<div class="clearfix" id="header-wrap">
	
		<!-- HEADER -->
		<header class="container_12">
		<!-- HEADER META -->
                <div class="grid_12" id="header-meta">
                    <h1>SO:KNO&trade;</h1>
                    
                    
                    
                    <ul id="nav-meta">
                        <li>
                            <a href="about.php"><span class="button-info"></span>About</a>

                 <!--           <ul id="nav-about">
                                <li><a href="pdf/Privacy Policy v1.pdf">Privacy Policy</a></li>
                                <li><a href="pdf/Terms of Use v3.pdf">Terms of use policy</a></li>
                                <li><a href="contact.php">Contact Us</a></li>
                            </ul>
                -->
                        </li>
                        <li class="last">
                            <a href="logout.php"><span class="button-logout"></span>Logout</a>
                        </li>
                    </ul>
                    
                    
                    
                    <p>Welcome Back, <?php echo $user->name ?></p>
                </div>
		
		<!-- HEADER NAV -->
		<div class="grid_12" id="header-nav">
			<ul id="nav-main">
				<li><a <? echo 'href="'.$nav_search_discover.'"';?><?php echo $currentDiscover;?>><span class="button-discover"></span>Discover</a></li>
				<li><a href="persona.php" <?php echo $currentPersona;?>> <span class="button-persona" ></span>Persona</a></li>
				<li><a href="settings.php" <?php echo $currentSetting;?>> <span class="button-settings" ></span>Settings</a></li>
			</ul>
			
			<div id="form-search">
				<form method="post" action=<? echo '"'.$nav_search_discover.'"'; ?> >
				<input class="search" type="text" id="field-find" name="field-find" value="<?php echo ((isset($find_knowledge))? $find_knowledge :'');?>" />
				<input class="button search" type="submit" />
				</form>
			</div>
		</div>
		
		</header>
	<!-- END HEADER -->
	</div>
	<!-- END HEADER -->
	
