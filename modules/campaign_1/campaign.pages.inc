<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('REAL_ESCAPE' , 1);
define('DS' , DIRECTORY_SEPARATOR);
define('MISC_PATH' , '/var/www/html/thinkwell/misc' );

class Error
{
    static $error = array();
}

function campaign_get($arr , $index , $default = '' , $real_escape = 0)
{
    if(isset($arr[$index]))
    {
        if($real_escape)
        {
            if(!is_array($arr[$index]))
            return db_escape_string($arr[$index]);
        }
        return $arr[$index];
    }

    return $default;
}

function campaign_getBanner($campaign_id = 0 , $pos = '' )
{
    $query = " SELECT `id` , `image_name` , `is_enable` , `title` , `description`  FROM drubanner d WHERE campaign_id = '$campaign_id' AND `position` =  '$pos'  ";
    $result = db_query($query);
    $row = db_fetch_array($result);

    return $row;
}

function campaign_page() {

    //return "<p>asdasd</p>";    
    ob_start();
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'form.tpl.php';
    $contents = ob_get_contents();
    ob_clean();
  
    return $contents;
}

function campaign_save()
{
    $id = campaign_get($_REQUEST , 'campaign_id' , 0 );

    $cont = campaign_get($arr, 'content_field' , '' , REAL_ESCAPE);

    if(!campaign_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=campaign/new&msg=".$msg );
        //redirect
    }
    //here $id is main campaign id
    if($id)    {
        //update
        campaign_update_campaign($id , $_REQUEST);
    }
    else {
        //insert
        $id = campaign_insert_campaign($_REQUEST );
    }

    //do else part
    campaign_store_photo_gallery($id ,$_REQUEST  );
    campaign_store_contents($id , $_REQUEST );
    campaign_store_banners($id , $_REQUEST );

    header("Location: index.php?q=campaign&msg=success" );

    //var_dump($id);
}

function campaign_store_photo_gallery($id , $arr)
{
    $no = $_REQUEST['uploadNo'];

    //$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\gallery\\';
    $path = MISC_PATH.DS.'gallery'.DS;

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
                $query = "INSERT INTO drujobs ( `campaign_id` , `image`  )
                VALUES ('$id' , '$filename' ) ";

                db_query($query);
            }
        }
    }
    
}

function campaign_store_contents($id , $arr = array())
{
    $query = "SELECT c.`campaign_id` FROM campaign_content c  WHERE c.campaign_id = '$id' ";
    $result = db_query($query);
    $row = db_fetch_array($result);

    $pattern = '/action="([^"]*)/';
    $replacement = 'action="';
    $content = campaign_get($arr, 'content_field' , '' , REAL_ESCAPE);
    $content = preg_replace($pattern, $replacement, $content);

    if($row['campaign_id']){
        //update
        $query = " UPDATE campaign_content SET  content_field = '".$content."'  WHERE campaign_id = '$id' ";
    }
    else{
        //insert
        $content = campaign_get($arr, 'content_field' , '' , REAL_ESCAPE);
        $query = " INSERT INTO campaign_content ( `campaign_id`,`content_field` ) VALUES ( '".db_escape_string($id)."','".$content."' ) ";
    }

    return db_query($query);
}

function campaign_store_banners($id , $arr = array())
{
    campaign_store_banner($id , $arr['banner1'] , 'banner1');
    campaign_store_banner($id , $arr['banner2'], 'banner2');
    campaign_store_banner($id , $arr['banner3'] , 'banner3');
}

function campaign_process_banner_image($bannerId , $bannerIndex)
{
    if(!$bannerId )
        $bannerId = 0;
    
    $oldFile_name = $_FILES[$bannerIndex]['name']['photo'];

    if($oldFile_name)
        $fileName = date("Ymd_his_").$oldFile_name;
    else
        $fileName = '';

    $query = " SELECT image_name FROM drubanner WHERE id =  $bannerId  ";
    $result = db_query($query);
    $row = db_fetch_array($result);

    //$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\banner\\';
    $path = MISC_PATH.DS.'banner'.DS;

    if($row['image_name'])
    {
        define('DS' , DIRECTORY_SEPARATOR);
        //$path = dirname(__FILE__);
        
        if(file_exists($path.$row['image_name']) && $fileName )
        {
            unlink($path.$row['image_name']);
        }
        else{
            return $row['image_name'];
        }
        
    }
    

    if ($_FILES[$bannerIndex]["error"]['photo'] > 0)
    {
        //echo "Error: " . $_FILES["file"]["error"] . "<br />";        
    }
    else if($fileName)
    {   
        $unlink = true;
        if(file_exists($path.$fileName))
        {
            $unlink = unlink($path.$fileName);
        }
        
        $uploaded = false;
        //if($unlink)
        {
            if(move_uploaded_file($_FILES[$bannerIndex]["tmp_name"]['photo'],$path.$fileName ))
                return $fileName;
        }

    }

    return '';
}

function campaign_store_banner($id , $banner = array() , $bannerIndex = '' )
{
    $date = date('Y-m-d', time());
    $bannerId = campaign_get($banner, 'banner_id' , '' , REAL_ESCAPE);

    $imageName = campaign_process_banner_image($bannerId , $bannerIndex);

    if($bannerId)
    {
        $query = " UPDATE drubanner SET `campaign_id` = '$id' ,
                                        `position` = '".campaign_get($banner, 'position')."',
                                        `image_name` = '$imageName',
                                        `is_enable` = 'yes',
                                        `date` = '$date',
                                        `description` = '".campaign_get($banner, 'description' , '' , REAL_ESCAPE)."',
                                        `title` = '".campaign_get($banner, 'title', '' , REAL_ESCAPE)."'
                                         WHERE id = '$bannerId'  ";
    }
    else
    {
        $query = " INSERT INTO  drubanner  (`campaign_id` , `position` , `is_enable` , `image_name` , `date` ,  `title` , `description` )
                    VALUES ( '$id' , '".campaign_get($banner, 'position')."','yes', '$imageName' , '$date', '".campaign_get($banner, 'title' , '' , REAL_ESCAPE)."' , '".campaign_get($banner, 'description' , '' , REAL_ESCAPE)."' ) ";
    }

    return db_query($query);
}

function campaign_update_campaign($id , $arr = array())
{
    $query = " UPDATE campaign SET campaign_title = '".campaign_get($arr, 'campaign_title' , '' , REAL_ESCAPE)."' WHERE id = '$id' ";
    db_query($query);

    return $id;
}

function campaign_insert_campaign($arr = array())
{
    $query = " INSERT INTO campaign ( `campaign_title` ) VALUES ( '".campaign_get($arr, 'campaign_title' , '' , REAL_ESCAPE)."' )";
    db_query($query);

    return db_last_insert_id('campaign' , 'id' );
}

function  campaign_validate($arr)
{
    $str = campaign_get($arr, 'campaign_title' , '',REAL_ESCAPE);
    
    if(!empty ($str))
    {
        return true;
    }
    else
    {
        Error::$error[] = "Please enter title";
    }
    
    return false;
}
?>


