<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump();
//var_dump($_SERVER);
//exit;

$id = campaign_get($_REQUEST , 'campaign_id' , 0 );

  $query = " SELECT c.id , c.redirect_url, c.`campaign_title` ,c.page_background, cc.content_field FROM campaign c
                LEFT JOIN campaign_content cc ON c.id = cc.campaign_id
                WHERE c.id = $id ";
  $result = db_query($query);
  $row = db_fetch_array($result);


  $query = " SELECT `image`,id,`order`,alttag FROM drujobs WHERE campaign_id = $id order by `order` ASC";
  $result_images = db_query($query);

  if(isset($_REQUEST['msg']))
  {
?>

<div class="error">
    <?php echo $_REQUEST['msg'] ?>
</div>
<?php
  }
  
  $host  = $_SERVER['HTTP_HOST'];
 $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
 $base_url = "http://".$host.$uri;
?>

<script type="text/javascript" src="<?php echo $base_url?>/editor/ckeditor.js"></script>

<script language="javascript" type="text/javascript">
    var upload_number = 0;
    function addFileInput()
    {
           /* if(upload_number <= 6)
            {
           */       
                    var d = document.createElement("div");
                    var file = document.createElement("input");
                    file.setAttribute("type", "file");
                    file.setAttribute("name", "photo_gallery"+upload_number);
                    d.appendChild(file);
                    document.getElementById("moreUploads").appendChild(d);
                    upload_number++;
           // }
    }

    function modifyUploadNo()
    {
        var ele = document.getElementById('uploadNo');
        ele.value = upload_number;
    }

</script>
<script type="text/javascript">
function deletedata()
{
	if(confirm('Do you want to delete this record?'))
	{
		e = document.getElementById('drupal_form');
		e.action = "index.php?q=campaign/delete";
		e.submit();
	}
}
function previewdata()
{

    //window.location="http://localhost/thinkwellgroup/campaignlanding";
	debugger;
	var win=window.open("http://www.dev.thinkwellgroup.com/campaign.php",'name',
		'height=1000,width=1200,toolbar=no,directories=no,status=no,scrollbars=yes,resizable=no');
}
function copyimgpath(path)
{
document.getElementById('pathTxt').value=path;
}
</script>



<form name="drupal_form" id="drupal_form" action="index.php?q=campaign/save" method="post" enctype="multipart/form-data" onsubmit="modifyUploadNo()">
    <table>
        <tr>
            <td valign="top">
                <label for="campaign_title">Campaign Title</label>
            </td>
            <td>
                <input id="campaign_title" type="text" name="campaign_title" value="<?php echo $row['campaign_title'] ?>" style="width:75%;border: 1px solid #7f9db9" maxlength="64" />
            </td>
        </tr>
		
        <tr>
        	<td>&nbsp;</td>
            <td ><b>Please check the box for the photo you wish to delete and press save.</b></td>
        </tr>
        <tr>
            <td valign="top">
                Media Gallery
            </td>
            <td>
                <table id="photogallery">
                    <tbody id="photogallery_table">
                    	<tr>
                        	<td>
                        <?php
                            $i = 0;
                            while( $images = db_fetch_array($result_images) )
                            {
                        ?>
                        	<div style="float:left; padding: 5px;"><img alt="<?php echo $images['alttag'] ?>" src="<?php echo $base_url; ?>/misc/gallery/<?php echo $images['image'] ?>" width="100" height="100"  />

                                <div>
                                                <input type="checkbox" name="deleteimage[]" value="<?php echo $images['id'] ?>" />
                                                <input type="text" style="border: 1px solid #7f9db9" name="reorder[<?php echo $images['id'] ?>][]" size="10" value="<?php echo $images['order'] ?>" />
                                                <input type="hidden" name="imageOrderid[]" value="<?php echo $images['id'] ?>"
                                               
                                            </div>
											 <b>Alt: </b><input type="text" style="border: 1px solid #7f9db9" name="alt[]" size="11" value="<?php echo $images['alttag'] ?>" />
                                    <div> &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="imgpath" onclick="copyimgpath('misc/gallery/<?php echo $images['image'] ?>');" value="Show Path"/></div>
                                </div>
                        <?php
                            	$i++;
                            }
                        ?>
                        	</td>
                    	</tr>
                    <script type="text/javascript">
                        upload_number = <?php echo $i ?>;
                    </script>
                        <div id="moreUploads"></div>
                        <div id="moreUploadsLink" style="display:block;">
                            <a href="javascript:addFileInput();">Attach Another File</a>
                        </div>
                        <input type="hidden" name="uploadNo" id="uploadNo" value="<?php echo $i ?>" />
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top">Image Path</td>
            <td>
               <input type="text" name="" size="70" style="width:75%;border: 1px solid #7f9db9" id="pathTxt" value="" />
            </td>
        </tr>
			<tr>
            <td valign="top"><label for="Redirect Url">Page URL</label></td>
            <td><input type="text" name="url" size="15" style="width:75%;border: 1px solid #7f9db9" value="<?php echo $row['redirect_url']?>"/></td>
        </tr>
		<tr>
           <td valign="top"><label for="page_background">Page Background</label></td>
            <td>
                <input type="file" name="page_background" id="page_background" value="<?php echo $row['page_background']?>" />
            </td>
        </tr>
		 <tr>
            <td></td>
            <td><img height="100" width="100" src="<?php echo $base_url; ?>/misc/campaign_background/<?php echo $row['page_background'] ?>" /></td>
        </tr>
        <tr>
            <td valign="top">Landing Form</td>
            <td>
                <textarea cols="80" id="content_field" name="content_field" rows="10">
                    <?php echo $row['content_field'] ?>
                </textarea>
            </td>
        </tr>


