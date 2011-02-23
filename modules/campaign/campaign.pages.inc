<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('REAL_ESCAPE' , 1);
define('DS' , DIRECTORY_SEPARATOR);
//define('MISC_PATH' , 'D:/phpdev/wamp/www/drupal/drupal/misc' );
global $base_url;
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
    campaign_delete_images($id);
    campaign_store_photo_gallery($id ,$_REQUEST  );
    campaign_store_contents($id , $_REQUEST );
    campaign_store_banners($id , $_REQUEST );
    campaign_save_order($id);
	global $base_url;
    //header("Location: index.php?q=campaign&msg=success" );
	header("Location: ".$base_url."/campaign&msg=success" );

    //var_dump($id);
}

function campaign_save_order($id)
{
     $imageOrder = $_REQUEST['reorder'];
      $imageIds = $_REQUEST['imageOrderid'];

      //var_dump($imageOrder);

      asort($imageOrder);
      //echo "<br/>";

      //var_dump($imageOrder);

      $i = 0;
      foreach ($imageOrder as $key => $value){
         $query = " Update drujobs set `order`= '".$i."' WHERE id = ".$key;
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

function campaign_delete_images($id)
	{
    	$imageIds = $_REQUEST['deleteimage'];

    	$query = "SELECT * FROM  drujobs  WHERE id IN ( '".implode("','", $imageIds)."' )";
    	$result = db_query($query);

    	$path = MISC_PATH.DS.'gallery'.DS;
       

    	while ($row = db_fetch_array($result))
    	{
        	if(unlink($path.$row['image']))
        	{
                    $query = " DELETE FROM drujobs WHERE id = ".$row['id'];
                    db_query($query);
        	}
    	}
	}
function campaign_store_photo_gallery($id , $arr)
{
    $no = $_REQUEST['uploadNo'];

    //$path = 'D:\phpdev\wamp\www\drupal\drupal\misc\gallery\\';
    $path = $base_url.DS.'gallery'.DS;

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
function check_background($processId)
	{
    	$oldFilename = $_FILES['page_background']['name'];
		
    	if($oldFilename && checkvedio($oldFilename))
        	$filename = date("Ymd_his_").$oldFilename;
    	else
        	$filename = '';
		
    	$query = "SELECT `page_background` FROM campaign d WHERE `id` = $processId";
    	$result = db_query($query);
    	$row = db_fetch_array($result);
    	$path = MISC_PATH.DS.DS.'campaign_background'.DS;
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
function campaign_update_campaign($id , $arr = array())
{
	$url = $_REQUEST[url] ;
	 $background = check_background($id);
    $query = " UPDATE campaign SET campaign_title = '".campaign_get($arr, 'campaign_title' , '' , REAL_ESCAPE)."' ,`page_background` = '".$background."', redirect_url = '".$url."' WHERE id = '$id' ";
    db_query($query);

    return $id;
}

function campaign_insert_campaign($arr = array())
{
	$url = $_REQUEST[url] ;
	$background = check_background(0);
    $query = " INSERT INTO campaign ( `campaign_title` , `redirect_url`,`page_background`) VALUES ( '".campaign_get($arr, 'campaign_title' , '' , REAL_ESCAPE)."' , '".$url."','".$background."')";
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
function campaign_delete()
{
	if(isset($_POST['campaign_id']))
	{
		$query = "DELETE FROM campaign WHERE id = ".$_POST['campaign_id'];
		$query_conetent = "DELETE FROM campaign_content WHERE campaign_id = ".$_POST['campaign_id'];
		  if(db_query($query))
		  {
		  db_query($query_conetent);
		  print_r($query );
		  	$query_img = "DELETE FROM  drujobs WHERE campaign_id = ".$_POST['campaign_id'];
		  $query_baner = "DELETE FROM  drubanner WHERE campaign_id = ".$_POST['campaign_id'];
		  db_query($query_baner);
		  if(db_query($query_img))
			{
			
				 header("Location: index.php?q=campaign&msg=successdelete");
			}
		  }		  
	}
}
?>


