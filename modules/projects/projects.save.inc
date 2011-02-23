<?php
	class Error
	{
    	static $error = '';
	}
	
	function isImage($filename = '',$moreExt = array())
	{
    	$allowedExtensions = array("jpg","jpeg","gif","png","bmp");
        $allowedExtensions = array_merge($allowedExtensions , $moreExt);
		
    	if (!in_array( end( explode(".",strtolower($filename)) ),$allowedExtensions ))
    	{
        	return false;
    	}
		
    	return true;
	}
	
	function projects_save()
	{
	
    	$id = projects_get($_REQUEST , 'project_id' , 0 );
		
    	$b = projects_validate($_REQUEST);
    	
    	if(!projects_validate($_REQUEST))
    	{
        	$msg = Error::$error;
        	header("Location: index.php?q=projects/new&msg=".$msg );
        	exit;
    	}
		
    	if($id)
		{
        	projects_update_page($id , $_REQUEST);
    	}
    	else
		{
        	$id = projects_insert_page($_REQUEST );
    	}
		
		if(isset($_POST['deletesidevideo']))
		{
			$query = "UPDATE druprojects SET video = '', side_video_image = '' WHERE id = ".$id;
			db_query($query);
		}
    	projects_store_photo_gallery($id, $_REQUEST  );
    	projects_store_serviceperformed($id , $_REQUEST  );
		
    	projects_store_categories($id , $_REQUEST);
		
    	projects_store_home_page($id , $_REQUEST);
		
    	projects_delete_images($id);
        projects_delete_probackground( $id);
        projects_delete_thumbnail( $id);
        projects_delete_sidevedio( $id);
        projects_save_order($id);
        projects_save_alttag($id);
    	header("Location: index.php?q=projects&project_id=$id&msg=success");
    	exit;
	}
	function projects_save_alttag($id)
{
     $imageAlt = $_REQUEST['alt'];
      $imageIds = $_REQUEST['imageOrderid'];

     for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update druprojects_images set alttag='".$imageAlt[$i]."' WHERE id = ".$imageIds[$i];
            db_query($query);

     }
}
function projects_delete_probackground( $id)
{
$projectIds = $_REQUEST['delete_background'];

$query = "SELECT * FROM druprojects WHERE project_background ='". $projectIds."' ";
$result = db_query($query);

$path = MISC_PATH.DS.'project_background'.DS;

while ($row = db_fetch_array($result))
{
//echo $path.$row['project_bio_thumbnail'];

if(unlink($path.$row['project_background']))
{
    $query = " Update druprojects set project_background='' WHERE id = ".$row['id'];
    db_query($query);

}
//exit;
}

}
function projects_delete_thumbnail( $id)
{
$imageIds = $_REQUEST['delete_thumbnail'];

$query = "SELECT * FROM druprojects WHERE project_bio_thumbnail ='". $imageIds."' ";
$result = db_query($query);

$path = MISC_PATH.DS.'project_bio_thumbnail'.DS;

while ($row = db_fetch_array($result))
{
//echo $path.$row['project_bio_thumbnail'];

if(unlink($path.$row['project_bio_thumbnail']))
{
    $query = " Update druprojects set project_bio_thumbnail='' WHERE id = ".$row['id'];
    db_query($query);

}
//exit;
}

}
function projects_delete_sidevedio( $id)
{
$vedioIds = $_REQUEST['delete_sidevedio'];

$query = "SELECT * FROM druprojects WHERE side_video_image ='". $vedioIds."' ";
$result = db_query($query);

$path = MISC_PATH.DS.'project_video'.DS;

while ($row = db_fetch_array($result))
{
//echo $path.$row['project_bio_thumbnail'];

if(unlink($path.$row['side_video_image']))
{
    $query = " Update druprojects set side_video_image='' WHERE id = ".$row['id'];
    db_query($query);

}
//exit;
}

}

