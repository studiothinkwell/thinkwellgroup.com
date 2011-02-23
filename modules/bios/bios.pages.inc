<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function bios_page()
{
    ob_start();
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'form.tpl.php';
    $contents = ob_get_contents();
    ob_clean();

    return $contents;
}
function bios_delete()
{

	
	if(isset($_POST['bios_id']))
	{
		$query = "DELETE FROM drubios WHERE id = ".$_POST['bios_id'];
		print_r($query );
		  if(db_query($query))
		  {
		  print_r($query );
		  	$query_img = "DELETE FROM drubios_images WHERE project_id = ".$_POST['bios_id'];
		  	if(db_query($query_img))
			{
				 header("Location: index.php?q=bioslist&process_id=$id&msg=successdelete");
			}
		  }		  
	}
}

?>
