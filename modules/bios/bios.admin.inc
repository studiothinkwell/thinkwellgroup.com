<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function bios_list(){
    ob_start();
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'list.tpl.php';
    $contents = ob_get_contents();
    ob_clean();

    return $contents;
}
?>
