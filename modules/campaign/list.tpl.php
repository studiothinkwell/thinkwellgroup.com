<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
 global $base_url;

?>
<div id="campaign_list_container">
<?php if(isset($_REQUEST['msg'])&& $_REQUEST['msg'] == "success")
{
    echo '<div style="width:100%; color:green; text-align:center; background-color:white;"> Your campagin has been saved successfully </div>'    ;
}
?>
<?php if(isset($_REQUEST['msg'])&& $_REQUEST['msg'] == "successdelete")
{
    echo '<div style="width:100%; color:green; text-align:center; background-color:white;"> Your campagin has been deleted successfully </div>'    ;
}
?>

<div id="create_new_link">
    <a href="index.php?q=campaign/new"><strong>Create new</strong></a>
</div>

<form name="adminForm" method="get" action="<?php echo $base_url; ?>/campaign/new">
    <!--<input type="hidden" name="q"  value="campaign/new" />-->
    <select name="campaign_id">
        <?php
            $query = " SELECT id , campaign_title FROM campaign ";
            $result = db_query($query);
            while( $campaign = db_fetch_array($result))
            {
        ?>
                <option value="<?php echo $campaign['id'] ?>"><?php echo $campaign['campaign_title'] ?></option>
        <?php
            }
        ?>
    </select>
    <input type="submit" name="view" value="View" />
</form>

</div>