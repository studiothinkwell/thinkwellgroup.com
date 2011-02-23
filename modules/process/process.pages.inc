<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function process_page()
{
    ob_start();
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'form.tpl.php';
    $contents = ob_get_contents();
    ob_clean();

    return $contents;
}
function process_delete()
{

	
	if(isset($_POST['process_id']))
	{
		$query = "DELETE FROM druprocess WHERE id = ".$_POST['process_id'];
		print_r($query );
		  if(db_query($query))
		  {
		  print_r($query );
		  	$query_img = "DELETE FROM druprocess_images WHERE process_id = ".$_POST['process_id'];
		  	if(db_query($query_img))
			{
				 header("Location: index.php?q=process&process_id=$id&msg=successdelete");
			}
		  }		  
	}
}
?>
