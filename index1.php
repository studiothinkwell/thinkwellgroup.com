<?
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	///////////////// include common class file
	require_once( "include/commonclass.php" ) ;

	///////////// creating object for the commonclass
	$classObj = new commonClass( '' , 'Home Page' ) ;
	
	if( isset($_SESSION[sess_memId]) && strlen($_SESSION[sess_memId]) < 6 ) {
		$classObj->redirect("account/account.php?") ;
	}
	
	//// todays date
	$todayDate  = mktime( 0,0,0,date("m"),date("d"),date("Y") ) ;

	//// events from eventlisted
	$classObj->tbl_event_master() ;
	$condition = " event_status = 'P' AND event_from = 'E' AND event_start_date >= '".$todayDate."' ORDER BY RAND()" ;

	$result = $classObj->selectRecord( $condition ) ;
	$recordCount = $classObj->recordNumber($result) ;

	if( $recordCount > 0 )
	{
		$eventList = $classObj->fetchRecord( $result ) ;
		
		$eventCounter = 1 ;

		$leftEvents  = '<table width="90%" cellpadding="0" cellspacing="0" border="0"><tr>' ;
		
			$_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
			$_xml .="<artworkinfo>";
			
			$_xml .= "<albuminfo>";
			$_xml .="<artLocation>images/index2/publishanevent.jpg</artLocation>";
			$_xml .="<trackName>Publish An Event</trackName>";
			$_xml .="<artist>EventsListed</artist>";
			$_xml .="<albumName>Event Marketing</albumName>";
			$_xml .="<httptype></httptype>";
			$_xml .="<url>p_redirect.htm</url>";
			$_xml .= "</albuminfo>";
			
		foreach( $eventList as $eventKey=>$eventValue )
		{
			$eventOwner = $eventValue[member_id] ;
			$eventID = $eventValue[event_id] ;
			$profileDir = $eventValue[event_profile_folder] ;
			$eventName = ucwords($eventValue[event_title]) ;
			$eventProfile = _PROFILE_DIRECTORY.$eventOwner."/".$profileDir."/" ;

			if( $eventCounter == 1 )
				$ID .= "previewImg_".$eventID ;
			else
				$ID .= "@previewImg_".$eventID ;
				
			if( $eventCounter == 1 )
				$evName .= "evName_".$eventID ;
			else
				$evName .= "@evName_".$eventID ;		
			
			////// event image
			$classObj->tbl_event_ticket_master() ;
			$condition = " event_id = '".$eventValue[event_id]."'" ;
			$result = $classObj->selectRecord( $condition ) ;
			$recordCount = $classObj->recordNumber( $result ) ;

			if( $recordCount > 0 )
			{
				$ticketListing = $classObj->fetchRecord( $result ) ;
			
				$ticketImage = $ticketListing[0][ticket_image] ;
		
				$eventPhoto = _PROFILE_DIRECTORY.$eventOwner."/".$profileDir."/".$ticketImage ;

				if( !trim($ticketImage) )
					$eventPhoto = "images/no-img.gif" ;
			}
			else
			{
				$eventPhoto = "images/no-img.gif" ;
			}
			////// end event image
			
			$leftEvents .= '<td width="5%" align=left >' ;
			$leftEvents .= '<a href="'.$eventProfile.'" class="mainpageLink" title="'.$eventName.'">
			<div id="previewImg_'.$eventID.'" style="display:none;position:absolute;color:#FFFFFF;filter:alpha(opacity=70);opacity:0.5;background-color:#000;border:1px solid #FFFFFF;text-align:center; font-weight:bold; font-size:10px;margin-top:10px;"  onmouseover="loadEv(\'evName_'.$eventID.'\',\'shoe_'.$eventID.'\'); " onmouseout="vanish();" ><br>
				<div style="font-weight:bold;color:#FFFFFF;">'.$eventName.'</div>
			</div>
			</a>' ;
			$leftEvents .= '<a href="'.$eventProfile.'" class="mainpageLink" title="'.$eventName.'">';
			
			$leftEvents .= '<div><img src="'.$eventPhoto.'" width="98" height=60 onmouseover="loadIMGPreview(\'previewImg_'.$eventID.'\')" height="77" style="border:2px solid #EAEAEA"/></div>' ;
			$leftEvents .= "</a></td>" ;
			
			$_xml .= "<albuminfo>";
			$_xml .="<artLocation>".$eventPhoto."</artLocation>";
			$_xml .="<trackName>".$eventName."</trackName>";
			$_xml .="<artist>EventsListed</artist>";
			$_xml .="<albumName>Event Promotion</albumName>";
			$_xml .="<httptype></httptype>";
			$_xml .="<url>".$profileDir."</url>";
			$_xml .= "</albuminfo>";


			if( $eventCounter % 4 == 0 )  
				$leftEvents .= "</tr><tr>" ;
						
			if( $eventCounter % 7 == 0 )
				break ;

			$eventCounter++ ;
		}

		$leftEvents  .= '</tr></table>' ;
		
			$_xml .= "<albuminfo>";
			$_xml .="<artLocation>images/index2/publishanevent.jpg</artLocation>";
			$_xml .="<trackName>Publish An Event</trackName>";
			$_xml .="<artist>EventsListed</artist>";
			$_xml .="<albumName>Search Events Across The Globe</albumName>";
			$_xml .="<httptype></httptype>";
			$_xml .="<url>p_redirect.htm</url>";
			$_xml .= "</albuminfo>";
			$_xml .="</artworkinfo>";
	}
	//// end events  event/setupevent.php?action=step1


	//// events from ticketnetwork
	$classObj->tbl_event_master() ;
	$condition = " event_status = 'P' AND event_from = 'T' AND event_start_date >= '".$todayDate."' ORDER BY RAND()" ;

	$result = $classObj->selectRecord( $condition ) ;
	$recordCount = $classObj->recordNumber($result) ;

	if( $recordCount > 0 )
	{
		$eventList = $classObj->fetchRecord( $result ) ;
		
		$ticketNetworkEventCnt = 1 ;

		$rightEvents  = '<table width="100%" cellpadding="2" cellspacing="2" border="0"><tr>' ;
				
		foreach( $eventList as $eventKey=>$eventValue )
		{
			$eventOwner = $eventValue[member_id] ;
			$eventID = $eventValue[event_id] ;
			$profileDir = $eventValue[event_profile_folder] ;
			$eventName = ucwords($eventValue[event_title]) ;
			$eventProfile = _PROFILE_DIRECTORY.$eventOwner."/".$profileDir."/" ;

			if( $eventCounter == 1 )
				$ID .= "previewImg_".$eventID ;
			else
				$ID .= "@previewImg_".$eventID ;
				
			if( $eventCounter == 1 )
				$evName .= "evName_".$eventID ;
			else
				$evName .= "@evName_".$eventID ;		
			
			////// event image
			$classObj->tbl_event_ticket_master() ;
			$condition = " event_id = '".$eventValue[event_id]."'" ;
			$result = $classObj->selectRecord( $condition ) ;
			$recordCount = $classObj->recordNumber( $result ) ;

			if( $recordCount > 0 )
			{
				$ticketListing = $classObj->fetchRecord( $result ) ;
			
				$ticketImage = $ticketListing[0][ticket_image] ;
		
				$eventPhoto = _PROFILE_DIRECTORY.$eventOwner."/".$profileDir."/".$ticketImage ;

				if( !file_exists($eventPhoto) || !trim($ticketImage) )
					$eventPhoto = "images/no-img.gif" ;
			}
			else
			{
				$eventPhoto = "images/no-img.gif" ;
			}
			////// end event image
			
			$rightEvents .= '<td width="50%" align=left >' ;
			$rightEvents .= '<a href="'.$eventProfile.'" class="mainpageLink" title="'.$eventName.'"><div id="previewImg_'.$eventID.'" style="display:none;position:absolute;color:#FFFFFF;filter:alpha(opacity=70);opacity:0.5;background-color:#000;border:1px solid #FFFFFF;text-align:center; font-weight:bold; font-size:10px;" onmouseover="loadEv(\'evName_'.$eventID.'\',\'shoe_'.$eventID.'\'); " onmouseout="vanish();" ><br>'.$eventName.'</div></a>' ;
			$rightEvents .= '<a href="'.$eventProfile.'" class="mainpageLink" title="'.$eventName.'">';
			$rightEvents .= '<div ><img src="'.$eventPhoto.'" width="91" onmouseover="loadIMGPreview(\'previewImg_'.$eventID.'\')" height="77" style="border:2px solid #EAEAEA;"/></div>' ;
			$rightEvents .= "</a></td>" ;

			if( $ticketNetworkEventCnt % 2 == 0 )  
				$rightEvents .= "</tr><tr>" ;
			
			if( $ticketNetworkEventCnt % 4 == 0 )
				break ;

			$eventCounter++ ;
			$ticketNetworkEventCnt++ ;
		}

		$rightEvents  .= '</tr></table>' ;
	}
	//// end events ticketnetwork
	//print_r($_SESSION) ; die() ;
	
	//// total number of registered user
	$classObj->tbl_member_master() ;

	$condition = "" ;
	$result = $classObj->selectRecord( $condition ) ;
	$registerMemberCount = $classObj->recordNumber( $result ) ;
	$registerMemberCount = number_format( $registerMemberCount ) ;
	////// end total number of users
	
	$classObj->tbl_event_master() ;
	$condition = "event_status = 'P'" ;
	$result = $classObj->selectRecord( $condition ) ;
	$totalEventsregister = $classObj->recordNumber( $result ) ;

		@extract( $_REQUEST ) ;
		
		if( $err )
		{
			$errorMsg = $classObj->displayErrorMessage($err) ;
		}
		
