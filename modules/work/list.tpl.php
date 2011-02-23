<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success')
    {

?>
        <div class="">
            Work page Saved successfully.
        </div>
<?php
    }
?>

<div class="project_list">
    <form name="adminForm" method="get" action="index.php">
        <input type="hidden" name="q"  value="work/new" />
        <?php
            $query = " SELECT id  FROM druwork ";
            $result = db_query($query);

            $r = db_fetch_array($result);

            if($r)
            {
                $result = db_query($query);
        ?>
                <select name="work_id">
                    <?php
                        $workId = projects_get($_REQUEST , 'work_id' , '' );
                        while( $procecess = db_fetch_array($result))
                        {
                            if($workId== $procecess['id'] )
                            {
                    ?>
                            <option selected value="<?php echo $procecess['id'] ?>"><?php echo $procecess['id'] ?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                            <option value="<?php echo $procecess['id'] ?>"><?php echo $procecess['id'] ?></option>
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
        <a href="index.php?q=work/new">Create new</a>
    </div>
</div>