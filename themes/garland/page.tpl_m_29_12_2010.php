<?php
	global $base_url;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
	<head>
    	<meta name="format-detection" content="telephone=no" />
    	<link type="text/css" rel="stylesheet" media="all" href="<?php echo $base_url;?>/themes/garland/style.css" />
        <link rel="stylesheet" type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/themes/garland/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
        <?php print $head ?>
    	<title><?php print $head_title ?></title>
    	<?php print $styles ?>
    	<?php print $scripts ?>
        <script type="text/javascript">
			<!--
				function unhideBody()
				{
					var bodyElems = document.getElementsByTagName("body");
					bodyElems[0].style.visibility = "visible";
				}
			-->
		</script>
        <script type="text/javascript">
			var message="";
			
			function clickIE()
 			{
				if (document.all)
				{
					(message);
					return false;
				}
			}
 			
			function clickNS(e)
			{
				if(document.layers || document.getElementById)
				{
					if(!document.all)
					{
						if (e.which==2||e.which==3)
						{
							(message);
							return false;
						}
					}
				}
			}
			
			if (document.layers)
			{
				document.captureEvents(Event.MOUSEDOWN);
				document.onmousedown=clickNS;
			}
			else
			{
				document.onmouseup=clickNS;
				document.oncontextmenu  =clickIE;
			}
 			
			document.oncontextmenu=new Function("return false")
		</script>

        <script src="<?php echo $base_url."/" ?>js/jsall.js" type="text/javascript"></script>
       <!-- <script src="<?php echo $base_url."/" ?>js/jquery-1.4.2.min.js" type="text/javascript"></script>
    	<script src="<?php echo $base_url."/" ?>js/jquery.projects.js" type="text/javascript"></script>
    	<script src="<?php echo $base_url."/" ?>js/jquery.slideShow.js" type="text/javascript"></script>
        <script src="<?php echo $base_url."/" ?>js/jquery.slider.js" type="text/javascript"></script>
        <script src="<?php echo $base_url."/" ?>js/jquery.listbox.js" type="text/javascript"></script>
        <script src="<?php echo $base_url."/" ?>js/jquery.tabs.js" type="text/javascript" ></script>-->

    	<script type="text/javascript">
			$(document).ready(function(){
                if($("#banner-nav"))
                {
                $("#banner-nav").slideShow({
                    item : 0,
                    busy : false,
                    paused : true,
                    interval : null,
                    animationSpeed : 1,
                    animationInterval : 10,
                    menuOpacityOff : 1,
                    menuOpacityOn : 1,
                    jQuery_banner : "banner-nav",
					<?php
						$allowed_page = explode("/", $_SERVER['REQUEST_URI']);
                        $qnode = $_REQUEST['q'];
                        if ($qnode == "")
						{
                        	//echo "onAfterTransition : makeAjax,";
						}
					?>
                    jQuery_banner_menu : "menu-banner"
					
                });
                }
            });
        </script>
  	</head>
  
  	<!--<body style="visibility:hidden" onload="unhideBody()">-->
    <body>
        <?php global $page_not_found;?>
    	<div id="bg">
        <?php
            $class = 0;
			$allowed_page = explode("/", $_SERVER['REQUEST_URI']);
			$qnode = $_REQUEST['q'];
			//echo "<br>qnode : $qnode ";
			// qnode : newsdetail 
			
			if($page_not_found)
			{
				//echo "<br>qnode : 5 ";
				$query = "SELECT background FROM drupage_backgrounds WHERE page = 'pagenotfound'";
				$results = db_query($query);
				$row = db_fetch_object($results);
				if($row->background)
						$background = 'misc/page_background/'.$row->background;
				else
						$background = 'themes/garland/images/bg.jpg';

				?>
				<img src="<?php echo $background;?>" />
				<?php
			}
			else if ($qnode=='projectdetails')
			{
				//For projectdetails
				
				if(isset($_GET['type']) && $_GET['type'] != "")
				{
					$functional_id = $_GET['type'];					
				}
				else
				{
					$query_functional_id = "SELECT id FROM druprojects ORDER BY `order` LIMIT 0,1";
					$result_functional_id = db_query($query_functional_id);
					$row_functional_id = db_fetch_array($result_functional_id);
					$functional_id = $row_functional_id['id'];
				}
				
				$query = "SELECT project_background FROM druprojects WHERE redirect_url = '".$functional_id."'";
				$results = db_query($query);
				$row = db_fetch_object($results);				
				if($row->project_background)
				{
					$background = $base_url.'/misc/project_background/'.$row->project_background;
					$alt=explode('.',$row->project_background);
					$altvalue=$alt[0];
				}
				else
				{
					$background = $base_url.'/themes/garland/images/bg.jpg';
					$altvalue='bg';
				}
			?>
				<img src="<?php echo $background;?>" alt="<?php echo $altvalue;?>"/>
				<?php
			}		
			else if ($qnode=='bios')
			{
				//For bios
				
				if(isset($_GET['type']) && $_GET['type'] != "")
				{
					$functional_id = $_GET['type'];
				}
				else
				{
					$query_functional_id = "SELECT id FROM drubios ORDER BY `order` LIMIT 0,1";
					$result_functional_id = db_query($query_functional_id);
					$row_functional_id = db_fetch_array($result_functional_id);
					$functional_id = $row_functional_id['id'];
				}
				
				$query = "SELECT project_background FROM drubios WHERE redirect_url = '".$functional_id."'";
				$results = db_query($query);
				$row = db_fetch_object($results);				
				if($row->project_background)
				{
					$background = $base_url.'/misc/bios_background/'.$row->project_background;
					$alt=explode('.',$row->project_background);
					$altvalue=$alt[0];
				}
				else
				{
					$background = $base_url.'/themes/garland/images/bg.jpg';
					$altvalue='bg';
				}
			?>
				<img src="<?php echo $background ?>" alt="<?php echo $altvalue;?>"/>
				<?php
			}
			else if(ereg("ourprocess" , $_SERVER[REQUEST_URI]))
			{

				//For projectdetails
                             
				if(isset($_GET['type']) && $_GET['type'] != "")
				{
					$functional_id = $_GET['type'];
				}
				else
				{
					$query_functional_id = "SELECT id FROM druprocess LIMIT 0,1";
					$result_functional_id = db_query($query_functional_id);
					$row_functional_id = db_fetch_array($result_functional_id);
					$functional_id = $row_functional_id['id'];
				}

				$query = "SELECT page_background FROM druprocess WHERE id = ".$functional_id;
				$results = db_query($query);
				$row = db_fetch_object($results);
				if($row->page_background)
					$background = 'misc/process_background/'.$row->page_background;
				else
					$background = 'themes/garland/images/bg.jpg';
				?>
        		<img src="<?php echo $background;?>"/>
				<?php
			}			
			else if(ereg("campaignlanding" , $_SERVER[REQUEST_URI]))
			{

				//For projectdetails
                             
				if(isset($_GET['type']) && $_GET['type'] != "")
				{
					$functional_id = $_GET['type'];
				}
				else
				{
					$query_functional_id = "SELECT id FROM campaign LIMIT 0,1";
					$result_functional_id = db_query($query_functional_id);
					$row_functional_id = db_fetch_array($result_functional_id);
					$functional_id = $row_functional_id['id'];
				}

				$query = "SELECT page_background FROM campaign WHERE id = ".$functional_id;
				$results = db_query($query);
				$row = db_fetch_object($results);
				if($row->page_background)
					$background = 'misc/campaign_background/'.$row->page_background;
				else
					$background = 'themes/garland/images/bg.jpg';
				?>
        		<img src="<?php echo $background;?>"/>
				<?php
			}
			else if(ereg("newsdetail" , $_SERVER[REQUEST_URI]))
			{
				//echo "<br>qnode : 4 ";
				//For projectdetails qnode : projectdetails 	
				/*echo "<pre>" ;
				print_r($_REQUEST) ;*/
                   $news_id = $_REQUEST['news_id'];   
				     if(!$news_id)
					{
						$query = "SELECT id FROM drunews LIMIT 0 , 1";
						$result = db_query($query);
						$news = db_fetch_array($result);
						$news_id = $news['id'];
					}
				/*if(isset($_GET['type']) && $_GET['type'] != "")
				{
					$functional_id = $_GET['type'];
				}
				else
				{
					$query_functional_id = "SELECT id FROM drunews LIMIT 0,1";
					$result_functional_id = db_query($query_functional_id);
					$row_functional_id = db_fetch_array($result_functional_id);
					$functional_id = $row_functional_id['id'];
				}*/

				$query = "SELECT page_background FROM drunews WHERE id = ".$news_id;
				$results = db_query($query);
				$row = db_fetch_object($results);
				if($row->page_background)
					$background = 'misc/news_background/'.$row->page_background;
				else
					$background = 'themes/garland/images/bg.jpg';
				?>
        		<img src="<?php echo $background;?>"/>
				<?php
			}	
            else if ($qnode == "newsroom")
            {
            	// misc/news_category_background/20100717_031025_news-960x850-070910.jpg
				// newsroom?newscategory=5
				//echo " newscategory : " . $_REQUEST['newscategory'];
				//echo "<br>qnode : 1 ";
				if(!empty($_REQUEST['newscategory']) && $_REQUEST['newscategory']>0)
				{
					$query = " SELECT * FROM drunews_category where category_id = " . $_REQUEST['newscategory'] . " LIMIT 0 , 1";
					$result = db_query($query);
					$backgroundImage = db_fetch_array($result);
				}
				else
				{
					$query = " SELECT * FROM drunews_category LIMIT 0 , 1";
					$result = db_query($query);
					$backgroundImage = db_fetch_array($result);				
				}
				
				
                if($backgroundImage['category_background'])
				{
                	$background = "misc/news_category_background/".$backgroundImage['category_background'];
				}
                else
                {
                	$query = "SELECT * FROM `drupage_backgrounds` WHERE page = '".$allowed_page[1]."' ";
					$result = db_query($query);
					$backgroundRow = db_fetch_array($result);
					
					if($backgroundRow['page'])
					{
						$background = 'misc/page_background/'.$backgroundRow['background'];
						$alt=explode('.',$backgroundRow['background']);
						$altvalue=$alt[0];
					}
					else
					{
						$background = 'themes/garland/images/bg.jpg';
                        //$backgroundImage = 'themes/garland/images/bg.jpg';
						$altvalue='bg';
					}
              	}
				?>
				<img src="<?php echo $background ?>" alt="<?php echo $altvalue;?>" />
				<?php
        	}
			else if($qnode == "newsdetail")
			{
				//qnode : newsdetail  
				//echo "<br>qnode : 3 ";
				$news_id = $_REQUEST['news_id'];
				//echo "<br>news_id: $news_id ";
				if(!$news_id)
				{
					$query = "SELECT id FROM drunews LIMIT 0 , 1";
					$result = db_query($query);
					$news = db_fetch_array($result);
					$news_id = $news['id'];
				}

				$query = " 	SELECT 
								category_background 
							FROM 
								drunews_category d
								INNER JOIN drunews n ON d.category_id = n.news_type
							WHERE n.redirect_url = '".$news_id."' ORDER BY RAND() LIMIT 0 , 1  ";
				$query = " 								
							SELECT 
								d.category_background 
							FROM 
								drunews_category_detail dnd 
								left join drunews n ON n.id = dnd.news_id 
								left join drunews_category d on d.category_id = dnd.category_id			 
							WHERE n.redirect_url = '".$news_id."' ORDER BY RAND() LIMIT 0 , 1	
							";
							
				//echo $query;
				
				$result = db_query($query);
				$backgroundRow = db_fetch_array($result);

				if($backgroundRow['category_background'])
					$background = "/misc/news_category_background/".$backgroundRow['category_background'];
				else
				{
					$query = "SELECT * FROM `drupage_backgrounds` WHERE page = '".$allowed_page[1]."' ";

					$result = db_query($query);
					$backgroundRow = db_fetch_array($result);

					if($backgroundRow['page'])
					{
						$background = 'misc/page_background/'.$backgroundRow['background'];
						$alt=explode('.',$backgroundRow['background']);
						$altvalue=$alt[0];
					}
					else
					{
						$background = 'themes/garland/images/bg.jpg';
						//$background = 'themes/garland/images/bg.jpg';
						$altvalue='bg';
					}
				}

				?>
				<img src="<?php echo $background ?>" alt="<?php echo $altvalue;?>"/>
				<?php
			}			
			else if(count($allowed_page) >= 3 && $_SERVER['REQUEST_URI'] != "/?q=user/login&destination=node")
			{
				//echo "<br>qnode : 6 ";
				for($slash = 3; $slash <= count($allowed_page); $slash++)
				{
					$add_slash .= "../";
				}
				
				if(stristr($qnode, 'search') !== FALSE)
				{
					$query = "SELECT background FROM drupage_backgrounds WHERE page = 'search'";				
					$results = db_query($query);
					$row = db_fetch_object($results);				
					if($row->background)
						$background = 'misc/page_background/'.$row->background;
					else
						$background = 'themes/garland/images/bg.jpg';
					?>
						<img src="<?php echo $add_slash.$background;?>"/>
					<?php
				}
				else
				{
					?>
					<img src="<?php echo $add_slash; ?>themes/garland/images/bg.jpg"/>
					<?php
				}
			}
        	else if ($qnode == "")
			{				
				//echo "<br>qnode : 7 ";
				$query = " SELECT d.* FROM druprojects d
                                            INNER JOIN druprojecthomepage dh ON dh.project_id = d.id
                                            ORDER BY d.homepage_order, d.id ";

				$results = db_query($query);
				$class = "home-page";
				while($row = db_fetch_array($results))
				{
					if($row['project_background'])
					{
						$background = 'misc/project_background/'.$row['project_background'];
						$alt=explode('.',$row['project_background']);
						$altvalue=$alt[0];
					}
					else
					{
						$background = 'themes/garland/images/bg.jpg';
						$altvalue='bg';
					}
					?>
						<div class="bg_img">
							<img src="<?php echo $background;?>" alt="<?php echo $altvalue;?>"/>
						</div>
					<?php
				}			
				?>

				<?php
			} 
			else
			{
				//echo "<br>qnode : 2 ";
				$allowed_page_final = explode("?", $allowed_page[1]);
				$query = "SELECT * FROM `drupage_backgrounds` WHERE page = '".$allowed_page_final[0]."' ";
				
				$result = db_query($query);
				$backgroundRow = db_fetch_array($result);
				
				if($backgroundRow['page'])
				{
					$background = 'misc/page_background/'.$backgroundRow['background'];
					$alt=explode('.',$backgroundRow['background']);
					$altvalue=$alt[0];
				}
				else
				{
					$background = 'themes/garland/images/bg.jpg';
					$altvalue='bg';
				}
				
				?>
				<img src="<?php echo $background ?>" alt="<?php echo $altvalue;?>"/>
				<?php
			}
			//echo "<br>qnode : 8 ";
			?>
        </div>
        <div id="slide_div" class="<?php echo $class ?>">
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
            	<?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul>'; endif; ?>
          		<?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
            	<?php print "</div>"; ?>
        	</div>
      
        	<div id="wrapper">
        		<div id="left_wrapper"></div>
        		<div id="main-wrapper">
            		<div id="center">
                		<?php print $content ?>
                            <div style="clear:both;"></div>
            		</div>
            		<div style="clear:both;"></div>         
           		</div>
          		<div id="right_wrapper"></div>
                <div style="clear:both;"></div>
        	</div>          
        	
            <div style="clear:both;"></div>
            
        	<?php //print $closure ?>
         </div>

            <div style="clear:both;"></div>


            <div id="footer-main">
                <div id="footer">
                    <?php
                        if ($allowed_page[2] != "landingpage")
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
			<script type="text/javascript">
  				var _gaq = _gaq || [];
  				_gaq.push(['_setAccount', 'UA-16594702-1']);
  				_gaq.push(['_trackPageview']);
				
  				(function() {
    				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  				})();
			</script>
	</body>   
</html>