$day12 = date ("d");
$month12 = date ("m");
$year12 = date ("y");
		
		
	?><DIV style="PADDING-RIGHT: 0px; PADDING-LEFT:0px;  margin-top:-13px; FLOAT: left; z-index:100; margin-left:-15px; _margin-left:0; PADDING-BOTTOM: 5px; WIDTH: 980px;"><table width=980 border=0 cellspacing=0 cellpadding=0><tr><td height=37 colspan=2 align=right valign=middle><div style="list-style:none; color:#FF0000; width:200px; z-index:0; position:relative; text-align:right;"><?=$errorMsg?></div></td></tr><tr><td width=652 align=left valign=top style=padding-top:18px>
<div style="position:relative; top:0; width:652px; margin-top:-10px; text-align:center"><strong>Double Click</strong></div>
<object classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0 width=652 height=132> <param name=movie value=includes/indexflips.swf /><param name=quality value=high /><param name=wmode value=transparent /><embed src=includes/indexflips.swf quality=high wmode=transparent pluginspage=http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash width=652 height=132></embed></object><div style="text-align:left; background: url(images/index2/panel-bottom2.gif) no-repeat left top; height:37px; width:652px; margin-top:-3px;"><?php $keywords = "Search Popular Events";?>

<div style="padding:10px 13px 0 0; float:right; "><h2 style="color:#FFF; background:#000;"><?=$totalEventsregister?> EventsListed!</h2></div>
<form name=frmsearch action=event/eventlist.php method=get style=" height:36px; padding:6px 0 0 10px">

