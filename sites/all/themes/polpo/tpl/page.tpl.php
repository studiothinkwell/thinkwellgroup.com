<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
<title>
	<?php error_reporting(0) ; ?>
	<?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  
  <!--[if lte IE 6]><link type="text/css" rel="stylesheet" media="all" href="<?php print $base_path.$directory; ?>/css/ie6.css" /><![endif]-->
  <!--[if IE 7]><link type="text/css" rel="stylesheet" media="all" href="<?php print $base_path.$directory; ?>/css/ie7.css" /><![endif]-->
  <!--[if gte IE 7]><link type="text/css" rel="stylesheet" media="all" href="<?php print $base_path.$directory; ?>/css/ie8.css" /><![endif]-->

<link rel="stylesheet" type="text/css" href="/themes/garland/fancybox/jquery.fancybox-1.3.1.css" media="screen">


<link href="/sites/all/themes/polpo/css/reset.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/colour.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/typography.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/tabs.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/wireframe.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/styles.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/css3.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/table.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/forms.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/status.css?e" media="all" rel="stylesheet" type="text/css">
<link href="/sites/all/themes/polpo/css/menu.css?e" media="all" rel="stylesheet" type="text/css">


<!--<link type="text/css" rel="stylesheet" media="all" href="http://www.dev.thinkwellgroup.com/themes/garland/style.css">-->


<link href="/sites/all/themes/polpo/css/modules.css?e" media="all" rel="stylesheet" type="text/css">


<?php print $scripts; ?>

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

		<script src="/js/jsall.js"></script>
        
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
<body id="polpo">
	<?php if ($logo || $title_text): ?>
	<div id="header">
		<?php if ($logo): ?><a href="<?php print $base_path ?>"><img src="<?php print $logo; ?>" alt="" id="logo" /></a><?php endif; ?>
		<?php if ($title_text): ?><h1<?php if ($logo): ?> class="break"<?php endif; ?>><?php print $title_text; ?></h1><?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if ($title): ?> 
	<div id="pagebar">
		<?php print $breadcrumb; ?>
		<h1><?php print $title; ?></h1>
		<?php print $tabs; ?>
	</div>
	<?php endif; ?>
	
	<?php if ($messages||$help): ?>
	<div id="statusbar">
		<?php print $messages; ?>
		<?php print $help; ?>
	</div>
	<?php endif; ?>
	
	<div id="content">
			<?php if ($sidebar||$fast_tasks): ?>
			<div id="maincol">
				<?php print $content; ?>
			</div>
			<div id="sidecol">
				<?php print $fast_tasks; ?>
				<?php print $sidebar; ?>
			</div>
		<?php else: ?>
			<?php print $content; ?>
		<?php endif; ?>	

			<div id="footer">
				<?php print $footer; ?>
				<p class="author-message">Polpo is a Drupal admin theme by <a href="http://www.previousnext.com.au/" title="Visit homepage">PreviousNext</a>.</p>
			</div>
	
	</div>
    <!--[if lte IE 6]>
    	<script type="text/javascript" src="<?php print $base_path.$directory; ?>/js/supersleight.plugin.js"></script>
    	<script type="text/javascript">
    	$('body').supersleight({shim: '<?php print $base_path.$directory; ?>/img/x.gif'});
    	</script>
    <![endif]-->	
<?php print $closure; ?>
</body>
</html>
