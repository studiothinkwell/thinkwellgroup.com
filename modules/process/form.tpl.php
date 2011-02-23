<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump();
//var_dump($_SERVER);
//exit;

$id = process_get($_REQUEST , 'process_id' , 0 );

  $query = " SELECT p.`id` , p.`title` , p.`description`,p.`page_background`  FROM druprocess p WHERE p.`id` = '$id' ";
  $result = db_query($query);
  $row = db_fetch_array($result);

  $query = " SELECT p.`process_id`,p.`id`, p.`image`,p.`order`,p.`alttag` FROM druprocess_images p WHERE p.`process_id` = '$id' order by `order` ASC ";
  $result_images = db_query($query);

  if(isset($_REQUEST['msg']))
  {
?>
<div class="error">
    <?php echo $_REQUEST['msg'] ?>
</div>
<?php
  }
?>
<script type="text/javascript" src="editor/ckeditor.js"></script>
<style type="text/css">
.textCounter{
    background: #EEEEEE;
    width: 40px;
    text-align: center;
    float: left;
    margin: 0 0 0 5px;
}
.textCounter_text{
    float: left;
}
</style>
<script language="javascript" type="text/javascript">
    var upload_number = 1;
    function addFileInput()
    {
        var d = document.createElement("div");
		var c = document.createElement("div");
		/*var txt = document.createElement("input");
        txt.setAttribute("type", "text");
        txt.setAttribute("size", "10");
        txt.setAttribute("name", "txtalt"+upload_number);
        d.appendChild(txt);
        document.getElementById("moreUploads").appendChild(d);*/
       
        var file = document.createElement("input");
        file.setAttribute("type", "file");
        file.setAttribute("name", "photo_gallery"+upload_number);
        d.appendChild(file);
        document.getElementById("moreUploads").appendChild(d);
		
        upload_number++;
      //  document.getElementById("moreUploads").style.width="100%";
    }

    function modifyUploadNo()
    {
        var ele = document.getElementById('uploadNo');
        ele.value = upload_number;
    }

    var maxChar = <?php echo CHAR_MAX_LENGTH ?>;
    function checkLength(obj , counterId)
    {
        var countEle = document.getElementById(counterId);
        if(obj.value.length > maxChar )
        {
            obj.value = obj.value.substr(0,maxChar-1);
        }

        var count = maxChar - obj.value.length;
        if(count < 0)
            count = 0;
        countEle.innerHTML = count;
    }

</script>
<script type="text/javascript">
function deletedata()
{
	if(confirm('Do you want to delete this record?'))
	{
		e = document.getElementById('drupal_form');
		e.action = "index.php?q=process/delete";
		e.submit();
	}
}
</script>


<form name="drupal_form" action="index.php?q=process/save" method="post" id="drupal_form" enctype="multipart/form-data" onsubmit="modifyUploadNo()">
    <table>
        <tr>
            <td valign="top">
                <label for="title">Title</label>
            </td>
            <td>
                <input id="title" type="text" style="width:75%;border: 1px solid #7f9db9" name="title" value="<?php echo $row['title'] ?>" maxlength="64" />
            </td>
        </tr>

        <tr>
            <td valign="top"><label for="description">Description</label></td>
            <td>
                <textarea onkeyup="checkLength(this , 'textCounter')" rows="10" cols="60" style="width:75%;" id="description" name="description" ><?php echo $row['description'] ?></textarea>

                <div>
                    <div class="textCounter_text">
                        Number of characters remaining.
                    </div>
                    <div class="textCounter">
                    <span id="textCounter">
                        <script type="text/javascript">
                            var c = maxChar - <?php echo strlen($row['description']) ?>;
                            if(c < 0)
                                c = 0;
                            document.write(c);
                        </script>
                    </span>
                    </div>
                </div>
            </td>
        </tr>
		
		  <tr>
           <td valign="top"><label for="page_background">Page Background</label></td>
            <td>
                <input type="file" name="page_background" id="page_background" value="<?php echo $row['page_background']?>" />
            </td>
        </tr>
		 <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/process_background/<?php echo $row['page_background'] ?>" /></td>
        </tr>
        <tr>
            <td valign="top"><label for="">Media Gallery</label></td>
            <td>
                <table id="photogallery">
                    <tbody id="photogallery_table">
                        <tr>
                            <td>
                        <?php
                            $i = 1;
                            while( $images = db_fetch_array($result_images) )
                            {
                        ?>
                                
                                        <!--<input type="file" name="photo_gallery<?php //echo $i ?>" value="<?php //echo $images['image'] ?>" />-->
                                        <div style="float:left; padding: 5px;"><img alt="<?php echo $images['alttag'] ?>" src="misc/process_gallery/<?php echo $images['image'] ?>"  height="100"  width="100" />
										                                            <div>
                                                <input type="checkbox" name="deleteimage[]" value="<?php echo $images['id'] ?>" />
                                                <input type="text" style="border: 1px solid #7f9db9" name="reorder[<?php echo $images['id'] ?>][]" size="10" value="<?php echo $images['order'] ?>" />
                                                <input type="hidden" name="imageOrderid[]" value="<?php echo $images['id'] ?>"
                                            </div>
  <b>Alt: </b><input type="text" style="border: 1px solid #7f9db9" name="alt[]" size="10" value="<?php echo $images['alttag'] ?>" />
										
										</div>
										
                                        <?php
                                            //echo $images['image'];
                                        ?>

                        <?php
                            $i++;
                            }
                        ?>
                            </td>
                        </tr>
                        <!--<script type="text/javascript">
                            upload_number = <?php //echo $i ?>;
                        </script>-->
                        <div id="moreUploads">
                            
                        </div>
                        <div id="moreUploadsLink" style="display:block;">
                            <a href="javascript:addFileInput();">Attach Another File</a>
                        </div>
                        <input type="hidden" name="uploadNo" id="uploadNo" value="<?php echo $i ?>" />
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td></td>
            <td><input type="submit" name="save" value="Save" />&nbsp;
            <input type="button" name="delete" value="Delete" onclick="javascript: deletedata()" />
            </td>

        </tr>
    </table>
    
    <input type="hidden" name="process_id" value="<?php echo $row['id'] ?>" />
    
</form>