<input type=text style="font-weight:bold; border: 2px solid #ffffff; width:432px;" name=keyword value='<?=$keywords?>' onclick="if(this.value=='Search Popular Events')this.value=''" /> <input name=Submit2 type=submit value="" style="height:20px; width:30px; margin-top:1px; font-weight:bold; background: url(images/index2/button-backg.gif) no-repeat left top; border:none" />
<input type="hidden" name="start_date" value="" />
<input type="hidden" name="end_date" value="<?php echo "$month12/$day12/$year12"; ?>" />
</form></div>

</td><td width=328 rowspan=2 align=center valign=middle>

<div style="padding:10px 10px 2px 40px;">

<a href=p_redirect.htm><img src=images/index/publish_index.png alt="Create Your Own Event Page" width=283 height=47 border=0 /></a>
<br />
<!--<a href="search/events.php"><img src="images/index/search_index.png" alt="Search Events From Across The Globe" width="283" height="47" border="0" /></a>-->

<br />
<br /><img src="images/index/tag_bottom_index.png" width="283" height="65" alt="EventsListed helps you publish, promote, manage, and sell your event across Social Media."/><br />
          </div>
          
          </td></tr></table><br /><br />
<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td valign=top><table width=300 height=470 border=0 cellpadding=0 cellspacing=0><tr><td width=6 align=right style="background:url(images/index2/tl.gif) no-repeat bottom left;width:6px;">&nbsp;</td><td width=288 valign=bottom id=head-top-1 style="background:url(images/index2/repeater-top.gif) repeat-x bottom left; color:#FFFFFF;"><a href=http://youtube.com/user/EventsListed target=_blank><img src=images/blogs/yt.png alt=YouTube width=32 height=32 border=0 style=float:right /></a><div style=margin-top:10px;float:left><a href=javascript:video1()>Introduction</a> | <a href=javascript:video2()>Get Started</a> | <a href=javascript:video3()>Benefits</a></div></td><td width=6 align=left style="background:url(images/index2/tr.gif) no-repeat bottom right; width:6px;">&nbsp;</td></tr><tr><td colspan=3 align=center bgcolor=#e4e4e4><div style=padding:5px;><script src="<?=$path?>javascript/swfobject.js" type="text/javascript"></script><div id=player1>This text will be replaced</div><div id=player2 style=display:none>This text will be replaced</div><div id=player3 style=display:none>This text will be replaced</div><script type="text/javascript">var so = new SWFObject('includes/player.swf','mpl','287','435','9');so.addParam('allowscriptaccess','always');so.addParam('allowfullscreen','true');so.addParam('flashvars','&file=includes/video1.xml&backcolor=e4e4e4&frontcolor=000000&lightcolor=666666&playlist=bottom');so.write('player1');var s1 = new SWFObject('includes/player.swf','mpl','287','435','9');s1.addParam('allowscriptaccess','always');s1.addParam('allowfullscreen','true');s1.addParam('flashvars','&file=includes/video2.xml&backcolor=e4e4e4&frontcolor=000000&lightcolor=666666&playlist=bottom');s1.write('player2');var s2 = new SWFObject('includes/player.swf','mpl','287','435','9');s2.addParam('allowscriptaccess','always');s2.addParam('allowfullscreen','true');s2.addParam('flashvars','&file=includes/video3.xml&backcolor=e4e4e4&frontcolor=000000&lightcolor=666666&playlist=bottom');s2.write('player3');</script></div></td></tr><tr style=height:6px;><td style="background:url(images/index2/bl.gif) no-repeat bottom left" align=right></td><td style="height:6px; " height=6 bgcolor=#e4e4e4></td><td style="background:url(images/index2/br.gif) no-repeat bottom right" align=left></td></tr></table></td><td align=right valign=top><table width=630 height=485 border=0 cellspacing=0 cellpadding=0><tr><td width=6 align=right style="background:url(images/index2/tl.gif) no-repeat bottom left; width:6px;">&nbsp;</td><td width=618 valign=bottom id=head-top-1 style="background:url(images/index2/repeater-top.gif) repeat-x bottom left; color:#FFFFFF;"><a href=http://www.facebook.com/pages/edit/?id=14944686641#/pages/Tewantin-Australia/Events-Listed-web-page-publishing-networking/14944686641 target=_blank><img src=images/blogs/facebook.png alt=Facebook width=32 height=32 border=0 style=float:right /></a> <a href=http://friendfeed.com/eventline target=_blank><img src=images/blogs/ff.png alt=FriendFeed width=32 height=32 border=0 style=float:right /></a> <a href=http://digg.com/users/eventslisted target=_blank><img src=images/blogs/digg.png alt=Digg width=32 height=32 border=0 style=float:right /></a> <a href=http://www.linkedin.com/in/simonuford target=_blank><img src=images/blogs/linkedin.png alt=LinkedIn width=32 height=32 border=0 style=float:right /></a> <a href=http://twitter.com/eventslisted target=_blank><img src=images/blogs/twitter.png alt=Twitter width=32 height=32 border=0 style=float:right /></a><div style=margin-top:10px;float:left><a href=javascript:index21()>Our Community</a> | <a href=javascript:index22()>About Us</a> | <a href=doors_closed.php target=_blank>Join Us</a></div></td><td width=6 align=left style="background:url(images/index2/tr.gif) no-repeat bottom right; width:6px;">&nbsp;</td></tr><tr><td height=450 colspan=3 valign=middle bgcolor=#e4e4e4><div id=g1 style=padding:15px;><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td valign=top><script type="text/javascript" src="http://www.google.com/friendconnect/script/friendconnect.js"></script><div id=div-5659069897776162775 style="width:200px; border:1px solid #e4e4e4;"></div><script type="text/javascript">var skin = {};skin['BORDER_COLOR'] = '#e4e4e4';skin['ENDCAP_BG_COLOR'] = '#f2f2f2';skin['ENDCAP_TEXT_COLOR'] = '#333333';skin['ENDCAP_LINK_COLOR'] = '#0000cc';skin['ALTERNATE_BG_COLOR'] = '#f2f2f2';skin['CONTENT_BG_COLOR'] = '#ffffff';skin['CONTENT_LINK_COLOR'] = '#0000cc';skin['CONTENT_TEXT_COLOR'] = '#333333';skin['CONTENT_SECONDARY_LINK_COLOR'] = '#7777cc';skin['CONTENT_SECONDARY_TEXT_COLOR'] = '#666666';skin['CONTENT_HEADLINE_COLOR'] = '#333333';skin['NUMBER_ROWS'] = '8';google.friendconnect.container.setParentUrl('/');google.friendconnect.container.renderMembersGadget({ id: 'div-5659069897776162775',site: '12229734849477614328' },skin);</script></td><td valign=top><div style="height:417px; overflow-x:hidden; overflow-y:auto;"><div id=div-8441502830768037023 style="width:375px; border:1px solid #e4e4e4;"></div></div><script type="text/javascript">var skin = {};skin['BORDER_COLOR'] = '#e4e4e4';skin['ENDCAP_BG_COLOR'] = '#f2f2f2';skin['ENDCAP_TEXT_COLOR'] = '#333333';skin['ENDCAP_LINK_COLOR'] = '#0000cc';skin['ALTERNATE_BG_COLOR'] = '#f2f2f2';skin['CONTENT_BG_COLOR'] = '#ffffff';skin['CONTENT_LINK_COLOR'] = '#0000cc';skin['CONTENT_TEXT_COLOR'] = '#333333';skin['CONTENT_SECONDARY_LINK_COLOR'] = '#7777cc';skin['CONTENT_SECONDARY_TEXT_COLOR'] = '#666666';skin['CONTENT_HEADLINE_COLOR'] = '#333333';skin['DEFAULT_COMMENT_TEXT'] = '- add your comment here -';skin['HEADER_TEXT'] = 'Comments';skin['POSTS_PER_PAGE'] = '8';google.friendconnect.container.setParentUrl('/');google.friendconnect.container.renderWallGadget({ id: 'div-8441502830768037023',site: '12229734849477614328','view-params':{"disableMinMax":"true","scope":"SITE","features":"video,comment","startMaximized":"true"}},skin);</script></td></tr></table></div><div id=g2 style="padding:0 15px; width:600px; display:none"><h1 align=center style=color:#000000>Cutting edge technology to take your event to a whole new level.</h1><br /> <h2 align=center>Indulge in a symphony of astounding event marketing, planning &amp; networking tools that include online ticketing &amp; merchandising paid directly into your bank account.</h2><p align=center><B><I>&quot;Are you promoting a concert, club night, theatre event, conference, party or a sporting event?&quot;</I></B></p><p align=center><B><I>&quot;Do you need online ticket sales &amp; invitations?&quot;</I></B></p><p align=center>Utilize our FREE multi media rich platform to seamlessly promote and execute your event planning, marketing and management of conferences, seminars, fundraisers, club nights, theatre events, parties, sports events and any other type of entertainment, including Las Vegas shows. Who says what happens in Vegas stays in Vegas?</p><p align=center><B>We say &quot;Publish It! List It! Remember It!</B>&quot;<br /> <br /> We charge a small fee on ticket or merchandise sales made from your web page directly into your bank account. No sales, no fees &ndash; as the platform itself is <B>FREE</B>. Whether you are a huge company or an individual, anything worth sharing can become an Event Listed that you can share and commemorate with colleagues, friends, and family.. This site will attract a vibrant mixture of users to promote and celebrate together. Keep your event private or make it a blockbuster by publishing as public in the search engines. Syndicate your event data across multiple Web 2.0 content sharing websites &amp; multi layer your ticket commissions so distribution spreads virally</p><p align=right><I>- The Events People</I></p></div></td></tr><tr style=height:6px;><td style="background:url(images/index2/bl.gif) no-repeat bottom left" align=right></td><td style="height:6px; " height=6 bgcolor=#e4e4e4></td><td style="background:url(images/index2/br.gif) no-repeat bottom right" align=left></td></tr></table></td></tr></table><br /></div><? $homePageFlag = "Home" ; require_once( "include/footer.php" ) ; ?> <?php $file= fopen("includes/albuminfo2.xml", "w"); fwrite($file, $_xml); fclose($file); ?> 