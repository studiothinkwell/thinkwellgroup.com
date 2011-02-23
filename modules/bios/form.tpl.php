<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump();
//var_dump($_SERVER);
//exit;
global $base_url;
$id = bios_get($_REQUEST , 'bios_id' , 0 );

  $query = " SELECT p.`id`, p.`title`, p.`client_name`, p.`description` , p.`project_bio_first_name` , p.`project_bio_last_name` , p.`project_bio_thumbnail` , p.`project_category` , p.`project_background` , p.`order` , p.`company_title` , p.`redirect_url`
                FROM drubios p WHERE p.`id` = '$id' ";
  $result = db_query($query);
  $row = db_fetch_array($result);

  $query = " SELECT id,image,`order`,alttag FROM drubios_images WHERE project_id = '$id' order by `order` ASC";
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
        var file = document.createElement("input");
        file.setAttribute("type", "file");
        file.setAttribute("name", "photo_gallery"+upload_number);
        d.appendChild(file);
        document.getElementById("moreUploads").appendChild(d);
        upload_number++;
            
    }

    var servicePerformedNumber = 2;
    function addServiceperformedInput()
    {
        if(servicePerformedNumber < 4)
        {
            var serviceperformed = document.getElementById("serviceperformed_body");

            var linkRow = document.createElement('tr');
            var linkTd = document.createElement('td');
            linkTd.colSpan = 2;
            linkTd.align = "center";
            linkTd.innerHTML = "<div class='heading' >Link "+(servicePerformedNumber)+"</div>";
            linkRow.appendChild(linkTd);

            var titleRow = document.createElement('tr');            
            var titleTd = document.createElement('td');
            titleTd.innerHTML = "<label for='heading"+servicePerformedNumber+"'>Title</label>";
            var inputTd = document.createElement('td');
            inputTd.innerHTML = "<input type='text' maxlength='64' class='input_box' id='heading"+servicePerformedNumber+"' name='heading"+servicePerformedNumber+"' value='' />";
            titleRow.appendChild(titleTd);
            titleRow.appendChild(inputTd);

            var descRow = document.createElement('tr');
            var descTitleTd = document.createElement('td');
            descTitleTd.innerHTML = "<label for='description"+servicePerformedNumber+"'>Description</label>";
            var descTextAreaTd = document.createElement('td');
            descTextAreaTd.innerHTML = "<textarea id='description"+servicePerformedNumber+"' rows='3' cols='20' class='project_input_textarea' name='description"+servicePerformedNumber+"'></textarea>";
            descRow.appendChild(descTitleTd);
            descRow.appendChild(descTextAreaTd);

            serviceperformed.appendChild(linkRow);
            serviceperformed.appendChild(titleRow);
            serviceperformed.appendChild(descRow);
            //alert(serviceperformed.innerHTML);
            servicePerformedNumber++;
        }
        else{
            
        }
    }

    function modifyUploadNo()
    {
        var ele = document.getElementById('uploadNo');
        ele.value = upload_number;

        var projTitle = document.getElementById("title");

        var title0 = document.getElementById("serviceperformed_heading1");

        if(!projTitle.value.length)
        {
            alert("Please enter project title !");
            projTitle.style.border = "1px solid #FF0000";
            return false;
        }

        var order = document.getElementById("order");
        var reg = /\d+/;

        if(!reg.test(order.value))
        {
            alert("Please enter no!");
            order.style.border = "1px solid #FF0000";
            return false;
        }

        /*if(!title0.value.length)
        {
            alert("Please enter heading for link1!");
            title0.style.border = "1px solid #FF0000";
            return false;
        }*/

        return true;
    }

    var maxChar = <?php echo CHAR_MAX_LENGTH ?>;
    function checkLength(obj , counterId)
    {
        //var countEle = document.getElementById(counterId);
        //if(obj.value.length > maxChar )
        {
            //obj.value = obj.value.substr(0,maxChar-1);
        }

        var count = maxChar - obj.value.length;
        if(count < 0)
            count = 0;
        //countEle.innerHTML = count;
    }

</script>

<script type="text/javascript">
function deletedata()
{
	if(confirm('Do you want to delete this record?'))
	{
		e = document.getElementById('drupal_form');
		e.action = "index.php?q=bios/delete";
		e.submit();
	}
}
</script>


