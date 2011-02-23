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

function bios_save()
{
    $id = bios_get($_REQUEST , 'bios_id' , 0 );

    $b = bios_validate($_REQUEST);
    
    if(!bios_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=bios/new&msg=".$msg );
        exit;
    }

    if($id){
        bios_update_page($id , $_REQUEST);
    }
    else{
        $id = bios_insert_page($_REQUEST );
    }

    bios_store_photo_gallery($id, $_REQUEST  );
	bios_delete_images($id);
	bios_delete_thumbimages($id);
        bios_save_order($id);
        bios_save_alttag($id);
    //bios_store_serviceperformed($id , $_REQUEST  );

    //bios_store_home_page($id , $_REQUEST);

    //header("Location: index.php?q=bioslist&bios_id=$id&msg=success");
	global $base_url;
	header("Location: ".$base_url."/bioslist/bios_id=$id&msg=success");
    exit;
}

function bios_store_home_page($projectId , $arr = array()){

    if(bios_get($arr , 'home' , '')){
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

function bios_update_page($id , $arr = array())
{
    $image_name = process_image($id);
    $background = process_background($id);
	$url = $_REQUEST[url] ;

    $description = bios_get($arr, 'description' , '' , REAL_ESCAPE);
    $description = substr($description, 0 , CHAR_MAX_LENGTH);

    $query = " UPDATE drubios SET `title` = '".bios_get($arr, 'title' , '' , REAL_ESCAPE)."' ,
                `client_name` = '".bios_get($arr, 'client_name' , '' , REAL_ESCAPE)."',
                `description` = '".bios_get($arr, 'description' , '' , REAL_ESCAPE)."',
                `project_bio_first_name` = '".bios_get($arr, 'project_bio_first_name' , '' , REAL_ESCAPE)."',
                `project_bio_last_name` = '".bios_get($arr, 'project_bio_last_name' , '' , REAL_ESCAPE)."',
                `project_bio_thumbnail` = '".$image_name."' ,
                `project_category` = '".bios_get($arr, 'project_category' , '' , REAL_ESCAPE)."',
                `project_background` = '$background',
                `order` = '".bios_get($arr, 'order' , '' , REAL_ESCAPE)."',
				`redirect_url` = '".$url."' , 
                `company_title` = '".bios_get($arr, 'company_title' , '' , REAL_ESCAPE)."'
                WHERE id = '$id' ";
    db_query($query);

    return $id;
}

function bios_insert_page($arr = array())
{
    $image_name = process_image(0);
    $background = process_background(0);
	$url = $_REQUEST[url] ;

    $query = " INSERT INTO drubios  ( `title` , `client_name` , `description` , `project_bio_first_name` , `project_bio_last_name` , `project_bio_thumbnail` , `project_category` , `project_background` , `order` , `company_title` , `redirect_url` )
                VALUES ( '".bios_get($arr, 'title' , '' , REAL_ESCAPE)."' ,
                        '".bios_get($arr, 'client_name' , '' , REAL_ESCAPE)."' ,
                            '".bios_get($arr, 'description' , '' , REAL_ESCAPE)."' ,
                                '".bios_get($arr, 'project_bio_first_name' , '' , REAL_ESCAPE)."' ,
                                    '".bios_get($arr, 'project_bio_last_name' , '' , REAL_ESCAPE)."' ,
                                  '".$image_name."' , '".bios_get($arr, 'project_category' , '' , REAL_ESCAPE)."' , '$background' , '".bios_get($arr, 'order' , '' , REAL_ESCAPE)."' , '".bios_get($arr, 'company_title' , '' , REAL_ESCAPE)."' , '".$url."' )  ";
    db_query($query);
    return db_last_insert_id('drubios' , 'id' );
}

function process_background($projectId){
    $oldFilename = $_FILES['project_background']['name'];

    if($oldFilename && isImage($oldFilename))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `project_background` FROM drubios d WHERE `id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'bios_background'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['project_background']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['project_background'];
}

function bios_store_serviceperformed($projectId , $arr)
{
    $servicePerformedId = bios_get($arr,'serviceperformedId' , 0 ,REAL_ESCAPE);

    $heading1 = bios_get($arr,'heading1' , '' ,REAL_ESCAPE);
    $description1 = bios_get($arr,'description1' , '' ,REAL_ESCAPE);

    $heading2 = bios_get($arr,'heading2' , '' ,REAL_ESCAPE);
    $description2 = bios_get($arr,'description2' , '' ,REAL_ESCAPE);

    $heading3 = bios_get($arr,'heading3' , '' ,REAL_ESCAPE);
    $description3 = bios_get($arr,'description3' , '' ,REAL_ESCAPE);

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

    $query = " SELECT `project_bio_thumbnail` FROM drubios d WHERE `id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'bios_bio_thumbnail'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['project_bio_thumbnail']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['project_bio_thumbnail'];
}

function bios_validate($arr){

    $str = bios_get($_REQUEST, 'title' , '',REAL_ESCAPE);

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
function bios_save_order($id)
{
     $imageOrder = $_REQUEST['reorder'];
      $imageIds = $_REQUEST['imageOrderid'];

      //var_dump($imageOrder);
      
      asort($imageOrder);
      //echo "<br/>";

      //var_dump($imageOrder);

      $i = 0;
      foreach ($imageOrder as $key => $value){
        echo $query = " Update drubios_images set `order`= '".$i."' WHERE id = ".$key;
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
function bios_save_alttag($id)
{
     $imageAlt = $_REQUEST['alt'];
      $imageIds = $_REQUEST['imageOrderid'];

     for($i=0;$i<=count($imageIds);$i++)
     {
           $query = " Update drubios_images set alttag='".$imageAlt[$i]."' WHERE id = ".$imageIds[$i];
            db_query($query);

     }
}
function bios_delete_images($id)
{
    $imageIds = $_REQUEST['deleteimage'];

    $query = " SELECT * FROM  drubios_images  WHERE id IN ( '".implode("','", $imageIds)."' ) ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'bios_gallery'.DS;

    while ($row = db_fetch_array($result))
    {
        if(unlink($path.$row['image']))
        {
            $query = " DELETE FROM drubios_images WHERE id = ".$row['id'];
            db_query($query);
        }
    }
}
function bios_delete_thumbimages( $id)
{
    $imageIds = $_REQUEST['deleteimage_thumb'];

    $query = "SELECT * FROM drubios WHERE project_bio_thumbnail ='". $imageIds."' ";
    $result = db_query($query);

    $path = MISC_PATH.DS.'bios_bio_thumbnail'.DS;

    while ($row = db_fetch_array($result))
    {
        //echo $path.$row['project_bio_thumbnail'];

        if(unlink($path.$row['project_bio_thumbnail']))
        {
            $query = " Update drubios set project_bio_thumbnail='' WHERE id = ".$row['id'];
            db_query($query);
        
        }
        //exit;
    }
}
function bios_store_photo_gallery($id , $arr)
{
    $no = $_REQUEST['uploadNo'];

    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'imageprocess.php';

    //$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\gallery\\';
    $path = MISC_PATH.DS.'bios_gallery'.DS;

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
                $query = "INSERT INTO drubios_images ( `project_id` , `image`  )
                VALUES ('$id' , '$filename' ) ";

                db_query($query);

                //make it round
                //Imageprocess::makeImageRound($path , $filename );
            }
        }
    }

}
?>
