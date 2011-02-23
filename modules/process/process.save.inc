<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Error
{
    static $error = '';
}

function process_save(){
    $id = process_get($_REQUEST , 'process_id' , 0 );

    $b = process_validate($_REQUEST);

    if(!process_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=process/new&msg=".$msg );
        exit;
    }

    if($id){
        process_update_page($id , $_REQUEST);
    }
    else{
        $id = process_insert_page($_REQUEST );
    }

    process_store_photo_gallery($id, $_REQUEST  );
    process_delete_images($id);
    process_save_order($id);
    process_save_alttag($id);
    header("Location: index.php?q=process&process_id=$id&msg=success");
}
function process_save_alttag($id)
{
     $imageAlt = $_REQUEST['alt'];
      $imageIds = $_REQUEST['imageOrderid'];

     for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update druprocess_images set alttag='".$imageAlt[$i]."' WHERE id = ".$imageIds[$i];
            db_query($query);

     }
}
function process_save_order($id)
{
     $imageOrder = $_REQUEST['reorder'];
      $imageIds = $_REQUEST['imageOrderid'];

      //var_dump($imageOrder);

      asort($imageOrder);
      //echo "<br/>";

      //var_dump($imageOrder);

      $i = 0;
      foreach ($imageOrder as $key => $value){
        $query = " Update druprocess_images set `order`= '".$i."' WHERE id = ".$key;
        db_query($query);
        $i++;
      }


     /*for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update drubios_images set `order`=".$imageOrder[$i]." WHERE id = ".$imageIds[$i];
            db_query($query);

     }*/
}
function process_delete_images($id)
{
     $imageIds = $_REQUEST['deleteimage'];

    $query = " SELECT * FROM  druprocess_images  WHERE id IN ( '".implode("','", $imageIds)."' ) ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'process_gallery'.DS;

    while ($row = db_fetch_array($result))
    {
        if(unlink($path.$row['image']))
        {
            $query = " DELETE FROM druprocess_images WHERE id = ".$row['id'];
            db_query($query);
        }
    }
}
function process_update_page($id , $arr = array())
{
    $background = check_background($id);
	$description = process_get($arr, 'description' , '' , REAL_ESCAPE);
    $description = substr($description, 0 , CHAR_MAX_LENGTH);

    $query = " UPDATE druprocess SET `title` = '".process_get($arr, 'title' , '' , REAL_ESCAPE)."' ,
                `description` = '".$description ."', `page_background` = '".$background."'
                WHERE id = '$id' ";
    db_query($query);

    return $id;
}

function process_insert_page($arr = array())
{
	$background = check_background(0);
    $query = " INSERT INTO druprocess ( `title` , `description`,`page_background` )
                VALUES ( '".process_get($arr, 'title' , '' , REAL_ESCAPE)."' ,
                            '".process_get($arr, 'description' , '' , REAL_ESCAPE)."','".$background."'   )  ";
    db_query($query);
    return db_last_insert_id('druprocess' , 'id' );
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
function check_background($processId)
	{
    	$oldFilename = $_FILES['page_background']['name'];
		
    	if($oldFilename && isImage($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = "SELECT `page_background` FROM druprocess d WHERE `id` = $processId";
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'process_background'.DS;
    	if($filename)
    	{
        	if(move_uploaded_file($_FILES['page_background']['tmp_name'],$path.$filename ))
        	{
            	unlink($path.$row['process_background']);
            	return $filename;
        	}
    	}
		
    	return $row['process_background'];
	}
	
	

function process_image($projectId){

   /* $oldFilename = $_FILES['project_bio_thumbnail']['name'];

    if($oldFilename)
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `project_bio_thumbnail` FROM druprojects d WHERE `id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'project_bio_thumbnail'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['project_bio_thumbnail']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['project_bio_thumbnail'];*/
}

function process_validate($arr){

    $str = process_get($_REQUEST, 'title' , '',REAL_ESCAPE);

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

function process_store_photo_gallery($id , $arr)
{
    $no = $_REQUEST['uploadNo'];

    //$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\gallery\\';
    $path = MISC_PATH.DS.'process_gallery'.DS;

    for($i = 0 ; $i < $no ; $i++)
    {
        $oldFile_name = $_FILES['photo_gallery'.$i]['name'];

        if($oldFile_name)
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
                $query = "INSERT INTO druprocess_images ( `process_id` , `image`  )
                VALUES ('$id' , '$filename' ) ";

                db_query($query);
            }
        }
    }

}
?>
