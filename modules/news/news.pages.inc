<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function news_page()
{
    ob_start();
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'form.tpl.php';
    $contents = ob_get_contents();
    ob_clean();

    return $contents;
}
function news_delete()
{
	if(isset($_POST['news_id']))
	{
		$query = "DELETE FROM drunews WHERE id = ".$_POST['news_id'];
		  if(db_query($query))
		  {
		  	$query_img = "DELETE FROM  drunews_images WHERE news_id  = ".$_POST['news_id'];
		   if(db_query($query_img))
			{
			
				 header("Location: index.php?q=news&msg=successdelete");
			}
		  }		  
	}
}
?>
