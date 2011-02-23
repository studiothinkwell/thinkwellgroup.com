<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="imagetoolbar" content="no" />
		<title>Video In Lightbox</title>
		<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="../themes/garland/fancybox/jquery.fancybox-1.3.1.js"></script>
		<link rel="stylesheet" type="text/css" href="../themes/garland/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
		<script type="text/javascript">
			$(document).ready(function(){
				$("#various1").fancybox({
					'titlePosition'		: 'inside',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none'
				});
				$("#various2").fancybox({
					'titlePosition'		: 'inside',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none'
				});
				$("#various3").fancybox({
					'titlePosition'		: 'inside',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none'
				});
			});
		</script>
	</head>

	<body>
		<div id="content">
        	<a id="various1" href="#inline1">Play Video</a> 
			<div style="display: none;">
				<div id="inline1" style="width:520px;height:353px;overflow:auto; padding-left: 20px;">
					<embed height="333"
                       width="500"
                       flashvars="file=http://www.dev.thinkwellgroup.com/video/ice_age.flv&controlbar=none"
                       wmode="opaque"
                       controlbar="none"
                       allowscriptaccess="always"
                       allowfullscreen="true"
                       quality="high"
                       bgcolor="#FFFFFF"
                       name="ply1"
                       id="ply1"
                       style=""
                       src="../player/jwplayer.swf" type="application/x-shockwave-flash" />
				</div>
			</div>
     	</div>
	</body>
</html>