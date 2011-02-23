<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Error
{
    static $error = '';
}

function isImage($filename = '', $includepdf=0)
{
    $allowedExtensions = array("jpg","jpeg","gif","png","bmp");

    if($includepdf)
        $allowedExtensions[] = "pdf";

    if (!in_array( end( explode(".",strtolower($filename)) ),$allowedExtensions ))
    {
        return false;
    }

    return true;
}


function checkvedio($filename = '',$moreExt = array())
{
	$allowedExtensions = array("jpg","jpeg","gif","png","bmp");
	$allowedExtensions = array_merge($allowedExtensions , $moreExt);

	if (!in_array( end( explode(".",strtolower($filename)) ),$allowedExtensions ))
	{
		return false;
	}

	return true;
}

function news_save(){
    $id = news_get($_REQUEST , 'news_id' , 0 );

    $b = news_validate($_REQUEST);

    /*var_dump($_REQUEST);
    exit;*/

    if(!news_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=news/new&msg=".$msg );
        exit;
    }

    if($id){
        news_update_page($id , $_REQUEST);
    }
    else{
        $id = news_insert_page($_REQUEST );
    }

	news_store_photo_gallery($id, $_REQUEST  );
	//news_store_serviceperformed($id , $_REQUEST  );
	news_store_news_category($id , $_REQUEST);
	//news_store_home_page($id , $_REQUEST);
	news_delete_images($id);
	news_save_order($id);
	news_save_alttag($id);
	news_delete_thumbimages($id);
	news_delete_pdf( $id);
    header("Location: index.php?q=news&news_id=$id&msg=success");
}
function news_save_alttag($id)
{
     $imageAlt = $_REQUEST['alt'];
      $imageIds = $_REQUEST['imageOrderid'];

     for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update drunews_images set alttag='".$imageAlt[$i]."' WHERE id = ".$imageIds[$i];
            db_query($query);

     }
}
function news_delete_thumbimages( $id)
{
    $imageIds = $_REQUEST['deleteimage_thumb'];

    $query = "SELECT * FROM drunews WHERE news_thumbnail ='". $imageIds."' ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'news_thumbnail'.DS;

    while ($row = db_fetch_array($result))
    {
        //echo $path.$row['project_bio_thumbnail'];

        if(unlink($path.$row['news_thumbnail']))
        {
            $query = " Update drunews set news_thumbnail='' WHERE id = ".$row['id'];
            db_query($query);

        }
        //exit;
    }
   
}
function news_delete_pdf( $id)
{
    $pdfIds = $_REQUEST['delete_pdf'];

    $query = "SELECT * FROM drunews WHERE news_pdf ='". $pdfIds."' ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'news_pdf'.DS;

    while ($row = db_fetch_array($result))
    {
        //echo $path.$row['project_bio_thumbnail'];

        if(unlink($path.$row['news_pdf']))
        {
            $query = " Update drunews set news_pdf='' WHERE id = ".$row['id'];
            db_query($query);

        }
        //exit;
    }

}
function news_save_order($id)
{
     $imageOrder = $_REQUEST['reorder'];
      $imageIds = $_REQUEST['imageOrderid'];

      //var_dump($imageOrder);

      asort($imageOrder);
      //echo "<br/>";

      //var_dump($imageOrder);

      $i = 0;
      foreach ($imageOrder as $key => $value){
        $query = " Update drunews_images  set `order`= '".$i."' WHERE id = ".$key;
        db_query($query);
        $i++;
      }


     /*for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update drubios_images set `order`=".$imageOrder[$i]." WHERE id = ".$imageIds[$i];
            db_query($query);

     }*/
}
function news_store_home_page($projectId , $arr = array()){

    if(news_get($arr , 'home' , '')){
        $query = " SELECT project_id FROM druprojecthomepage d ";
        $result = db_query($query);
        $homePage = db_fetch_array($result);

        if($homePage['project_id']){
            $query = " UPDATE druprojecthomepage SET project_id = '$projectId' ";
        }
        else{
            $query = " INSERT INTO druprojecthomepage ( `project_id` ) VALUES ( '$projectId' ) ";
        }
        db_query($query);
    }
}

