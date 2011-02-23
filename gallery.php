<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<link type="text/css" rel="stylesheet" media="all" href="http://www.dev.thinkwellgroup.com/themes/garland/style.css" />
        <link rel="shortcut icon" href="/misc/favicon.ico" type="image/x-icon" />
    	<title>Video-Image Gallery</title>
        <script src="http://www.dev.thinkwellgroup.com/js/jquery-1.4.2.min.js"></script>
        <script src="http://www.dev.thinkwellgroup.com/js/jquery.slideShow.js"></script>
        <!--<script src="http://www.dev.thinkwellgroup.com/js/jsall.js"></script>-->
    	<script type="text/javascript">
			var galleryPaused = 0;
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
					 	jQuery_banner_menu : "menu-banner"					
                	});
                }
            });
			
			var prevHTML = '';
			var startGallery = 0;
			function showVideo(obj,url)
			{
				bannerNav.clearInterval();
				var item = $(obj).parent();
				prevHTML = item.html();
				startGallery = 0;
				var html = "<embed "+
						" type='application/x-shockwave-flash' "+
						" id='single2' "+
						" name='single2' "+
						" src='http://www.dev.thinkwellgroup.com/player/jwplayer.swf' "+
						" width='591' "+
						" height='333' "+
						" bgcolor='undefined' "+
						" allowscriptaccess='always' "+
						" allowfullscreen='true' "+
						" wmode='transparent' "+
						" flashvars='file="+url+"&controlbar=none' "+
						
						//" onclick='if(startGallery){var item = $(this).parent();item.html(prevHTML);bannerNav.setInterval();startGallery = 0;}else{startGallery = 1;}'"+
						"/>";
				item.html(html);
			}
        </script>
  	</head>
    
    <body style="background-color: #FFFFFF;"> 							
        <div class="project-details" id="projects">
            <div class="pdetails-middle">
                <div class="gallery-image" id="slideshow">
                    <div class="Banner">
                        <div class="Banner-tl"></div>
                        <div class="Banner-tr"><div></div></div>
                        <div class="Banner-bl"><div></div></div>
                        <div class="Banner-br"><div></div></div>
                        <div class="Banner-tc"><div></div></div>
                        <div class="Banner-bc"><div></div></div>
                        <div class="Banner-cl"><div></div></div>
                        <div class="Banner-cr"><div></div></div>
                        <div class="Banner-cc"></div>
                        <div id="banner-nav" class="banner-nav" >
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                                <img src="misc/project_gallery/20100622_011344_SNL-1-HERO.jpg" id="newal" />
                            </a>
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                            	<!--<img id="newal" src="misc/project_gallery/20100622_011344_SNL-2.jpg" onClick="showVideo(this,'http://www.dev.thinkwellgroup.com/video/ice_age.flv')" />-->
                            	<embed
                    				type='application/x-shockwave-flash'
                    				id='single2'
                    				name='single2'
                    				src='player/jwplayer.swf'
                    				width='591'
                    				height='333'
                    				bgcolor='undefined'
                    				allowscriptaccess='always'
                    				allowfullscreen='true'
                    				wmode='transparent'
                    				flashvars='image=http://www.dev.thinkwellgroup.com/misc/project_gallery/20100622_011344_SNL-2.jpg&file=http://www.dev.thinkwellgroup.com/video/ice_age.flv&controlbar=none'
                    				onClick="if(!galleryPaused){bannerNav.clearInterval();galleryPaused =1}else{galleryPaused=0;bannerNav.setInterval();}"
                    			/>
                            </a>
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                                <img src="misc/project_gallery/20100622_011344_SNL-3.jpg" id="newal" />
                            </a>
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                            	<!--<img id="newal" src="misc/project_gallery/20100622_011344_SNL-4-gal.jpg" onClick="showVideo(this,'http://www.dev.thinkwellgroup.com/video/raj4.flv')" />-->
                                <embed
                    				type='application/x-shockwave-flash'
                    				id='single2'
                    				name='single2'
                    				src='player/jwplayer.swf'
                    				width='601'
                    				height='333'
                    				bgcolor='undefined'
                    				allowscriptaccess='always'
                    				allowfullscreen='true'
                    				wmode='transparent'
                    				flashvars='image=http://www.dev.thinkwellgroup.com/misc/project_gallery/20100622_011344_SNL-4.jpg&file=http://www.dev.thinkwellgroup.com/video/raj4.flv&controlbar=none'
                    				onClick="if(!galleryPaused){bannerNav.clearInterval();galleryPaused =1}else{galleryPaused=0;bannerNav.setInterval();}"
                    			/>
                            </a>
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                                <img src="misc/project_gallery/20100622_011344_SNL-5.jpg" id="newal" />
                            </a>
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                                <img src="misc/project_gallery/20100622_011344_SNL-6.jpg" id="newal" />
                            </a>
                            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                                <img src="misc/project_gallery/20100622_011344_SNL-7.jpg" id="newal" />
                            </a>
                            <div class="menu-banner" id="menu-banner" style="">
                                <a href="javascript:bannerNav.playpause();" style="display: none;" class="playpause">Play / Pause</a>
                                <a href="javascript:bannerNav.prev();" style="display: none;" class="prev">Previous</a>
                                <a style="" class="item" href="javascript:galleryPaused=0;bannerNav.toBanner(0);">&nbsp;</a>
                                <a style="" class="item item-video" href="javascript:galleryPaused=0;bannerNav.toBanner(1);">&nbsp;</a>
                                <a style="" class="item" href="javascript:galleryPaused=0;bannerNav.toBanner(2);">&nbsp;</a>
                                <a style="" class="item item-video" href="javascript:galleryPaused=0;bannerNav.toBanner(3);">&nbsp;</a>
                                <a style="" class="item" href="javascript:galleryPaused=0;bannerNav.toBanner(4);">&nbsp;</a>
                                <a style="" class="item" href="javascript:galleryPaused=0;bannerNav.toBanner(5);">&nbsp;</a>
                                <a style="" class="item" href="javascript:galleryPaused=0;bannerNav.toBanner(6);">&nbsp;</a>
                                <a href="javascript:bannerNav.next();" style="display: none;" class="next">Next</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="" id="tabs-area-content">
                    <div class="project-details tabs-content" id="readTabContent">
                        <b>Client: Broadway Video</b><br /><br />Partnering with Broadway Video, 
                        Thinkwell is creating and producing an all-new kind of “live” attraction that 
                        brings to life over thirty-five years of classic characters and sketches from 
                        one of the longest- running and most groundbreaking comedy television shows in 
                        history, engaging guests with highly interactive media content and immersive 
                        technology.
                    </div>
                </div>
                <div class="gradient">&nbsp;</div>
            </div>            
        </div>        		
	</body>   
</html>