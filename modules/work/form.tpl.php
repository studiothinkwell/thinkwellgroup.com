<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump();
//var_dump($_SERVER);
//exit;

$id = work_get($_REQUEST , 'work_id' , 1 );

  $query = " SELECT d.`id`, d.`description1`, d.`description2` FROM druwork d WHERE d.`id` = '$id' ";
  $result = db_query($query);
  $row = db_fetch_array($result);

  /*$query = " SELECT p.`work_id`, p.`image` FROM druwork_images p WHERE p.`work_id` = '$id' ";
  $result_images = db_query($query);*/

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
<script type="text/javascript" src="editor/ckeditor.js"></script>
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

    function modifyUploadNo()
    {
        /*var ele = document.getElementById('uploadNo');
        ele.value = upload_number;*/
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


<form name="drupal_form" action="index.php?q=work/save" method="post" enctype="multipart/form-data" onsubmit="modifyUploadNo()">
    <table>
        <tr>
            <td valign="top">
                <label for="description1">Description</label>
            </td>
            <td>
                <textarea id="description1" name="description1" rows="5" cols="20"><?php echo $row['description1'] ?></textarea>
            </td>
        </tr>

        

        <tr>
            <td></td>
            <td>
                <input type="submit" name="save" value="Save" />
            </td>
        </tr>
    </table>

    <script type="text/javascript">//<![CDATA[
      CKEDITOR.replace("description1", { "width": "600"});
      CKEDITOR.replace("description2", { "width": "600"});
    //]]></script>
    
    <input type="hidden" name="work_id" value="<?php echo $row['id'] ?>" />
    
</form>