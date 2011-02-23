<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success')
    {

?>
        <div class="">
            Project Saved successfully.
        </div>
<?php
    }
?>

<div class="project_list">
    <form name="adminForm" method="get" action="index.php">
        <input type="hidden" name="q"  value="project_category/new" />
        <?php
            $query = " SELECT `category_id` , `category_name` FROM druprojectcategory ";
            $result = db_query($query);

            $r = db_fetch_array($result);

            if($r)
            {
                $result = db_query($query);
        ?>
                <select name="project_category_id">
                    <?php
                        $projectId = project_category_get($_REQUEST , 'project_category_id' , '' );
                        while( $project_category = db_fetch_array($result))
                        {
                            if($projectId == $project_category['category_id'] )
                            {
                    ?>
                            <option selected value="<?php echo $project_category['category_id'] ?>"><?php echo $project_category['category_name'] ?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                            <option value="<?php echo $project_category['category_id'] ?>"><?php echo $project_category['category_name'] ?></option>
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
        <a href="index.php?q=project_category/new">Create new</a>
    </div>
</div>