function news_update_page($id , $arr = array())
{
    $background = check_background($id);
    $image_name = process_image($id);
    $pdf = process_pdf($id);
	$url = $_REQUEST[url] ;
	
   // $video = process_video($id);
    $news_type = implode(",", news_get($arr, 'news_type' , '' , REAL_ESCAPE));

    $description = news_get($arr, 'news_description' , '' , REAL_ESCAPE);
    //$description = substr($description, 0 , CHAR_MAX_LENGTH);

   /* if($_FILES['side_video_image']['name'] != "")
    {
            $path = MISC_PATH.DS.DS.'news_video'.DS;
            $side_video_image = $_FILES['side_video_image']['name'];
            $side_video_image = date("Ymd_his_").$side_video_image;
            move_uploaded_file($_FILES['side_video_image']['tmp_name'], $path.$side_video_image);
    }
    else
    {
            $query = "SELECT side_video_image FROM drunews WHERE id = ".$id;
            $results = db_query($query);
            $row = db_fetch_object($results);
            $side_video_image = $row->side_video_image;
    }*/

    $query = " UPDATE drunews SET `news_date` = '".news_get($arr, 'news_date' , '' , REAL_ESCAPE)."' ,
                `news_type` = '".$news_type."',
                `news_heading` = '".news_get($arr, 'news_heading' , '' , REAL_ESCAPE)."',
				`media_contact` = '".news_get($arr, 'media_contact' , '' , REAL_ESCAPE)."',
				`media_email` = '".news_get($arr, 'media_email' , '' , REAL_ESCAPE)."',
                `news_description` = '".$description."',
                `news_thumbnail` = '".$image_name."',
				`redirect_url` = '".$url."',
                `news_pdf` = '".$pdf."',`page_background` = '".$background."'
                WHERE id = '$id' ";

    //echo $query;
    //exit;

    db_query($query);
    

    return $id;
}

