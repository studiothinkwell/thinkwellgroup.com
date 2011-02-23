<div class="gallery">
    <div class="gallery-image">
        <?php
        	$querybanner = "SELECT campaign_title FROM campaign ";
			if(isset($_GET['type']))
			{
				$querybanner .= "WHERE id = ".$_GET['type'];
			}
			else
			{
				$querybanner .= "WHERE id = 1";
			}
			$resultsbanner = db_query($querybanner);
			while ($rowbanner = db_result($resultsbanner))
			{
		?>
        <strong><?php echo $rowbanner; ?></strong>
        <?php
			}
		?>
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
            <?php
                $query = "SELECT image FROM drujobs ";
                if(isset($_GET['type']))
                {
                    $query .= "WHERE campaign_id = ".$_GET['type'];
                }
                else
                {
                    $query .= "WHERE campaign_id = 1";
                }
                $results = db_query($query);
                $i = 0;
                $html = "";
                while ($row = db_result($results))
                {
            ?>
            <a class="banner" href="javascript:void(0)" style="z-index: 1; opacity: 0;">
                <img src="misc/gallery/<?php echo $row; ?>" align="absmiddle" />
            </a>
            <?php
                    $html .= "<a style=\"opacity: 0.5;\" class=\"item\" href=\"javascript:bannerNav.toBanner($i);\">&nbsp;</a>";
                    $i++;
                }
            ?>
            <div class="menu-banner" style="opacity: 0.75;"><?php echo $html ?></div>
        </div>
        </div>
    </div>
    <div class="gallery-text">
        <div class="mail-heading">Behold Our Tales of Wonder</div>
        <div class="display-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vel justo tortor. Eleifend vel, pretium at ante  sectetur adipiscing elit.
<br />
Phasellus auctor diam eu sapien ullam lacinia. Aenean aliquam ornare dapibus. Morbi eleifend massa odio, sagitti.
<br />
Eleifend vel, pretium at ante  sectetur adipiscing elit. In luctus, erat in vulputate mattis, erat quam tincidunt neque, ut sollicitudin turpis eros at felis. In hac habitasse platea quam.
<br />
Morbi eleifend massa odio, sagitti.
</div>
    </div>
</div>
<div class="lower-display">
	<?php
    	$querypos1 = "SELECT * FROM drubanner WHERE position = 'pos1' ";
		if(isset($_GET['type']))
		{
			$querypos1 .= "AND campaign_id = ".$_GET['type'];
		}
		else
		{
			$querypos1 .= "AND campaign_id = 1";
		}
		$resultpos1 = db_query($querypos1);
		while ($rowpos1 = db_fetch_object($resultpos1))
		{
	?>
    <div class="lower-display1">
        <img src="misc/banner/<?php echo $rowpos1->image_name;?>">
        <div style="padding: 0 0 5px 46px; font-size:9pt;">
            <strong style="font-family:Helvetica; color:#ceeb11; font-weight:bold;"><?php echo $rowpos1->title;?></strong>
            <br />
            <strong style="font-family:Helvetica; color:#FFFFFF;"><?php echo $rowpos1->description;?></strong>
        </div>
    </div>
    <?php
		}

		$querypos2 = "SELECT * FROM drubanner WHERE position = 'pos2' ";
		if(isset($_GET['type']))
		{
			$querypos2 .= "AND campaign_id = ".$_GET['type'];
		}
		else
		{
			$querypos2 .= "AND campaign_id = 1";
		}
		$resultpos2 = db_query($querypos2);
		while ($rowpos2 = db_fetch_object($resultpos2))
		{
	?>
    <div class="lower-display2">
        <img src="misc/banner/<?php echo $rowpos2->image_name;?>">
        <div style="padding: 0 0 5px 9px; font-size:9pt;">
            <strong style="font-family:Helvetica; color:#ceeb11;"><?php echo $rowpos2->title;?></strong>
            <br />
            <strong style="font-family:Helvetica; color:#FFFFFF;"><?php echo $rowpos2->description;?></strong>
        </div>
    </div>
    <?php
		}

		$querypos3 = "SELECT * FROM drubanner WHERE position = 'pos3' ";
		if(isset($_GET['type']))
		{
			$querypos3 .= "AND campaign_id = ".$_GET['type'];
		}
		else
		{
			$querypos3 .= "AND campaign_id = 1";
		}
		$resultpos3 = db_query($querypos3);
		while ($rowpos3 = db_fetch_object($resultpos3))
		{
	?>
    <div class="lower-display3">
        <img src="misc/banner/<?php echo $rowpos3->image_name;?>">
        <div style="padding: 0 36px 5px 9px; font-size:9pt;">
            <strong style="font-family:Helvetica; color:#ceeb11;"><?php echo $rowpos3->title;?></strong>
            <br />
            <strong style="font-family:Helvetica; color:#FFFFFF;"><?php echo $rowpos3->description;?></strong>
        </div>
    </div>
    <?php
		}
	?>
</div>