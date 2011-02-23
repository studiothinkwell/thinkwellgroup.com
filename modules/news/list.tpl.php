<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success')
    {

?>
        <div class="">
            News page saved successfully.
        </div>
<?php
    }
	   if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'successdeletd')
    {

?>
        <div class="">
            News page deleted successfully.
        </div>
<?php
    }
?>

<div class="project_list">
    <form name="adminForm" method="get" action="index.php">
        <input type="hidden" name="q"  value="news/new" />
        <?php
            $query = " SELECT id , `news_heading` FROM drunews ";
            $result = db_query($query);

            $r = db_fetch_array($result);

            if($r)
            {
                $result = db_query($query);
        ?>
                <select name="news_id">
                    <?php
                        $projectId = news_get($_REQUEST , 'news_id' , '' );
                        while( $news = db_fetch_array($result))
                        {
                            if($projectId == $news['id'] )
                            {
                    ?>
                            <option selected value="<?php echo $news['id'] ?>"><?php echo $news['news_heading'] ?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                            <option value="<?php echo $news['id'] ?>"><?php echo $news['news_heading'] ?></option>
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
        <a href="index.php?q=news/new">Create new</a>
    </div>
</div>