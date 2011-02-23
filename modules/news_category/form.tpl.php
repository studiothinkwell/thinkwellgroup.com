<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump();
//var_dump($_SERVER);
//exit;
global $base_url;

$id = news_category_get($_REQUEST , 'news_category_id' , 0 );

  $query = " SELECT d.`category_id`, d.`category_name`, d.`category_background` FROM drunews_category d WHERE d.`category_id` = '$id' ";
  $result = db_query($query);
  $row = db_fetch_array($result);

  if(isset($_REQUEST['msg']))
  {
?>
<div class="error">
    <?php echo $_REQUEST['msg'] ?>
</div>
<?php
  }
?>

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

        var projTitle = document.getElementById("category_name");

        //var title0 = document.getElementById("serviceperformed_heading1");

        if(!projTitle.value.length)
        {
            alert("Please enter category title !");
            projTitle.style.border = "1px solid #FF0000";
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


<form name="drupal_form" action="index.php?q=news_category/save" method="post" enctype="multipart/form-data" onsubmit="return modifyUploadNo()">
    <table>
        <tr>
            <td valign="top">
                <label for="category_name">News Category Title</label>
            </td>
            <td>
                <input id="category_name" type="text" style="width:75%" name="category_name" value="<?php echo $row['category_name'] ?>" maxlength="64" />
            </td>
        </tr>

        <tr>
            <td valign="top"><label for="category_background">Category Background</label></td>
            <td>
                <input type="file" name="category_background" id="category_background" value="<?php echo $row['category_background']?>" />
            </td>
        </tr>        
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/news_category_background/<?php echo $row['category_background'] ?>" /></td>
        </tr>

        <tr>
            <td></td>
            <td><input type="submit" name="save" value="Save" /></td>
        </tr>
        
    </table>

    <input type="hidden" name="news_category_id" value="<?php echo $row['category_id'] ?>" />
    
</form>