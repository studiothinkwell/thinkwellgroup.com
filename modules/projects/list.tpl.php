<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success')
    {

?>
        <div class="">
            Project saved successfully.
        </div>
<?php
    }
	 if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'successdelete')
    {

?>
        <div class="">
            Project deleted successfully.
        </div>
<?php
    }
?>



<div class="project_list">
    <form name="adminForm" method="get" action="index.php">
        <input type="hidden" name="q"  value="projects/new" />
        <?php
            $query = " SELECT id , `title` FROM druprojects ";
            $result = db_query($query);

            $r = db_fetch_array($result);

            if($r)
            {
                $result = db_query($query);
        ?>
                <select name="project_id">
                    <?php
                        $projectId = projects_get($_REQUEST , 'project_id' , '' );
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
        <a href="index.php?q=projects/new">Create new</a>
    </div>
</div>