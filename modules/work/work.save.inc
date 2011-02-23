<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Error
{
    static $error = '';
}

function work_save()
{
    $id = work_get($_REQUEST , 'work_id' , 0 );

    /*if(!work_validate($_REQUEST))
    {
        $msg = Error::$error;
        header("Location: index.php?q=work/new&msg=".$msg );
    }*/

    if($id){
        work_update_page($id , $_REQUEST);
    }
    else{
        $id = work_insert_page($_REQUEST );
    }

    header("Location: index.php?q=work&work_id=".$id );

}

function work_update_page($id , $arr = array())
{
    $query = " UPDATE druwork SET `description1` = '".work_get($arr, 'description1' , '' , REAL_ESCAPE)."' WHERE id = '$id' ";
    db_query($query);

    return $id;
}

function work_insert_page($arr = array())
{
    $query = " INSERT INTO druwork  (`description1`)
                VALUES ( '".work_get($arr, 'description1' , '' , REAL_ESCAPE)."')";
    db_query($query);
    return db_last_insert_id('druwork' , 'id' );
}

function work_validate($arr){

    $str = work_get($arr, 'title' , '',REAL_ESCAPE);

    if(!empty ($str))
    {
        return true;
    }
    else
    {
        Error::$error = "Please enter title";
    }

    return false;

}
?>