function check_background($processId)
	{
    	$oldFilename = $_FILES['page_background']['name'];
		
    	if($oldFilename && checkvedio($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = "SELECT `page_background` FROM drunews d WHERE `id` = $processId";
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'news_background'.DS;
    	if($filename)
    	{
        	if(move_uploaded_file($_FILES['page_background']['tmp_name'],$path.$filename ))
        	{
            	unlink($path.$row['page_background']);
            	return $filename;
        	}
    	}
		
    	return $row['page_background'];
	}
	

function process_pdf($projectId = 0){
    $oldFilename = $_FILES['news_pdf']['name'];

    if($oldFilename && isImage($oldFilename,1))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `news_pdf` FROM drunews d WHERE `id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'news_pdf'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['news_pdf']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['news_pdf'];
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

    $query = "SELECT `video` FROM drunews d WHERE `id` = ".$projectId;
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
function news_insert_page($arr = array())
{
	$background = check_background(0);
    $image_name = process_image(0);
    $pdf = process_pdf(0);
	$url = $_REQUEST[url] ;
    //$video = process_video(0);

   /* $side_video_image = "";
    if($_FILES['side_video_image']['name'] != "")
    {
            $path = MISC_PATH.DS.DS.'news_video'.DS;
            $side_video_image = $_FILES['side_video_image']['name'];
            $side_video_image = date("Ymd_his_").$side_video_image;
            move_uploaded_file($_FILES['side_video_image']['tmp_name'], $path.$side_video_image);
    }*/

    $description = news_get($arr, 'news_description' , '' , REAL_ESCAPE);
     $news_type = implode(",", news_get($arr, 'news_type' , '' , REAL_ESCAPE));
   $query = " INSERT INTO drunews  ( `news_date` , `news_type` , `news_heading` , `media_contact`, `media_email`, `news_description` , `news_thumbnail` , `news_pdf`,`page_background`,`redirect_url`)
                VALUES ( '".news_get($arr, 'news_date' , '' , REAL_ESCAPE)."' ,
                        '".$news_type."' ,
                            '".news_get($arr, 'news_heading' , '' , REAL_ESCAPE)."' ,
                            '".news_get($arr, 'media_contact' , '' , REAL_ESCAPE)."' ,
                            '".news_get($arr, 'media_email' , '' , REAL_ESCAPE)."' ,
                                '".$description."' ,
                                    '".$image_name."' ,
                                  '".$pdf."','".$background."','".$url."') ";
    db_query($query);
    return db_last_insert_id('drunews' , 'id' );
}

function process_background($projectId){
    $oldFilename = $_FILES['project_background']['name'];

    if($oldFilename && isImage($oldFilename))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `project_background` FROM drunews d WHERE `id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'project_background'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['project_background']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['project_background'];
}

function news_store_serviceperformed($projectId , $arr)
{
    $servicePerformedId = news_get($arr,'serviceperformedId' , 0 ,REAL_ESCAPE);

    $heading1 = news_get($arr,'heading1' , '' ,REAL_ESCAPE);
    $description1 = news_get($arr,'description1' , '' ,REAL_ESCAPE);

    $heading2 = news_get($arr,'heading2' , '' ,REAL_ESCAPE);
    $description2 = news_get($arr,'description2' , '' ,REAL_ESCAPE);

    $heading3 = news_get($arr,'heading3' , '' ,REAL_ESCAPE);
    $description3 = news_get($arr,'description3' , '' ,REAL_ESCAPE);

    if($servicePerformedId)
    {
        $query = " UPDATE  druproject_serviceperformed SET `project_id` = '$projectId' ,
                            `heading1` = '$heading1' ,
                            `description1` = '$description1',
                            `heading2` = '$heading2' ,
                            `description2` = '$description2',
                            `heading3` = '$heading3',
                            `description3` = '$description3'
                            WHERE id = $servicePerformedId ";
    }
    else
    {
        $query = " INSERT INTO  druproject_serviceperformed ( `project_id` , `heading1` , `description1` , `heading2` , `description2` , `heading3` , `description3` )
                    VALUES ( '$projectId', '$heading1','$description1','$heading2','$description2','$heading3','$description3' ) ";
    }

    db_query($query);

    return true;
}

function process_image($projectId){

    $oldFilename = $_FILES['news_thumbnail']['name'];

    if($oldFilename && isImage($oldFilename))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `news_thumbnail` FROM drunews d WHERE `id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'news_thumbnail'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['news_thumbnail']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['news_thumbnail'];
}

function news_validate($arr){

    $str = news_get($_REQUEST, 'news_heading' , '',REAL_ESCAPE);

    $str = trim($str);

    if(!empty($str))
    {
        return true;
    }
    else
    {
        Error::$error = "Please enter news heading";
    }

    return false;

}

function news_delete_images($id)
{
    $imageIds = $_REQUEST['deleteimage'];

    $query = " SELECT * FROM  drunews_images  WHERE id IN ( '".implode("','", $imageIds)."' ) ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'news_gallery'.DS;

    while ($row = db_fetch_array($result))
    {
        if(unlink($path.$row['image']))
        {
            $query = " DELETE FROM drunews_images WHERE id = ".$row['id'];
            db_query($query);
        }
    }
}

function news_store_photo_gallery($id , $arr)
{
    // ?q=news/new&msg=Please%20enter%20news%20heading
	
	$no = $_REQUEST['uploadNo'];

    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'imageprocess.php';

    //$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\gallery\\';
    $path = MISC_PATH.DS.'news_gallery'.DS;
   
    $videoPath = MISC_PATH.DS.'news_video'.DS;
    $news_type = implode(",", news_get($arr, 'news_type' , '' , REAL_ESCAPE));
    
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

            /* 
			if(move_uploaded_file($_FILES['photo_gallery'.$i]['tmp_name'],$path.$filename ))
            {
               
                $query = "INSERT INTO drunews_images ( `news_id` , `image`,`video`)
                VALUES ('$id' , '$filename','$video' ) ";

                db_query($query);

                //make it round
                //Imageprocess::makeImageRound($path , $filename );
            }
			*/
			
            if(move_uploaded_file($_FILES['photo_gallery'.$i]['tmp_name'],$path.$filename ))
            {
                $imagename = $filename;
            }
            else
            {
                $imagename = '';
            }
			
			
            $oldVideo = $_FILES['photo_gallery_video_file'.$i]['name'];

            if($oldVideo && checkvedio($oldVideo , array("flv")))
			{
				//echo "<br>1";
				$filename = date("Ymd_his_").$oldVideo;
			}
			else
			{
				$filename = '';
			}		
			//echo "<br>$filename";

			$videoFile = '';
			if($filename != "")
			{
				if(file_exists($videoPath.$filename))
				{
					unlink($videoPath.$filename);
				}
				if(move_uploaded_file($_FILES['photo_gallery_video_file'.$i]['tmp_name'],$videoPath.$filename ))
				{
					//echo "<br>2";
					$videoFile = $filename;
				}
				else
				{
					$videoFile = '';
				}
			}
			//echo "<br>$videoFile";
			$query = "INSERT INTO drunews_images ( `news_id` , `image`,`video`) VALUES ('".$id."' , '".$imagename."','".$videoFile."')";
			//echo "<br>$query";
			db_query($query);

        }
    }

}
function news_store_news_category($id , $arr)
 {
    $newsIds = $_REQUEST['news_type'];
    $deleteQuery="delete from drunews_category_detail where news_id=".$id;
    db_query($deleteQuery);
    for($i=0;$i<count($newsIds);$i++)
    {
        
        $query = "INSERT INTO drunews_category_detail ( `news_id` , `category_id`)VALUES ('".$id."' , '".$newsIds[$i]."')";
               db_query($query);


    }

 }
?>