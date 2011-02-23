<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
global $base_url;


    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success')
    {

?>
        <div class="">
            Bios saved successfully.
        </div>
<?php
    }
	   if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'successdelete')
    {

?>
        <div class="">
            Bios deleted successfully.
        </div>
<?php
    }

?>

<div class="project_list">
    <form name="adminForm" method="get" action="<?php echo $base_url; ?>/bios/new">
        <!--<input type="hidden" name="q"  value="bios/new" />-->
        <?php
            $query = " SELECT id , `title` FROM drubios ";
            $result = db_query($query);

            $r = db_fetch_array($result);

            if($r)
            {
                $result = db_query($query);
        ?>
                <select name="bios_id">
                    <?php
                        $projectId = bios_get($_REQUEST , 'bios_id' , '' );
                        while( $projects = db_fetch_array($result))
                        {
                            if($projectId == $projects['id'] )
                            {
                    ?>
                            <option selected value="<?php echo $projects['id'] ?>"><?php echo $projects['title'] ?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                            <option value="<?php echo $projects['id'] ?>"><?php echo $projects['title'] ?></option>
                    <?php
                            }
                        }
                    ?>
                </select>
                <input type="submit" name="view" value="View" />
        <?php
            }
        ?>
    </form>

    <div>
        <a href="<?php echo $base_url; ?>/bios/new">Create new</a>
    </div>
</div>