<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Error
{
    static $error = '';
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

function projects_save(){
    $id = projects_get($_REQUEST , 'project_id' , 0 );

    $b = projects_validate($_REQUEST);
    
    if(!projects_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=page/new&msg=".$msg );
        exit;
    }

    if($id){
        projects_update_page($id , $_REQUEST);
    }
    else{
        $id = projects_insert_page($_REQUEST );
    }

    projects_store_photo_gallery($id, $_REQUEST  );
    projects_store_serviceperformed($id , $_REQUEST  );

    projects_store_categories($id , $_REQUEST);

    projects_store_home_page($id , $_REQUEST);

    projects_delete_images($id);

    header("Location: index.php?q=projects&project_id=$id&msg=success");
    exit;
}

function projects_store_categories($projectId , $arr)
{
    $query = " DELETE FROM druproject_category_map WHERE project_id = $projectId ";
    db_query($query);

    $categories = projects_get($arr, 'project_category' , '' , REAL_ESCAPE);

    for($i = 0 ; $i < count($categories) ; $i++)
    {
        $query = " INSERT INTO druproject_category_map ( `project_id` , `category_id` )
                    VALUES ('$projectId','".$categories[$i]."') ";

        db_query($query);
    }

}

function projects_store_home_page($projectId , $arr = array()){

    if(projects_get($arr , 'home' , '') == "yes"){
        $query = " SELECT project_id FROM druprojecthomepage d WHERE project_id = '$projectId' ";
        $result = db_query($query);
        $homePage = db_fetch_array($result);

        if($homePage['project_id']){
            //$query = " UPDATE druprojecthomepage SET project_id = '$projectId' ";
        }
        else{
            $query = " INSERT INTO druprojecthomepage ( `project_id` ) VALUES ( '$projectId' ) ";
        }
        db_query($query);
    }
    else{
        $query = " DELETE FROM druprojecthomepage WHERE  project_id = '$projectId' ";
        db_query($query);
    }

}

function projects_update_page($id , $arr = array())
{
    $image_name = process_image($id);
    $background = process_background($id);

    $video = process_video($id);


    $description = projects_get($arr, 'description' , '' , REAL_ESCAPE);
    //$description = substr($description, 0 , CHAR_MAX_LENGTH);

    $query = " UPDATE druprojects SET `title` = '".projects_get($arr, 'title' , '' , REAL_ESCAPE)."' ,
                `client_name` = '".projects_get($arr, 'client_name' , '' , REAL_ESCAPE)."',
                `description` = '".projects_get($arr, 'description' , '' , REAL_ESCAPE)."',
                `home_description` = '".projects_get($arr, 'home_description' , '' , REAL_ESCAPE)."',
                `project_bio_first_name` = '".projects_get($arr, 'project_bio_first_name' , '' , REAL_ESCAPE)."',
                `project_bio_last_name` = '".projects_get($arr, 'project_bio_last_name' , '' , REAL_ESCAPE)."',
                `project_bio_thumbnail` = '".$image_name."' ,
                `project_category` = '".projects_get($arr, 'project_category' , '' , REAL_ESCAPE)."',
                `project_background` = '$background',
                `project_url` = '".projects_get($arr, 'project_url' , '' , REAL_ESCAPE)."',
                `video` = '".$video."',
                `order` = '".projects_get($arr, 'order' , '' , REAL_ESCAPE)."',
                `homepage_order` = '".projects_get($arr, 'homepage_order' , '' , REAL_ESCAPE)."'
                WHERE id = '$id' ";
    db_query($query);

    return $id;
}

function projects_insert_page($arr = array())
{
    $image_name = process_image(0);
    $background = process_background(0);

    $video = process_video(0);

    $query = " INSERT INTO druprojects  ( `title` , `client_name` , `description` , `home_description` , `project_bio_first_name` , `project_bio_last_name` , `project_bio_thumbnail` , `project_category` , `project_background` , `project_url` , `order`, 'homepage_order', `video` )
                VALUES ( '".projects_get($arr, 'title' , '' , REAL_ESCAPE)."' ,
                        '".projects_get($arr, 'client_name' , '' , REAL_ESCAPE)."' ,
                            '".projects_get($arr, 'description' , '' , REAL_ESCAPE)."' ,
                            '".projects_get($arr, 'home_description' , '' , REAL_ESCAPE)."' ,
                                '".projects_get($arr, 'project_bio_first_name' , '' , REAL_ESCAPE)."' ,
                                    '".projects_get($arr, 'project_bio_last_name' , '' , REAL_ESCAPE)."' ,
                                  '".$image_name."' , '".projects_get($arr, 'project_category' , '' , REAL_ESCAPE)."' , '$background' , '".projects_get($arr, 'project_url' , '' , REAL_ESCAPE)."' , '".projects_get($arr, 'order' , '' , REAL_ESCAPE)."', '".projects_get($arr, 'homepage_order' , '' , REAL_ESCAPE)."','$video' )  ";
    db_query($query);
    return db_last_insert_id('druprojects' , 'id' );
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

    $query = " SELECT `video` FROM druprojects d WHERE `id` = $projectId ";
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

function process_background($projectId){
    $oldFilename = $_FILES['project_background']['name'];

    if($oldFilename && isImage($oldFilename))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `project_background` FROM druprojects d WHERE `id` = $projectId ";
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

    $heading1 = projects_get($arr,'heading1' , '' ,REAL_ESCAPE);
    $description1 = projects_get($arr,'description1' , '' ,REAL_ESCAPE);

    $heading2 = projects_get($arr,'heading2' , '' ,REAL_ESCAPE);
    $description2 = projects_get($arr,'description2' , '' ,REAL_ESCAPE);

    $heading3 = projects_get($arr,'heading3' , '' ,REAL_ESCAPE);
    $description3 = projects_get($arr,'description3' , '' ,REAL_ESCAPE);

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

    $oldFilename = $_FILES['project_bio_thumbnail']['name'];

    if($oldFilename && isImage($oldFilename))
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
            unlink($path.$row['project_bio_thumbnail']);
            return $filename;
        }
    }

    return $row['project_bio_thumbnail'];
}

function projects_validate($arr){

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
                $query = "INSERT INTO druprojects_images ( `project_id` , `image`  )
                VALUES ('$id' , '$filename' ) ";

                db_query($query);

                //make it round
                //Imageprocess::makeImageRound($path , $filename );
            }
        }
    }

}

function projects_delete_images($id)
{
    $imageIds = $_REQUEST['deleteimage'];

    $query = " SELECT * FROM  druprojects_images  WHERE id IN ( '".implode("','", $imageIds)."' ) ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'project_gallery'.DS;

    while ($row = db_fetch_array($result))
    {
        if(unlink($path.$row['image']))
        {
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
