<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div id="campaign_list_container">
<div id="create_new_link">
    <a href="index.php?q=campaign/new"><strong>Create new</strong></a>
</div>

<form name="adminForm" method="get" action="index.php">
    <input type="hidden" name="q"  value="campaign/new" />
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