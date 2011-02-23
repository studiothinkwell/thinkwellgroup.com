<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function page_background_page()
{
    ob_start();
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'form.tpl.php';
    $contents = ob_get_contents();
    ob_clean();

    return $contents;
}
?>
