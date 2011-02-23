<?php	
  	if(isset($_REQUEST['msg']))
  	{
?>
	<div class="error"><?php echo $_REQUEST['msg'] ?></div>
<?php
  	}

	if(isset($_REQUEST['success']))
  	{
?>
	<div class=""><?php echo "Saved Successfully !" ?></div>
<?php
  	}
?>
<script type="text/javascript" src="editor/ckeditor.js"></script>
<style type="text/css">
	.textCounter
	{
    	background: #EEEEEE;
    	width: 40px;
    	text-align: center;
    	float: left;
    	margin: 0 0 0 5px;
	}
	
	.textCounter_text
	{
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
            servicePerformedNumber++;
        }
        else
		{
            
        }
    }

    function modifyUploadNo()
    {
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

<form name="drupal_form" action="index.php?q=page_background/save" method="post" enctype="multipart/form-data" onsubmit="return modifyUploadNo()">
    <table>
    	<?php
        	$query = " SELECT * FROM drupage_backgrounds  WHERE page = 'ourwork' ";
			$result = db_query($query);
			$row = db_fetch_array($result);
		?>
        <tr>
            <td valign="top"><label for="ourwork">Our work</label></td>
            <td>
                <input id="ourwork" type="file" class="input_box" name="ourwork" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'ourprocess' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
        <tr>
            <td><label for="ourprocess">Our process</label></td>
            <td>
                <input id="ourprocess" type="file" class="input_box" name="ourprocess" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>       
        <tr>
            <td><label for="ourpeople">Our people</label></td>
            <td>
                <input id="ourpeople" type="file" class="input_box" name="ourpeople" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr> 
		<?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'aboutus' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="aboutus">About Us</label></td>
            <td>
                <input id="aboutus" type="file" class="input_box" name="aboutus" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
		<?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'contactus' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="contactus">Contact Us</label></td>
            <td>
                <input id="contactus" type="file" class="input_box" name="contactus" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
	
        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'careers' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="careers">Careers</label></td>
            <td>
                <input id="careers" type="file" class="input_box" name="careers" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
          <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'newsroom' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="newsroom">News Room</label></td>
            <td>
                <input id="newsroom" type="file" class="input_box" name="newsroom" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'termsnconditions' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="termsnconditions">Terms & Conditions</label></td>
            <td>
                <input id="termsnconditions" type="file" class="input_box" name="termsnconditions" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'privacypolicy' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="privacypolicy">Privacy Policy</label></td>
            <td>
                <input id="privacypolicy" type="file" class="input_box" name="privacypolicy" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>

        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'search' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="search">Search</label></td>
            <td>
                <input id="search" type="file" class="input_box" name="search" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'pagenotfound' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="search">Page Not found</label></td>
            <td>
                <input id="pagenotfound" type="file" class="input_box" name="pagenotfound" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
        <?php
            $query = " SELECT * FROM drupage_backgrounds  WHERE page = 'trademark' ";
            $result = db_query($query);
            $row = db_fetch_array($result);
        ?>
 		<tr>
            <td valign="top"><label for="search">Trademark</label></td>
            <td>
                <input id="trademark" type="file" class="input_box" name="trademark" value="<?php echo $row['background'] ?>" maxlength="64" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><img height="100" width="100" src="misc/page_background/<?php echo $row['background'] ?>" /></td>
        </tr>
        <tr> 
          	<td></td>
            <td><input type="submit" name="save" value="Save" /></td>
        </tr>
    </table>    
</form>