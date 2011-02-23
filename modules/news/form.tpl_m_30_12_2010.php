<?php
	global $base_url;
	
	$id = news_get($_REQUEST , 'news_id' , 0 );
	
  	$query = " SELECT d.* FROM drunews d WHERE d.`id` = '$id' ";
  	$result = db_query($query);
  	$row = db_fetch_array($result);
	
  	$query = " SELECT id , image,`order`,alttag FROM drunews_images WHERE news_id = '$id' order by `order` ASC";
  	$result_images = db_query($query);
	
  	if(isset($_REQUEST['msg']))
  	{
?>
		<div class="error"><?php echo $_REQUEST['msg'] ?></div>
<?php
  	}
?>
<script type="text/javascript" src="<?php echo $base_url."/" ?>editor/ckeditor.js"></script>
<script src="<?php echo $base_url."/" ?>datepicker/prototype-1.6.0.2.js" type="text/javascript"></script>
<script src="<?php echo $base_url."/" ?>datepicker/prototype-date-extensions.js" type="text/javascript"></script>
<script src="<?php echo $base_url."/" ?>datepicker/datepicker.js" type="text/javascript"></script>
<link href="<?php echo $base_url."/" ?>datepicker/datepicker.css" rel="stylesheet" />
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
        debugger;
		var ele = document.getElementById('uploadNo');
        ele.value = upload_number;

        var projTitle = document.getElementById("news_heading");

        //var title0 = document.getElementById("serviceperformed_heading1");

        if(!projTitle.value.length)
        {
            alert("Please enter project title !");
            projTitle.style.border = "1px solid #FF0000";
            return false;
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
		e.action = "index.php?q=news/delete";
		e.submit();
	}
}
</script>

