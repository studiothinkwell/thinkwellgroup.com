<?php
// $Id: search-results.tpl.php,v 1.1 2007/10/31 18:06:38 dries Exp $

/**
 * @file search-results.tpl.php
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and
 * the child template are dependant to one another sharing the markup for
 * definition lists.
 *
 * Note that modules may implement their own search type and theme function
 * completely bypassing this template.
 *
 * Available variables:
 * - $search_results: All results as it is rendered through
 *   search-result.tpl.php
 * - $type: The type of search, e.g., "node" or "user".
 *
 *
 * @see template_preprocess_search_results()
 */
global $base_url;
?>
<dl class="search-results <?php print $type; ?>-results">
	<div class="left-pan"><?php print $search_results; ?></div>
	<div class="right-pan"><h1>THAT WHICH <br />YOU SEEK<br />IS HERE</h1></div>
</dl>
<?php //print $pager; ?>
<div style="clear: both;"></div>
<div id="" class="work-navigation search-navigation">
    <?php
    global $pager_page_array , $pager_total;
    $element = 0;
    $class = "";
    if ($pager_page_array[$element] == 0) {
        $class = "disabled";
    }
	if($pager_total[0]>1)
	{
    ?>
    <span id="prev" class="left-navigation <?php echo $class ?>">
        <?php
        if ($pager_page_array[$element] <= 0) {
        ?>
            <span><img src="<?php echo $base_url ?>/themes/garland/images/left-navigation.png" /></span>
        <?php
        }
        else{

            if($pager_page_array[$element] - 1 > 0)
                $page = "?page=".($pager_page_array[$element] - 1);
            else
                $page = "";
        ?>
            <a href="<?php echo $base_url."/".$_GET['q'] ?><?php echo $page ?>"><img src="<?php echo $base_url ?>/themes/garland/images/left-navigation.png" /></a>
        <?php
        }
        ?>
    </span>
    <span class="middle-navigator">
        <?php
            $p = $pager_page_array[$element]+1;
        ?>
        <span class="page-indicator"><?php echo ($p)." of ".$pager_total[$element] ?></span>
    </span>
    <?php
    $class = "";
    if($pager_page_array[$element] >= ($pager_total[$element]-1))
    {
        $class = "disabled";
    }
    ?>
    <span id="next" class="right-navigation <?php echo $class ?>">
    <?php
    if($pager_page_array[$element] >= ($pager_total[$element]-1))
    {
        
    ?>
        <span><img src="<?php echo $base_url ?>/themes/garland/images/right-navigation.png"></span>
    <?php
    }
    else{
    ?>
        <a href="<?php echo $base_url."/".$_GET['q'] ?>?page=<?php echo $pager_page_array[$element] + 1 ?>"><img src="<?php echo $base_url ?>/themes/garland/images/right-navigation.png"></a>
    <?php
    }
	
    ?>
    </span>
    <?php 
	}
	?>
</div>