<form name="drupal_form" id="drupal_form" action="index.php?q=bios/save" method="post" enctype="multipart/form-data" onsubmit="return modifyUploadNo()">
    <table>
        <tr>
            <td valign="top">
                <label for="title">Page Title</label>
            </td>
            <td>
                <input id="title" type="text" style="width:75%;border: 1px solid #7f9db9" name="title" value="<?php echo $row['title'] ?>" maxlength="64" />
            </td>
        </tr>

        <!--<tr>
            <td valign="top">Project Category</td>
            <td>
                <select id="project_category" name="project_category">
                    <option value="">Select</option>
                    <?php
                        $query = "SELECT category_name , category_id FROM druprojectcategory ";
                        $resultCategory = db_query($query);
                        while($projectCategory = db_fetch_array($resultCategory))
                        {
                            if($row['project_category'] == $projectCategory['category_id'] )
                            {
                            ?>
                                <option selected value="<?php //echo $projectCategory['category_id']?>"><?php //echo $projectCategory['category_name']?></option>
                            <?php
                            }else{
                            ?>
                                <option value="<?php //echo $projectCategory['category_id']?>"><?php //echo $projectCategory['category_name']?></option>
                            <?php
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td><label for="">Set home page</label></td>
            <td>
                <?php
                    $query = " SELECT * FROM druprojecthomepage d ";
                    $homePageResult = db_query($query);
                    $homePage = db_fetch_array($homePageResult);
                    $selected = "";
                    $noSelected = "";
                    if($homePage['project_id'] == $row['id'] )
                    {
                        $selected = "checked";
                        $noSelected = "";
                    }
                    else{
                        $selected = "";
                        $noSelected = "checked";
                    }
                ?>
                <label>Yes</label>
                <input type="radio" <?php //echo $selected ?> name="home" value="yes" />
                <label>No</label>
                <input type="radio" <?php// echo $noSelected ?> name="home" value="no" />
            </td>
        </tr>-->

       <!-- <tr>
            <td valign="top"><label for="client_name">Client name</label></td>
            <td><input id="client_name" type="text" name="client_name" value="<?php //echo $row['client_name'] ?>" maxlength="64" /></td>
        </tr>-->
        
    <tr>
            <td valign="top"><label for="description">Body Text</label></td>
            <td>
                <textarea onkeyup="checkLength(this , 'textCounter')" rows="10" cols="60" style="width:75%;" id="description" name="description" ><?php echo $row['description'] ?></textarea>

                <div>
                    <!--<div class="textCounter_text">
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
                    </div>-->
                </div>
            </td>
        </tr>

      <!--  <tr>
            <td valign="top"><label for="project_background">Bios Background</label></td>
            <td>
                <input type="file" name="project_background" id="project_background" value="<?php echo $row['project_background']?>" />
            </td>
        </tr>-->
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/bios_background/<?php echo $row['project_background'] ?>" style="display:none;"/></td>
        </tr>

        <tr>
            <td><label for="company_title">Company Title</label></td>
            <td><input id="company_title" style="width:75%;border: 1px solid #7f9db9" type="text" name="company_title" value="<?php echo $row['company_title'] ?>" /></td>
        </tr>
        <tr>
            <td><label for="order">Order</label></td>
            <td><input id="order" type="text" style="width:75%;border: 1px solid #7f9db9" name="order" value="<?php echo $row['order'] ?>" /></td>
        </tr>

        <tr>
            <td valign="top"><label for="project_bio_first_name">Bios Name</label></td>
            <td><input type="text" id="project_bio_first_name" name="project_bio_first_name" style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['project_bio_first_name'] ?>" maxlength="64" /></td>
        </tr>

        <tr>
          <td valign="top">Page URL </td>
          <td><input type="text" name="url" size="15" value="<?php echo $row['redirect_url']?>" style="width:75%;border: 1px solid #7f9db9"/></td>
        </tr>
        <tr>
            <td valign="top"><label for="project_bio_last_name">Thumbnail Title</label></td>
            <td><input type="text" id="project_bio_last_name" name="project_bio_last_name" style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['project_bio_last_name'] ?>" maxlength="64" /></td>
        </tr>
		<!--<tr>
            <td valign="top"><label for="Redirect Url">Redirect Url</label></td>
            <td><input type="text" size="15" style="width:75%"/></td>
        </tr>-->
        <tr>
            <td valign="top"><label for="project_bio_thumbnail">Bios Thumbnail</label></td>
            <td><input type="file" id="project_bio_thumbnail" name="project_bio_thumbnail" value="<?php echo $row['project_bio_thumbnail'] ?>"  /></td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="<?php echo $base_url; ?>/misc/bios_bio_thumbnail/<?php echo $row['project_bio_thumbnail'] ?>" />


                 <?php
                	if($row['project_bio_thumbnail'] != "")
					{
				?>
                <input type="checkbox" name="deleteimage_thumb" value="<?php echo $row['project_bio_thumbnail'] ?>" />
                <?php
					}
				?>     

                </td>
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
                                        <div style="float:left; padding: 5px;">
                                        	<img alt="<?php echo $images['alttag'] ?>" src="<?php echo $base_url; ?>/misc/bios_gallery/<?php echo $images['image'] ?>"  height="100"  width="100" />
                                            <div>
                                                <input type="checkbox" name="deleteimage[]" value="<?php echo $images['id'] ?>" />
                                                <input type="text" style="border: 1px solid #7f9db9" name="reorder[<?php echo $images['id'] ?>][]" size="10" value="<?php echo $images['order'] ?>" />
                                                <input type="hidden" name="imageOrderid[]" value="<?php echo $images['id'] ?>"
                                            </div>
											 <b>Alt: </b><input type="text" style="border: 1px solid #7f9db9" name="alt[]" size="11" value="<?php echo $images['alttag'] ?>" />
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
                            <input type="file" name="photo_gallery0" value="" />
                        </div>
                        <div id="moreUploadsLink" style="display:block;">
                            <a href="javascript:addFileInput();">Attach Another File</a>
                        </div>
						<br/>
						<div>
						<b>Please check the box for the photo you wish to delete and press save.</b></div>
                        <input type="hidden" name="uploadNo" id="uploadNo" value="<?php echo $i ?>" />
                    </tbody>
                </table>
            </td>
        </tr>

        <!--<tr>
            <td colspan="2"><div class="heading">Enter data for links</div></td>
            <td></td>
        </tr>

        <tr>
            <td colspan="2">
                <table id="serviceperformed">
                    <tbody id="serviceperformed_body">
            
        <?php
            $query = "SELECT * FROM druproject_serviceperformed WHERE project_id = ".$id;
            $resultServicePerformed = db_query($query);
            $i = 1;
            $servicePerformed = db_fetch_array($resultServicePerformed)
            
        ?>
                <tr>
                    <td colspan="2" align="center"><div class="heading">Link 1</div></td>
                </tr>
                <tr>
                    <td valign="top"><label for="heading<?php echo $i ?>">Title</label></td>
                    <td><input id="serviceperformed_heading<?php echo $i ?>" class="input_box" type="text" maxlength="64" name="heading<?php echo $i ?>" value="<?php echo $servicePerformed['heading1'] ?>" /></td>
                </tr>
                <tr>
                    <td valign="top"><label for="description<?php echo $i ?>">Description</label></td>
                    <td><textarea rows="3" cols="20" class="project_input_textarea" id="serviceperformed_description<?php echo $i ?>" class="input_box" name="description<?php echo $i ?>" ><?php echo $servicePerformed['description1'] ?></textarea></td>
                </tr>
                
        <?php
            $k = 2;
            //while($i < 3)
            {
                $j = $i+1;
                if($servicePerformed['heading'.$j] || $servicePerformed['description'.$j] )
                {
        ?>
                    <tr>
                        <td colspan="2" align="center"><div class="heading">Link <?php echo $k ?></div></td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="heading<?php echo $j ?>">Title</label></td>
                        <td><input id="serviceperformed_heading<?php echo $j ?>" class="input_box" type="text" maxlength="64" name="heading<?php echo $j ?>" value="<?php echo $servicePerformed['heading'.$j] ?>" /></td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="description<?php echo $j ?>">Description</label></td>
                        <td><textarea rows="3" cols="20" class="project_input_textarea" id="serviceperformed_description<?php echo $j ?>" class="input_box" name="description<?php echo $j ?>" ><?php echo $servicePerformed['description'.$j] ?></textarea></td>
                    </tr>
        <?php
                    $k++;
                }
                $i++;
            }            
        ?>
                    <input type="hidden" name="serviceperformedId" value="<?php echo $servicePerformed['id'] ?>" />

                    <script type="text/javascript">servicePerformedNumber = <?php echo $k ?></script>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="1"></td>
            <td><a href="javascript:addServiceperformedInput();" onclick="">Add More</a></td>
        </tr>-->
        
        <tr>
            <td></td>
            <td><input type="submit" name="save" value="Save" />&nbsp; <input type="button" name="delete" value="Delete" onclick="javascript: deletedata()" /></td>
        </tr>
    </table>
    
    <input type="hidden" name="bios_id" value="<?php echo $row['id'] ?>" />
    
</form>