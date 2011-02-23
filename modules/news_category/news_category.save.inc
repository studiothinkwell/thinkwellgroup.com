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

function news_category_save(){
    $id = news_category_get($_REQUEST , 'news_category_id' , 0 );

    $b = news_category_validate($_REQUEST);

    if(!news_category_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=news_category/new&msg=".$msg );
        exit;
    }

    if($id){
        news_category_update_page($id , $_REQUEST);
    }
    else{
        $id = news_category_insert_page($_REQUEST );
    }

    header("Location: index.php?q=news_category&news_category_id=$id&msg=success");
}

function news_category_update_page($id , $arr = array())
{
    $image_name = process_background($id);

    //$description = substr($description, 0 , CHAR_MAX_LENGTH);

    $query = " UPDATE drunews_category SET `category_name` = '".news_category_get($arr, 'category_name' , '' , REAL_ESCAPE)."' ,
                `category_background` = '".$image_name."'
                WHERE category_id = '$id' ";
    db_query($query);


    return $id;
}

function news_category_insert_page($arr = array())
{
    $image_name = process_background(0);
    
    $query = " INSERT INTO drunews_category ( `category_name` , `category_background` )
                VALUES ( '".news_category_get($arr, 'category_name' , '' , REAL_ESCAPE)."' ,
                        '".$image_name."'  )  ";
    db_query($query);
    return db_last_insert_id('drunews_category' , 'category_id' );
}

function process_background($projectId){
    
    $oldFilename = $_FILES['category_background']['name'];

    if($oldFilename && isImage($oldFilename))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `category_background` FROM drunews_category d WHERE `category_id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'news_category_background'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['category_background']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['category_background'];
}

function news_category_validate($arr){

    $str = news_category_get($_REQUEST, 'category_name' , '',REAL_ESCAPE);

    $str = trim($str);

    if(!empty($str))
    {
        return true;
    }
    else
    {
        Error::$error = "Please enter category heading";
    }

    return false;

}

?>