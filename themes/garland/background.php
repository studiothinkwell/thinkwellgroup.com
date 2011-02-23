<?php
	$class = 0;
	$allowed_page = explode("/", $_SERVER['REQUEST_URI']);
	$qnode = $_REQUEST['q'];
	if(count($allowed_page) >= 3 && $_SERVER['REQUEST_URI'] != "/?q=user/login&destination=node")
	{
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
				$background = $add_slash.'misc/page_background/'.$row->background;
			else
				$background = $add_slash.'themes/garland/images/bg.jpg';
		}
		else
		{
			$background = $add_slash.'themes/garland/images/bg.jpg';
		}
	}
	elseif ($qnode == "")
	{
		//For Homepage
		$query = " SELECT d.* FROM druprojects d INNER JOIN druprojecthomepage dh ON dh.project_id = d.id ORDER BY d.homepage_order, d.id ";

		$results = db_query($query);
		$class = "home-page";
		while($row = db_fetch_array($results))
		{
			if($row['project_background'])
				$background = 'misc/project_background/'.$row['project_background'];
			else
				$background = 'themes/garland/images/bg.jpg';
		}
	}
	elseif ($_GET['q'] == "node/14")
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
		
		$query = "SELECT project_background FROM druprojects WHERE id = ".$functional_id;
		$results = db_query($query);
		$row = db_fetch_object($results);				
		if($row->project_background)
			$background = 'misc/project_background/'.$row->project_background;
		else
			$background = 'themes/garland/images/bg.jpg';
	}
	elseif ($_GET['q'] == "node/16")
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
		
		$query = "SELECT project_background FROM drubios WHERE id = ".$functional_id;
		$results = db_query($query);
		$row = db_fetch_object($results);				
		if($row->project_background)
			$background = 'misc/bios_background/'.$row->project_background;
		else
			$background = 'themes/garland/images/bg.jpg';
	}
	elseif ($qnode == "newsroom")
	{
		$query = " SELECT * FROM drunews_category LIMIT 0 , 1";
		$result = db_query($query);
		$backgroundImage = db_fetch_array($result);
		
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
			}
			else
			{
				$background = 'themes/garland/images/bg.jpg';
			}
		}
		elseif($qnode == "newsdetail")
		{
			$news_id = $_REQUEST['news_id'];

			if(!$news_id)
			{
				$query = "SELECT id FROM drunews LIMIT 0 , 1";
				$result = db_query($query);
				$news = db_fetch_array($result);
				$news_id = $news['id'];
			}
			
			$query = " SELECT category_background FROM drunews_category d INNER JOIN drunews n ON d.category_id = n.news_type WHERE n.id = '".$news_id."' ";

			$result = db_query($query);
			$backgroundRow = db_fetch_array($result);

			if($backgroundRow['category_background'])
				$background = "misc/news_category_background/".$backgroundRow['category_background'];
			else
			{
				$query = "SELECT * FROM `drupage_backgrounds` WHERE page = '".$allowed_page[1]."' ";
				
				$result = db_query($query);
				$backgroundRow = db_fetch_array($result);
				
				if($backgroundRow['page'])
					$background = 'misc/page_background/'.$backgroundRow['background'];
				else
					$background = 'themes/garland/images/bg.jpg';
		   }
	}
	else
	{
		$allowed_page_final = explode("?", $allowed_page[1]);
		$query = "SELECT * FROM `drupage_backgrounds` WHERE page = '".$allowed_page_final[0]."' ";
		
		$result = db_query($query);
		$backgroundRow = db_fetch_array($result);
		
		if($backgroundRow['page'])
			$background = 'misc/page_background/'.$backgroundRow['background'];
		else
			$background = 'themes/garland/images/bg.jpg';
	}
?>