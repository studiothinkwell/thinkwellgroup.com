<?php
	function page_background_save()
	{
    	upload_image('ourwork');
    	upload_image('ourprocess');
    	upload_image('ourpeople');
	 	upload_image('contactus');
    	upload_image('careers');
    	upload_image('newsroom');
	 	upload_image('termsnconditions');
    	upload_image('privacypolicy');
	  	upload_image('aboutus');
        upload_image('search');
        upload_image('pagenotfound');
        upload_image('trademark');
		
    	header("Location: index.php?q=page_background&success=1");
    	exit;
	}
	
	function upload_image($page)
	{
    	$query = " SELECT id FROM drupage_backgrounds WHERE page = '$page' ";
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$imageName = process_image($page);
		
    	if($row)
    	{
        	$query = " UPDATE drupage_backgrounds SET `background` = '$imageName'  WHERE page = '$page' ";
    	}
    	else
		{
        	$query = " INSERT INTO  drupage_backgrounds (`page`,`background`) VALUES ('$page','$imageName') ";
    	}
		
    	db_query($query);
	}
	
	function isImage($filename = '')
	{
    	$allowedExtensions = array("jpg","jpeg","gif","png","bmp");
		
    	if (!in_array( end( explode(".",strtolower($filename)) ),$allowedExtensions ))
    	{
        	return false;
    	}
		
    	return true;
	}
	
	function process_image($page)
	{
    	$oldFilename = $_FILES[$page]['name'];
		
    	if($oldFilename && isImage($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = " SELECT id , `background` FROM drupage_backgrounds WHERE page = '$page' ";
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'page_background'.DS;
    	if($filename)
    	{
        	if(move_uploaded_file($_FILES[$page]['tmp_name'],$path.$filename ))
        	{
            	return $filename;
        	}
    	}
		
    	return $row['background'];
	}
?>