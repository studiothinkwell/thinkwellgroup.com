<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump();
//var_dump($_SERVER);
//exit;

$id = projects_get($_REQUEST , 'project_id' , 0 );

  $query = "SELECT p.* FROM druprojects p WHERE p.`id` = '$id' ";
  $result = db_query($query);
  $row = db_fetch_array($result);

  $query = " SELECT id, image , video,`order`,alttag FROM druprojects_images WHERE project_id = '$id' order by `order` ASC ";
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
        
        var checkbox = document.createElement("input");

        checkbox.setAttribute("type", "checkbox");
        checkbox.setAttribute("name", "photo_gallery_check"+upload_number);
        checkbox.onclick = function(){
            showUploadImageOption(this,this.name+'video');
        }

        var videoFileInput = document.createElement("input");
        videoFileInput.setAttribute('id',"photo_gallery_check"+upload_number+'video');
        videoFileInput.setAttribute("type", "file");
        videoFileInput.setAttribute("name", "photo_gallery_video_file"+upload_number);
        videoFileInput.style.display = "none";

        var s = document.createElement("span");
        s.innerHTML = "Attach flv";

        d.appendChild(file);
        d.appendChild(s);
        d.appendChild(checkbox);
        d.appendChild(videoFileInput);
        document.getElementById("moreUploads").appendChild(d);
        upload_number++;
            
    }

    function showUploadImageOption(obj,id)
    {
        var ele = document.getElementById(id);

        if(obj.checked)
        {
            ele.style.display = "inline";
        }
        else{
            ele.style.display = "none";
        }
    }



    var servicePerformedNumber = 2;
    function addServiceperformedInput()
    {
        if(servicePerformedNumber < 4)
        {
            var serviceperformed = document.getElementById("serviceperformed_body");
/*
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
            inputTd.innerHTML = "<input type='text' class='input_box' id='heading"+servicePerformedNumber+"' name='heading"+servicePerformedNumber+"' value='' />";
            titleRow.appendChild(titleTd);
            titleRow.appendChild(inputTd);*/

            var descRow = document.createElement('tr');
            var descTitleTd = document.createElement('td');
            descTitleTd.innerHTML = "<label for='description"+servicePerformedNumber+"'>Services</label>";
            var descTextAreaTd = document.createElement('td');
            descTextAreaTd.innerHTML = "<textarea id='description"+servicePerformedNumber+"' rows='3' cols='20' class='project_input_textarea' name='description"+servicePerformedNumber+"'></textarea>";
            descRow.appendChild(descTitleTd);
            descRow.appendChild(descTextAreaTd);

            //serviceperformed.appendChild(linkRow);
            //serviceperformed.appendChild(titleRow);
            serviceperformed.appendChild(descRow);
            //alert(serviceperformed.innerHTML);
            servicePerformedNumber++;
        }
        else{
            
        }
    }

    function modifyUploadNo(obj)
    {
        var ele = document.getElementById('uploadNo');
        ele.value = upload_number;

        var projTitle = document.getElementById("title");

        var title0 = document.getElementById("serviceperformed_heading1");

        if(!projTitle.value.length)
        {
            alert("Please enter title");
            projTitle.style.border = "1px solid #FF0000";
            return false;
        }
		
		if(document.drupal_form.video.value != "")
		{
			var filename = document.drupal_form.video.value;
			var dot = filename.lastIndexOf(".");
			var extension = filename.substr(dot, filename.length);
			if(extension != ".flv")
			{
				alert('Please upload only FLV files.');
				document.drupal_form.video.focus();
				return false;
			}
			else if(document.drupal_form.side_video_image.value == "")
			{
				alert('Please upload an image for the video.');
				document.drupal_form.side_video_image.focus();
				return false;
			}
			
		}
		for(i=0;i<document.getElementById("uploadNo").value;i++)
		{
			var videoId=document.getElementById("photo_gallery_check"+i+"video").value;
			if(videoId != "")
			{
				var filename = videoId;
				var dot = filename.lastIndexOf(".");
				var extension = filename.substr(dot, filename.length);
				if(extension != ".flv")
				{
					alert('Please upload only FLV files.');
					//document.drupal_form.photo_gallery_check0video.focus();
					return false;
				}
			
				
			}
		}
        if(!title0.value.length)
        {
            //alert("Please enter at least one link");
            title0.style.border = "1px solid #FF0000";
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

        return true;
        
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
		e.action = "index.php?q=projects/delete";
		e.submit();
	}
}
</script>