<?php
$getBanner1 = campaign_getBanner($id , 'pos1');
?>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" align="center"><b>Lower Part</b></td>
    </tr>

    <!--<tr>
        <td colspan="2" align="center"><div class="heading">Banner Left</div></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner1title" >Title</label></td>
        <td><input id="banner1title" type="text" name="banner1[title]" value="<?php echo $getBanner1['title'] ?>" class="input_box" maxlength="64" /></td>
    </tr>-->
    <tr>
        <td valign="top"><label for="banner1description" >Description</label></td>
        <td>
        	<textarea id="banner1description" name="banner1[description]" cols="20" rows="5" class="input_textarea"><?php echo $getBanner1['description'] ?></textarea>
            <input type="hidden" name="banner1[banner_id]" value="<?php echo $getBanner1['id'] ?>" />
            <input type="hidden" name="banner1[position]" value="<?php echo 'pos1' ?>" />
       	</td>
    </tr>
   <!-- <tr>
        <td valign="top"><label for="banner1photo" >Image</label></td>
        <td>
            <input type="file" size="32" id="banner1photo" name="banner1[photo]" value="<?php echo $getBanner1['image_name'] ?>" class="input_box" />
        </td>
    </tr>
    <tr>
        <td></td>
        <td><img src="misc/banner/<?php echo $getBanner1['image_name'] ?>"  /></td>
    </tr>
    <input type="hidden" name="banner1[banner_id]" value="<?php echo $getBanner1['id'] ?>" />
    <input type="hidden" name="banner1[position]" value="<?php echo 'pos1' ?>" />

<?php
$getBanner2 = campaign_getBanner($id , 'pos2');
?>
    <tr>
        <td colspan="2" align="center"><div class="heading">Banner Center</div></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner2title" >Title</label></td>
        <td><input id="banner2title" type="text" name="banner2[title]" value="<?php echo $getBanner2['title'] ?>" class="input_box" maxlength="64" /></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner2description" >Short Description</label></td>
        <td><textarea id="banner2description" name="banner2[description]" cols="20" rows="5" class="input_textarea"><?php echo $getBanner2['description'] ?></textarea></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner2photo" >Image</label></td>
        <td><input type="file" size="32" id="banner2photo" name="banner2[photo]" value="<?php echo $getBanner2['image_name'] ?>"  class="input_box"/>
            
        </td>
    </tr>
    <tr>
        <td></td>
        <td><img src="misc/banner/<?php echo $getBanner2['image_name'] ?>"  /></td>
    </tr>
    <input type="hidden" name="banner2[banner_id]" value="<?php echo $getBanner2['id'] ?>" />
    <input type="hidden" name="banner2[position]" value="<?php echo 'pos2' ?>" />

<?php
$getBanner3 = campaign_getBanner($id, 'pos3');
?>
    <tr>
        <td colspan="2" align="center"><div class="heading">Banner Right</div></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner3title" >Title</label></td>
        <td><input type="text" id="banner3title" name="banner3[title]" value="<?php echo $getBanner3['title'] ?>" class="input_box" /></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner3description" >Short Description</label></td>
        <td><textarea id="banner3description" name="banner3[description]" cols="20" rows="5" class="input_textarea"><?php echo $getBanner3['description'] ?></textarea></td>
    </tr>
    <tr>
        <td valign="top"><label for="banner3photo" >Image</label></td>
        <td>
            <input type="file" size="32" id="banner3photo" name="banner3[photo]" value="<?php echo $getBanner3['image_name'] ?>" />
            
        </td>
    </tr>
    <tr>
        <td></td>
        <td><img src="misc/banner/<?php echo $getBanner3['image_name'] ?>"  /></td>
    </tr>
    <input type="hidden" name="banner3[banner_id]" value="<?php echo $getBanner3['id'] ?>" />
    <input type="hidden" name="banner3[position]" value="<?php echo 'pos3' ?>" />-->

    <tr>
        <td></td>
        <td><input type="submit" name="save" value="Save" />&nbsp; <input type="button" name="delete" value="Delete" onclick="javascript: deletedata()" />&nbsp;
            &nbsp; <input type="button" name="preview" value="Preview" onclick="javascript:previewdata()" /></td>
    </tr>

    </table>

    <script type="text/javascript">//<![CDATA[
CKEDITOR.replace('content_field', { "width": "600" ,
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
        function createEditor(id)
        {
            CKEDITOR.replace(id, { "width": "600" ,
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
        }

        createEditor('banner1description');
        //createEditor('banner2description');
        //createEditor('banner3description');



//]]></script>

    <input type="hidden" name="campaign_id" value="<?php echo $row['id'] ?>" />

</form>