function projects_save_order($id)
{
     $imageOrder = $_REQUEST['reorder'];
      $imageIds = $_REQUEST['imageOrderid'];

      //var_dump($imageOrder);

      asort($imageOrder);
      //echo "<br/>";

      //var_dump($imageOrder);

      $i = 0;
      foreach ($imageOrder as $key => $value){
        $query = " Update druprojects_images set `order`= '".$i."' WHERE id = ".$key;
        db_query($query);
        $i++;
      }

      //exit;


     /*for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update drubios_images set `order`=".$imageOrder[$i]." WHERE id = ".$imageIds[$i];
            db_query($query);

     }*/
}


	function projects_store_categories($projectId , $arr)
	{
    	$query = "DELETE FROM druproject_category_map WHERE project_id = ".$projectId;
    	db_query($query);
		
    	$categories = projects_get($arr, 'project_category' , '' , REAL_ESCAPE);
		
    	for($i = 0 ; $i < count($categories) ; $i++)
    	{
        	$query = "INSERT INTO druproject_category_map (`project_id`, `category_id`) VALUES ('$projectId','".$categories[$i]."')";
        	db_query($query);
    	}
	}
	
	function projects_store_home_page($projectId , $arr = array())
	{
    	if(projects_get($arr , 'home' , '') == "yes")
		{
        	$query = "SELECT project_id FROM druprojecthomepage d WHERE project_id = '".$projectId."'";
        	$result = db_query($query);
        	$homePage = db_fetch_array($result);
			
        	if($homePage['project_id'])
			{
            	//$query = " UPDATE druprojecthomepage SET project_id = '$projectId' ";
        	}
        	else
			{
            	$query = "INSERT INTO druprojecthomepage (`project_id`) VALUES ('$projectId')";
        	}
        	db_query($query);
    	}
    	else
		{
        	$query = "DELETE FROM druprojecthomepage WHERE  project_id = '".$projectId."'";
        	db_query($query);
    	}
	}
	
	function projects_update_page($id , $arr = array())
	{
		
    	$url = $_REQUEST['url'];
		
		$image_name = process_image($id);
    	$background = process_background($id);
		
    	$video = process_video($id);
		
    	$description = projects_get($arr, 'description' , '' , REAL_ESCAPE);
    	//$description = substr($description, 0 , CHAR_MAX_LENGTH);
		$HomepageImage = "";
		if($_FILES['homepage_image']['name'] != "")
		{
			$path = MISC_PATH.DS.DS.'homepage_image'.DS;
			$HomepageImage = $_FILES['homepage_image']['name'];
			$HomepageImage = date("Ymd_his_").$HomepageImage;
			move_uploaded_file($_FILES['homepage_image']['tmp_name'], $path.$HomepageImage);
		}
		else
		{
			$query = "SELECT homepage_image FROM druprojects WHERE id = ".$id;
			$results = db_query($query);
			$row = db_fetch_object($results);
			$HomepageImage = $row->homepage_image;
		}
		
		if($_FILES['side_video_image']['name'] != "")
		{
			$path = MISC_PATH.DS.DS.'project_video'.DS;
			$side_video_image = $_FILES['side_video_image']['name'];
			$side_video_image = date("Ymd_his_").$side_video_image;
			move_uploaded_file($_FILES['side_video_image']['tmp_name'], $path.$side_video_image);
		}
		else
		{
			$query = "SELECT side_video_image FROM druprojects WHERE id = ".$id;
			$results = db_query($query);
			$row = db_fetch_object($results);
			$side_video_image = $row->side_video_image;
		}
		
    	$query = "UPDATE druprojects SET `title` = '".projects_get($arr, 'title' , '' , REAL_ESCAPE)."',
                						 `client_name` = '".projects_get($arr, 'client_name' , '' , REAL_ESCAPE)."',
                						 `description` = '".projects_get($arr, 'description' , '' , REAL_ESCAPE)."',
                						 `home_description` = '".projects_get($arr, 'home_description' , '' , REAL_ESCAPE)."',
                						 `homepage_url` = '".projects_get($arr, 'homepage_url' , '' , REAL_ESCAPE)."',
                						 `project_bio_first_name` = '".projects_get($arr, 'project_bio_first_name' , '' , REAL_ESCAPE)."',
                						 `project_bio_last_name` = '".projects_get($arr, 'project_bio_last_name' , '' , REAL_ESCAPE)."',
                						 `project_bio_thumbnail` = '".$image_name."',
                						 `project_category` = '".projects_get($arr, 'project_category' , '' , REAL_ESCAPE)."',
                						 `project_background` = '$background',
                						 `project_url` = '".projects_get($arr, 'project_url' , '' , REAL_ESCAPE)."',
                						 `video` = '".$video."',
                						 `side_video_image` = '".$side_video_image."',
                						 `order` = '".projects_get($arr, 'order' , '' , REAL_ESCAPE)."',
                						 `homepage_order` = '".projects_get($arr, 'homepage_order' , '' , REAL_ESCAPE)."',
                						 `homepage_image` = '".$HomepageImage."',
                						 `redirect_url` = '".$url."'
                  WHERE id = ".$id;
    	db_query($query);
    	return $id;
	}
	
	function projects_insert_page($arr = array())
	{
    	 $url = $_REQUEST['url'];
		$image_name = process_image(0);
    	$background = process_background(0);
		
    	$video = process_video(0);
		
		$HomepageImage = "";
		if($_FILES['homepage_image']['name'] != "")
		{
			$path = MISC_PATH.DS.DS.'homepage_image'.DS;
			$HomepageImage = $_FILES['homepage_image']['name'];
			$HomepageImage = date("Ymd_his_").$HomepageImage;
			move_uploaded_file($_FILES['homepage_image']['tmp_name'], $path.$HomepageImage);
		}
		
		$side_video_image = "";
		if($_FILES['side_video_image']['name'] != "")
		{
			$path = MISC_PATH.DS.DS.'project_video'.DS;
			$side_video_image = $_FILES['side_video_image']['name'];
			$side_video_image = date("Ymd_his_").$side_video_image;
			move_uploaded_file($_FILES['side_video_image']['tmp_name'], $path.$side_video_image);
		}
		
    	$query = "INSERT INTO druprojects (`title`, 
										   `client_name`, 
										   `description`, 
										   `home_description`,  
										   `homepage_url`, 
										   `project_bio_first_name`, 
										   `project_bio_last_name`, 
										   `project_bio_thumbnail`, 
										   `project_category`, 
										   `project_background`, 
										   `project_url`, 
										   `order`, 
										   `homepage_order`,  
										   `homepage_image`, 
										   `video`, 
										   `side_video_image`,`redirect_url`)
                			  VALUES('".projects_get($arr, 'title' , '' , REAL_ESCAPE)."',
                        			 '".projects_get($arr, 'client_name' , '' , REAL_ESCAPE)."',
                            		 '".projects_get($arr, 'description' , '' , REAL_ESCAPE)."',
                            		 '".projects_get($arr, 'home_description' , '' , REAL_ESCAPE)."',
                            		 '".projects_get($arr, 'homepage_url' , '' , REAL_ESCAPE)."',
                                	 '".projects_get($arr, 'project_bio_first_name' , '' , REAL_ESCAPE)."',
                                     '".projects_get($arr, 'project_bio_last_name' , '' , REAL_ESCAPE)."',
                                  	 '".$image_name."' , '".projects_get($arr, 'project_category' , '' , REAL_ESCAPE)."', 
									 '".$background."', 
									 '".projects_get($arr, 'project_url' , '' , REAL_ESCAPE)."', 
									 '".projects_get($arr, 'order' , '' , REAL_ESCAPE)."', 
									 '".projects_get($arr, 'homepage_order' , '' , REAL_ESCAPE)."', 
									 '".$HomepageImage."', 
									 '".$video."', 
									 '".$side_video_image."', 
									 '".$url."')";
    	db_query($query);
    	return db_last_insert_id('druprojects', 'id');
	}
	
	function isVideo($filename='')
	{
    	$allowedExtensions = array("flv");
		
    	if (!in_array( end( explode(".",strtolower($filename)) ),$allowedExtensions ))
    	{
        	return false;
    	}
		
    	return true;
	}
	
	function process_video($projectId)
	{
    	$oldFilename = $_FILES['video']['name'];
		
    	if($oldFilename && isVideo($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = "SELECT `video` FROM druprojects d WHERE `id` = ".$projectId;
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'project_video'.DS;
    	if($filename)
    	{
        	if(move_uploaded_file($_FILES['video']['tmp_name'],$path.$filename ))
        	{
            	unlink($path.$row['video']);
            	return $filename;
        	}
    	}
		
    	return $row['video'];
	}
	
	function process_background($projectId)
	{
    	$oldFilename = $_FILES['project_background']['name'];
		
    	if($oldFilename && isImage($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = "SELECT `project_background` FROM druprojects d WHERE `id` = $projectId";
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'project_background'.DS;
    	if($filename)
    	{
        	if(move_uploaded_file($_FILES['project_background']['tmp_name'],$path.$filename ))
        	{
            	unlink($path.$row['project_background']);
            	return $filename;
        	}
    	}
		
    	return $row['project_background'];
	}
	
	function projects_store_serviceperformed($projectId , $arr)
	{
    	$servicePerformedId = projects_get($arr,'serviceperformedId' , 0 ,REAL_ESCAPE);
		
    	//$heading1 = projects_get($arr,'heading1' , '' ,REAL_ESCAPE);
    	$description1 = projects_get($arr,'description1' , '' ,REAL_ESCAPE);
		
    	//$heading2 = projects_get($arr,'heading2' , '' ,REAL_ESCAPE);
    	$description2 = projects_get($arr,'description2' , '' ,REAL_ESCAPE);
		
    	//$heading3 = projects_get($arr,'heading3' , '' ,REAL_ESCAPE);
    	$description3 = projects_get($arr,'description3' , '' ,REAL_ESCAPE);
		
    	if($servicePerformedId)
    	{
        	$query = "UPDATE druproject_serviceperformed SET `project_id` = '$projectId',
                            								 `description1` = '$description1',
                            								 `description2` = '$description2',
                            								 `description3` = '$description3'
                      WHERE id = ".$servicePerformedId;
    	}
    	else
    	{
        	$query = "INSERT INTO  druproject_serviceperformed (`project_id`, 
																 
																`description1`, 
																`description2`,
																`description3`)
                    			   VALUES('".$projectId."',  
										  '".$description1."',  
										  '".$description2."',
										  '".$description3."') ";
    	}
    	db_query($query);    
    	return true;
	}
	
	function process_image($projectId)
	{
    	$oldFilename = $_FILES['project_bio_thumbnail']['name'];
		
    	if($oldFilename && isImage($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = "SELECT `project_bio_thumbnail` FROM druprojects d WHERE `id` = ".$projectId;
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'project_bio_thumbnail'.DS;
    	if($filename)
    	{
        	if(move_uploaded_file($_FILES['project_bio_thumbnail']['tmp_name'],$path.$filename ))
        	{
            	unlink($path.$row['project_bio_thumbnail']);
            	return $filename;
        	}
    	}
		
    	return $row['project_bio_thumbnail'];
	}
	
	function projects_validate($arr)
	{
    	$str = projects_get($_REQUEST, 'title' , '',REAL_ESCAPE);
		
    	$str = trim($str);
		
    	if(!empty($str))
    	{
        	return true;
    	}
    	else
    	{
        	Error::$error = "Please enter title";
    	}
		
    	return false;
	}
	
    function projects_store_photo_gallery($id , $arr)
    {
    	$no = $_REQUEST['uploadNo'];
		
    	require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'imageprocess.php';
		
    	//$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\gallery\\';
    	$path = MISC_PATH.DS.'project_gallery'.DS;
        $videoPath = MISC_PATH.DS.'project_video'.DS;
		
    	for($i = 0 ; $i < $no ; $i++)
    	{
            $oldFile_name = $_FILES['photo_gallery'.$i]['name'];
            if($oldFile_name && isImage($oldFile_name))
                $filename = date("Ymd_his_").$oldFile_name;
            else
                $filename = '';
            if($filename)
            {
                if(file_exists($path.$filename))
                {
                    unlink($path.$filename);
                }

                if(move_uploaded_file($_FILES['photo_gallery'.$i]['tmp_name'],$path.$filename ))
                {
                    $imagename = $filename;
                }
                else
                {
                    $imagename = '';
                }

                //check for video
                $oldVideo = $_FILES['photo_gallery_video_file'.$i]['name'];
                if($oldVideo && isImage($oldVideo , array("flv")))
				{
                    $filename = date("Ymd_his_").$oldVideo;
				}
                else
                    $filename = '';

                $videoFile = '';
                if($filename != "")
                {
                    if(file_exists($videoPath.$filename))
                    {
                        unlink($videoPath.$filename);
                    }
		if(move_uploaded_file($_FILES['photo_gallery_video_file'.$i]['tmp_name'],$videoPath.$filename ))
                    {
                        $videoFile = $filename;
                    }
                    else
                    {
                        $videoFile = '';
                    }
                }

                $query = "INSERT INTO druprojects_images (`project_id`, `image` , `video`) VALUES ('".$id."' , '".$imagename."','".$videoFile."')";                
				db_query($query);

            }
    	}
        
    }
	
	function projects_delete_images($id)
	{
    	$imageIds = $_REQUEST['deleteimage'];
		
    	$query = "SELECT * FROM  druprojects_images  WHERE id IN ( '".implode("','", $imageIds)."' )";
    	$result = db_query($query);
		
    	$path = MISC_PATH.DS.'project_gallery'.DS;
        $videoath = MISC_PATH.DS.'project_video'.DS;
		
    	while ($row = db_fetch_array($result))
    	{
        	if(unlink($path.$row['image']))
        	{
                    @unlink($videoath.$row['video']);
                    $query = " DELETE FROM druprojects_images WHERE id = ".$row['id'];
                    db_query($query);
        	}
    	}
	}
	
	function projects_delete()
	{
		if(isset($_POST['project_id']))
		{
			$query = "DELETE FROM druprojects WHERE id = ".$_POST['project_id'];
		  	if(db_query($query))
		  	{
		  		$query_img = "DELETE FROM  druprojects_images WHERE project_id = ".$_POST['project_id'];
		   		$query_service = "DELETE FROM  druproject_serviceperformed WHERE project_id = ".$_POST['project_id'];
		   		db_query($query_service);
		   		if(db_query($query_img))
				{
				 	header("Location: index.php?q=projects&msg=successdelete");
				}
		  	}
		}
	}
?>