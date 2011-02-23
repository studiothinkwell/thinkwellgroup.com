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

function project_category_save(){
    $id = project_category_get($_REQUEST , 'project_category_id' , 0 );

    $b = project_category_validate($_REQUEST);

    if(!project_category_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=project_category/new&msg=".$msg );
        exit;
    }

    if($id){
        project_category_update_page($id , $_REQUEST);
    }
    else{
        $id = project_category_insert_page($_REQUEST );
    }

    header("Location: index.php?q=project_category&project_category_id=$id&msg=success");
    exit;
}

function project_category_update_page($id , $arr = array())
{
    //$image_name = process_background($id);

    //$description = substr($description, 0 , CHAR_MAX_LENGTH);

    $query = " UPDATE druprojectcategory SET `category_name` = '".project_category_get($arr, 'category_name' , '' , REAL_ESCAPE)."' 
                WHERE category_id = '$id' ";
    db_query($query);
    
    return $id;
}

function project_category_insert_page($arr = array())
{
    //$image_name = process_background(0);
    
    $query = " INSERT INTO druprojectcategory ( `category_name`  )
                VALUES ( '".project_category_get($arr, 'category_name' , '' , REAL_ESCAPE)."' )  ";
    db_query($query);
    return db_last_insert_id('druprojectcategory' , 'category_id' );
}

function process_background($projectId){
    
    $oldFilename = $_FILES['category_background']['name'];

    if($oldFilename && isImage($oldFilename))
        $filename = date("Ymd_his_").$oldFilename;
    else
        $filename = '';

    $query = " SELECT `category_background` FROM druprojectcategory d WHERE `category_id` = $projectId ";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $path = MISC_PATH.DS.DS.'project_category_background'.DS;
    if($filename)
    {
        if(move_uploaded_file($_FILES['category_background']['tmp_name'],$path.$filename ))
        {
            return $filename;
        }
    }

    return $row['category_background'];
}

function project_category_validate($arr){

    $str = project_category_get($_REQUEST, 'category_name' , '',REAL_ESCAPE);

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