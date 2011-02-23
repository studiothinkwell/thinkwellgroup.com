<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
	<head>
    	<?php print $head ?>
    	<title><?php print $head_title ?></title>
    	<?php print $styles ?>
    	<?php print $scripts ?>
        <link type="text/css" rel="stylesheet" media="all" href="/themes/garland/style.css" />
                
        <script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
    	<!--<script src="js/javascript.js" type="text/javascript"></script>-->
    	<script src="js/jquery.projects.js" type="text/javascript"></script>
    	<script src="js/jquery.slideShow.js" type="text/javascript"></script>
        <script src="js/jquery.listbox.js" type="text/javascript"></script>
    	<script type="text/javascript">
			$(document).ready(function(){
                /*$("#navigation").projects({
                    //set default options
                    prev : 0,
                    total : 10, //need to pull out this
                    projects : "projects", //id of the project div
                    projects_overlay : "projects-overlay"
                });*/

                $("#banner-nav").slideShow({
                    item : 0,
                    busy : false,
                    paused : true,
                    interval : null,
                    animationSpeed : 0.5,
                    animationInterval : 5,
                    menuOpacityOff : 1,
                    menuOpacityOn : 1,
                    jQuery_banner : "banner-nav",
                    jQuery_banner_menu : "menu-banner"
                });

                //$("#tabs-area").tabs();

            })
        </script>
  	</head>
  
  	<body>
    	<div id="bg">
        <?php
			$allowed_page = explode("/", $_SERVER['REQUEST_URI']);
			
			if(count($allowed_page) >= 3 && $_SERVER['REQUEST_URI'] != "/?q=user/login&destination=node")
			{
				for($slash = 3; $slash <= count($allowed_page); $slash++)
				{
					$add_slash .= "../";
				}
		?>
        <img src="<?php echo $add_slash; ?>themes/garland/images/bg.jpg"/>
        <?php
			}
        	elseif ($allowed_page[2] == "projectdetails")
			{
		?>
        <img src="themes/garland/images/project_view_bg.jpg"/>
        <?php
			}
			else
			{
		?>
        <img src="themes/garland/images/bg.jpg"/>
        <?php
			}
		?>
        </div>
        <div id="slide_div">
        	<!--<div id="header-region" class="clear-block"><?php //print $header; ?></div>-->
			<div id="header-main">
                <div id="header">
                    <?php				
                        if ($allowed_page[1] != "landingpage")
                        {
                    ?>
                    <div id="logo-floater-menu">
                        <a href="<?php echo check_url($front_page);?>" title="<?php echo $site_title;?>">
                            <?php print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';?>
                        </a>
                    </div>
                    <div id="menu-floater">
                        <?php if (isset($primary_links)) : ?>
                            <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
                        <?php endif; ?>
                        <?php if (isset($secondary_links)) : ?>
                            <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>
                        <?php endif; ?>
                    </div>
                    <?php
                        }
                        else
                        {
                    ?>
                    <div id="logo-floater">
                        <a href="<?php echo check_url($front_page);?>" title="<?php echo $site_title;?>">
                            <?php print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';?>
                        </a>
                    </div>
                    <?php
                        }
                    ?>            
                </div>
        	</div>
        	<div id="menu" style="display:<?php if(isset($left)) echo "block;"; else echo "none;"; ?> ">
				<?php print $left ?>
            	<?php print "<div style='float: left; width: 400px;'>"; ?>
            	<?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          		<?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
            	<?php print "</div>"; ?>
        	</div>
      
        	<div id="wrapper">
        		<div id="left_wrapper"></div>
        		<div id="main-wrapper">
            		<div id="center">
                		<?php print $content ?>
            		</div>
            		<div style="clear:both;"></div>         
           		</div>
          		<div id="right_wrapper"></div>                    
        	</div>          
        	<div style="clear:both;"></div> 
         	
            <div id="footer-main">
        		<div id="footer">
        		<?php
            		if ($allowed_page[1] != "landingpage")
					{
				?>
            		<div id="footer_message_container">
            			<div id="footer_message">
							<?php print $footer_message ?>
                		</div>
            		</div>
            		<div id="footer-content"><?php print $footer ?></div>
            	<?php
					}
					else
					{
				?>
            		<div class="inner-footer">
                		&copy; 2010 Thinkwell Group, Inc. All rights reserved. &nbsp; &nbsp; 
                		<a href="<?php echo check_url($front_page);?>" title="<?php echo $site_title;?>">
                    		<strong style="color: rgb(161, 185, 197);">Learn More About Thinkwell</strong>
                		</a>
            		</div>
            	<?php	
					}
				?> 
        		</div>
        	</div>
        	<?php //print $closure ?>
         </div>
	</body>   
</html>