<form name="drupal_form" id="drupal_form" action="index.php?q=news/save" method="post" enctype="multipart/form-data" onsubmit="return modifyUploadNo()">
    <table>
        <tr>
            <td valign="top">
                <label for="news_heading">News Heading</label>
            </td>
            <td>
                <input id="news_heading" type="text" style="width:75%;border: 1px solid #7f9db9"  name="news_heading" value="<?php echo $row['news_heading'] ?>" />
            </td>
        </tr>

        <tr>
            <td valign="top"><label for="news_type">News Category</label></td>
            <td>
               
                   
                    <?php
                    
            $query = "SELECT category_name , category_id FROM drunews_category ";
            $resultCategory = db_query($query);
            while($newsCategory = db_fetch_array($resultCategory))
            {

            $query1 = " SELECT d.* FROM drunews_category_detail d WHERE d.`news_id` = '$id' ";
            $result1 = db_query($query1);
            while($row1 = db_fetch_array($result1))
            {
                if($row1['category_id'] == $newsCategory['category_id'] )
                {
                ?>
                    <input type="checkbox" checked name="news_type[]" value="<?php echo $newsCategory['category_id']?>" /><?php echo $newsCategory ['category_name']?>
                <?php

                }

            }
            }

            $queryforchekbox="SELECT category_name , category_id FROM drunews_category where category_id not in (SELECT category_id FROM drunews_category_detail WHERE news_id = '$id')";
             $resultqueryforchekbox = db_query($queryforchekbox);
            while($newsCategoryforchekbox = db_fetch_array($resultqueryforchekbox))
            {?>
                 <input type="checkbox" name="news_type[]" value="<?php echo $newsCategoryforchekbox ['category_id']?>" /><?php echo $newsCategoryforchekbox ['category_name']?>
                 <?php
            }
                    ?>
             
                
               
            </td>
        </tr>

        <tr>
            <td valign="top"><label for="news_date">News Time</label></td>
            <td><input id="news_date" style="width:75%;border: 1px solid #7f9db9" type="text" name="news_date" value="<?php echo $row['news_date'] ?>" /></td>
        </tr>
		
        <tr>
            <td valign="top"><label for="media_contact">Media Contact</label></td>
            <td>
                <textarea rows="10" cols="60" style="width:75%;"  id="media_contact" name="media_contact" ><?php echo $row['media_contact']; ?></textarea>
            </td>
        </tr>
        
        <tr>
            <td valign="top"><label for="news_date">Media Email</label></td>
            <td><input id="media_email" style="width:75%;border: 1px solid #7f9db9" type="text" name="media_email" value="<?php echo $row['media_email'] ?>" /></td>
        </tr>
        
        <tr>
            <td valign="top"><label for="news_description">Description</label></td>
            <td>
                <textarea rows="10" cols="60" class="project_input_textarea" id="news_description" name="news_description" ><?php echo $row['news_description'] ?></textarea>
            </td>
        </tr>
		<tr>
		  <td valign="top">Page URL </td>
		  <td><input type="text" name="url" size="15" value="<?php echo $row['redirect_url']?>" style="width:75%;border: 1px solid #7f9db9"/></td>
	  </tr>
		<tr>
           <td valign="top"><label for="page_background">Page Background</label></td>
            <td>
                <input type="file" name="page_background" id="page_background" value="<?php echo $row['page_background']?>" />
            </td>
        </tr>
		 <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/news_background/<?php echo $row['page_background'] ?>" /></td>
        </tr>
		
        <tr>
            <td valign="top"><label for="news_thumbnail">News Thumbnail</label></td>
            <td>
                <input type="file" name="news_thumbnail" id="news_thumbnail" value="<?php echo $row['news_thumbnail']?>" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/news_thumbnail/<?php echo $row['news_thumbnail'] ?>" />

                 <?php
                	if($row['news_thumbnail'] != "")
					{
				?>
               <input type="checkbox" name="deleteimage_thumb" value="<?php echo $row['news_thumbnail'] ?>" />
                <?php
					}
				?>  

               </td>
        </tr>

        <tr>
            <td valign="top"><label for="news_pdf">Pdf</label></td>
            <td>
                <input type="file" id="news_pdf" name="news_pdf" class="input_box" value="<?php echo $row['news_pdf'] ?>" />
                <a href="misc/news_pdf/<?php echo $row['news_pdf'] ?>"><?php echo $row['news_pdf'] ?></a>
                
                <?php
                	if($row['news_pdf'] != "")
					{
				?>
                <input type="checkbox" name="delete_pdf" value="<?php echo $row['news_pdf'] ?>"/>
                <?php
					}
				?>

            </td>
        </tr>
        
        <tr>
        	<td>&nbsp;</td>
            <td ><b>Please check the box for the photo you wish to delete and press save.</b></td>
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
                                        	<img alt="<?php echo $images['alttag'] ?>"  src="<?php echo $base_url ?>/misc/news_gallery/<?php echo $images['image'] ?>"  height="100"  width="100" />
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
                            <input type="file" name="photo_gallery0" value="" id="photo_gallery0"/>
                             <span>Attach flv</span><input type="checkbox" name="photo_gallery_check0" onclick="showUploadImageOption(this, this.name+'video')" />
                                <input type="file" id="photo_gallery_check0video" name="photo_gallery_video_file0" style="display: none;"><b>(Only FLV files are supported for upload.)</b>
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
            <td><input type="submit" name="save" value="Save" /> &nbsp; <input type="button" name="delete" value="Delete" onclick="javascript: deletedata()" /></td>
        </tr>
        
    </table>
<script type="text/javascript">//<![CDATA[
    CKEDITOR.replace('news_description', { "width": "600" ,
                        toolbar :
                        [
                            ['Source','-','NewPage','Preview','-','Templates'],
                            ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
                            ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
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

           new Control.DatePicker("news_date", {
            icon: 'misc/calendar.png',            
            dateFormat: 'yyyy-MM-dd'
            //timePicker: true,
            //timePickerAdjacent: true
        });

    //]]></script>

    <input type="hidden" name="news_id" value="<?php echo $row['id'] ?>" />
    
</form>