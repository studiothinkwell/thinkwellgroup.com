<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success')
    {

?>
        <div class="">
            Process page saved successfully.
        </div>
<?php
    }
	
 if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'successdelete')
    {

?>
        <div class="">
            Process page deleted successfully.
        </div>
<?php
    }
?>

<div class="project_list">
    <form name="adminForm" method="get" action="index.php">
        <input type="hidden" name="q"  value="process/new" />
        <?php
            $query = " SELECT id , `title` FROM druprocess ";
            $result = db_query($query);

            $r = db_fetch_array($result);

            if($r)
            {
                $result = db_query($query);
        ?>
                <select name="process_id">
                    <?php
                        $processId = projects_get($_REQUEST , 'process_id' , '' );
                        while( $procecess = db_fetch_array($result))
                        {
                            if($processId== $procecess['id'] )
                            {
                    ?>
                            <option selected value="<?php echo $procecess['id'] ?>"><?php echo $procecess['title'] ?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                            <option value="<?php echo $procecess['id'] ?>"><?php echo $procecess['title'] ?></option>
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
        <a href="index.php?q=process/new">Create new</a>
    </div>
</div>