<form name="drupal_form" id="drupal_form" action="index.php?q=projects/save" method="post" enctype="multipart/form-data" onsubmit="return modifyUploadNo(this)">
    <table>
        <tr>
            <td valign="top"><label for="title">Page Title</label></td>
            <td><input id="title" type="text" style="width:75%;border: 1px solid #7f9db9" name="title" value="<?php echo $row['title'] ?>" /></td>
        </tr>
  		<tr>
            <td valign="top"><label for="project_bio_last_name">Homepage Title</label></td>
            <td><textarea rows="10" cols="60" style="width:75%;" id="project_bio_last_name" name="project_bio_last_name" ><?php echo $row['project_bio_last_name'] ?></textarea></td>
        </tr>
		<tr>
            <td valign="top">Homepage Description</td>
            <td><textarea rows="10" cols="60" style="width:75%;" id="home_description" name="home_description" ><?php echo $row['home_description'] ?></textarea></td>
        </tr>
        <tr>
            <td valign="top"><label for="title">Home Page Alternate URL</label></td>
            <td>
            	<input id="title" type="text" style="width:75%;border: 1px solid #7f9db9" name="homepage_url" value="<?php echo $row['homepage_url'] ?>" />
                <br />
                (*Please enter full URL like: <strong style="color:#F00;">http://www.example.com</strong>, else it will not work.)
           	</td>
        </tr>
        <tr>
            <td valign="top">Project Category</td>
            <td>
                <?php
					$query = " SELECT category_id FROM  druproject_category_map  WHERE project_id = ".$row['id'];
					$result = db_query($query);
					$project_categories = array();
					while($category = db_fetch_array($result))
					{
						$project_categories[] = $category['category_id'];
					}

					$query = "SELECT category_name , category_id FROM druprojectcategory ";
					$resultCategory = db_query($query);
					$i = 0;
					while($projectCategory = db_fetch_array($resultCategory))
					{
						if(in_array($projectCategory['category_id'] , $project_categories) )
						{
						?>
							<div style="float: left;padding: 5px;">
								<input checked type="checkbox" name="project_category[]" value="<?php echo $projectCategory['category_id'] ?>" />
								<?php echo $projectCategory['category_name']?>
							</div>
						<?php
						}else{
						?>
							<div style="float: left;padding: 5px;">
								<input type="checkbox" name="project_category[]" value="<?php echo $projectCategory['category_id'] ?>" />
								<?php echo $projectCategory['category_name']?>
							</div>
						<?php
							if($i == 4)
							{
								$i = 0;
						?>
							<div class="" style="clear: both;"></div>
						<?php
							}
						}

						$i++;
					}
               	?>
         	</td>
        </tr>
        <tr>
            <td><label for="">Set Home Page</label></td>
            <td>
                <?php
                    $query = " SELECT * FROM druprojecthomepage d WHERE project_id = '$id'";
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
                <input type="radio" <?php echo $selected ?> name="home" value="yes" />
                <label>No</label>
                <input type="radio" <?php echo $noSelected ?> name="home" value="no" />
            </td>
        </tr>

        <tr>
            <td valign="top"><label for="client_name">Client Name</label></td>
            <td><input id="client_name" type="text" style="width:75%;border: 1px solid #7f9db9" name="client_name" value="<?php echo $row['client_name'] ?>" /></td>
        </tr>

        <tr>
            <td valign="top">Body Text</td>
            <td>

                
                <textarea rows="10" cols="60" id="description" style="width:75%;" name="description" ><?php echo $row['description'] ?></textarea>
                <!--Commented by suddhashil as per client request
                <textarea onkeyup="checkLength(this , 'textCounter')" rows="3" cols="20" class="project_input_textarea" id="description" name="description" ><?php echo $row['description'] ?></textarea>
				 
                <div>
                    <div class="textCounter_text">
                        Number of characters remaining.
                    </div>
                    <div class="textCounter">
                    <span id="textCounter">
                        <script type="text/javascript">
                            var c = maxChar - <?php //echo strlen($row['description']) ?>;
                            if(c < 0)
                                c = 0;
                            document.write(c);
                        </script>
                    </span>
                    </div>
                </div>-->
            </td>
        </tr>

        <tr>
            <td valign="top"><label for="project_background">Project Background</label></td>
            <td>
                <input type="file" name="project_background" id="project_background" value="<?php echo $row['project_background']?>" />
            </td>
        </tr>
		<tr>
            <td valign="top"><label for="Redirect Url">Page URL </label></td>
            <td><input type="text" name="url" size="15" style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['redirect_url'] ?>"/></td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/project_background/<?php echo $row['project_background'] ?>" /> 
                
                <?php 
                if($row['project_background']!='')
                    {
                ?>
                <input type="checkbox" name="delete_background" value="<?php echo $row['project_background'] ?>" /></td>
                    <?php

                    }
                    ?>
        </tr>

        <tr>
            <td><label for="order">Project Order</label></td>
            <td><input type="text" name="order" id="order" style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['order'] ?>"  /></td>
        </tr>
        
        <tr>
            <td><label for="order">Homepage Order</label></td>
            <td><input type="text" name="homepage_order" id="homepage_order"  style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['homepage_order'] ?>"  /></td>
        </tr>
        
        <!--<tr>
            <td valign="top"><label for="homepage_image">Homepage Image</label></td>
            <td><input type="file" id="homepage_image" name="homepage_image" value=""  /></td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/homepage_image/<?php echo $row['homepage_image'] ?>" /></td>
        </tr>-->

        <tr>
            <td valign="top"><label for="project_bio_first_name">Thumbnail Title</label></td>
            <td><input type="text" id="project_bio_first_name" name="project_bio_first_name" style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['project_bio_first_name'] ?>" /></td>
        </tr>
      
        <tr>
            <td valign="top"><label for="project_bio_thumbnail">Project  Thumbnail</label></td>
            <td><input type="file" id="project_bio_thumbnail" name="project_bio_thumbnail" value="<?php echo $row['project_bio_thumbnail'] ?>"  /></td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/project_bio_thumbnail/<?php echo $row['project_bio_thumbnail'] ?>" />
                <?php
                if($row['project_bio_thumbnail']!='')
                    {
                ?>
                <input type="checkbox" name="delete_thumbnail" value="<?php echo $row['project_bio_thumbnail'] ?>"/>

                    <?php
                    }
                     ?>
                    </td>
        </tr>

        <tr>
            <td><label for="project_url">Project Url</label></td>
            <td>
                <input type="text" style="width:75%;border: 1px solid #7f9db9"  id="project_url" name="project_url" value="<?php echo $row['project_url'] ?>"  />
                Please also enter http:// or https://
            </td>
        </tr>

        <tr>
            <td>Side Video</td>
            <td>
            	<input type="file" id="video" name="video" value="<?php echo $row['video'] ?>" />
				<?php echo $row['video'] ?> 
                <b>(Only FLV files are supported for upload.)</b>
                <?php
                	if($row['video'] != "")
					{
				?>
                <input type="checkbox" name="deletesidevideo" value="<?php echo $row['video']; ?>" />
                <?php
					}
				?>                
          	</td>
        </tr>
         <tr>
            <td>Side Video Image</td>
            <td><input type="file" id="side_video_image" name="side_video_image" value="" /></td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/project_video/<?php echo $row['side_video_image'] ?>" />
                
                <?php
                if($row['side_video_image']!='')
                    {
                    ?>
                
                
                <input type="checkbox" name="delete_sidevedio" value="<?php echo $row['side_video_image'] ?>"/>
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
                            <td><b>Please check the box for the photo you wish to delete and press save.</b></td>
                        </tr>
                        <tr>
                            <td>
                        <?php
                            $i = 1;
                            while( $images = db_fetch_array($result_images) )
                            {
                        ?>
                                
                                        <!--<input type="file" name="photo_gallery<?php //echo $i ?>" value="<?php //echo $images['image'] ?>" />-->
                                        <div style="float:left; padding: 5px;">
                                            <img alt="<?php echo $images['alttag'] ?>" src="misc/project_gallery/<?php echo $images['image'] ?>"  height="100"  width="100" />
                                            <div>
                                                <input type="checkbox" name="deleteimage[]" value="<?php echo $images['id'] ?>" />
                                                <input type="text" style="border: 1px solid #7f9db9" name="reorder[<?php echo $images['id'] ?>][]" size="10" value="<?php echo $images['order'] ?>" />
                                                <input type="hidden" name="imageOrderid[]" value="<?php echo $images['id'] ?>"
                                                <?php
                                                    if($images['video'])
                                                        echo "Video File";
                                                ?>
                                            </div> <b>Alt: </b><input type="text" style="border: 1px solid #7f9db9" name="alt[]" size="11" value="<?php echo $images['alttag'] ?>" />
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
                            <div>
                                <input type="file" name="photo_gallery0" value="" />
                                <span>Attach flv</span><input type="checkbox" name="photo_gallery_check0" onclick="showUploadImageOption(this, this.name+'video')" />
                                <input type="file" id="photo_gallery_check0video" name="photo_gallery_video_file0" style="display: none;">  (.flv files are only supported for uploading)
                            </div>
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
            <td colspan="2">
                <table id="serviceperformed" width="100%">
                    <tbody id="serviceperformed_body">
            
        <?php
            $query = "SELECT * FROM druproject_serviceperformed WHERE project_id = ".$id;
            $resultServicePerformed = db_query($query);
            $i = 1;
            $servicePerformed = db_fetch_array($resultServicePerformed)
            
        ?>
               
               
                <tr>
                    <td valign="top"><label for="description<?php echo $i ?>">Services</label></td>
                    <td><textarea rows="10" cols="60" id="serviceperformed_description<?php echo $i ?>" style="width:75%;" name="description<?php echo $i ?>" ><?php echo $servicePerformed['description1'] ?></textarea></td>
                </tr>
                
        <?php
            $k = 2;
            while($i < 3)
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
                        <td>
                        	<!--<input id="serviceperformed_heading<?php //echo $j ?>" class="input_box" type="text" maxlength="64" name="heading<?php //echo $j ?>" value="<?php //echo $servicePerformed['heading'.$j] ?>" />-->
                            <input id="serviceperformed_heading<?php echo $j ?>" class="input_box" type="text" name="heading<?php echo $j ?>" value="<?php echo $servicePerformed['heading'.$j] ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="description<?php echo $j ?>">Description</label></td>
                        <td><textarea rows="3" cols="60" id="serviceperformed_description<?php echo $j ?>" class="input_box project_input_textarea" name="description<?php echo $j ?>" ><?php echo $servicePerformed['description'.$j] ?></textarea></td>
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

       <!-- <tr>
            <td colspan="1"></td>
            <td><a href="javascript:addServiceperformedInput();" onclick="">Add More</a></td>
        </tr>-->
        
        <tr>
            <td></td>
            <td><input type="submit" name="save" value="Save" />&nbsp; <input type="button" name="delete" value="Delete" onclick="javascript: deletedata()" /></td>
        </tr>
    </table>
    
    <input type="hidden" name="project_id" value="<?php echo $row['id'] ?>" />

    <script type="text/javascript">//<![CDATA[
CKEDITOR.replace('description', { "width": "600" ,
            toolbar :
            [
                ['Source','-','Save','NewPage','Preview','-','Templates'],
                ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
                ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
                ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select',  'ImageButton', 'HiddenField'],
                '/',
                ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
                ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Link','Unlink','Anchor'],
                ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
                '/',
                ['Styles','Format','Font','FontSize'],
                ['TextColor','BGColor'],
                ['Maximize', 'ShowBlocks','-','About']
            ]

});
//        function createEditor(id)
//        {
//            CKEDITOR.replace(id, { "width": "600" ,
//                        toolbar :
//                        [
//                            ['Source','-','NewPage','Preview','-','Templates'],
//                            ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
//                            ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
//                            '/',
//                            ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
//                            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
//                            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
//                            ['Link','Unlink','Anchor'],
//                            ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
//                            '/',
//                            ['Styles','Format','Font','FontSize'],
//                            ['TextColor','BGColor'],
//                            ['Maximize', 'ShowBlocks','-','About']
//                        ]
//
//            });
//        }
//
//        createEditor('banner1description');
        //createEditor('banner2description');
        //createEditor('banner3description');



//]]></script>

</form>