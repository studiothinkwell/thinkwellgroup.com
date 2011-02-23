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