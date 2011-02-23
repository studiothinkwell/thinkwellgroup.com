<?php
// $Id: template.php,v 1.16.2.2 2009/08/10 11:32:54 goba Exp $

/**
 * Sets the body-tag class attribute.
 *
 * Adds 'sidebar-left', 'sidebar-right' or 'sidebars' classes as needed.
 */
function phptemplate_body_class($left, $right) {
  if ($left != '' && $right != '') {
    $class = 'sidebars';
  }
  else {
    if ($left != '') {
      $class = 'sidebar-left';
    }
    if ($right != '') {
      $class = 'sidebar-right';
    }
  }

  if (isset($class)) {
    print ' class="'. $class .'"';
  }
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function phptemplate_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    return '<div class="breadcrumb">'. implode(' › ', $breadcrumb) .'</div>';
  }
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {  
  
   
  $vars['tabs2'] = menu_secondary_local_tasks();   
                  
    
     if( $_REQUEST["q"]  ) {
         
         $tblName = '';
         $tblField = 'title';  
         $tblShortUrl = 'redirect_url';    
         $type = trim($_REQUEST["type"]) ? trim($_REQUEST["type"]) :  ( trim($_REQUEST["news_id"]) ? trim($_REQUEST["news_id"])  : '' );
         
         switch(trim($_REQUEST["q"])) 
         {
           case 'newsdetail': 
                $tblName = 'drunews'; 
                $tblField = 'news_heading'; 
                break;
           case 'bios':
                $tblName = 'drubios';                
                break;
           case 'projectdetails':
                $tblName = 'druprojects';                
                break;
         
         }
         
         $sqlTitle = "select $tblField from $tblName where redirect_url = '".$type."'";
         $rsTitle = db_query($sqlTitle);
         $rowTitle = db_fetch_array($rsTitle);           
         if(count($rowTitle)) {            
             $itemTitle = trim($rowTitle[$tblField]) != '' ? $rowTitle[$tblField] : 'Page not found';                                   
             $vars['head_title'] = $itemTitle . " | " . variable_get('site_name', 'Drupal'); //$_SERVER['QUERY_STRING'];            
         }
     }
  
  

  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

/**
 * Add a "Comments" heading above comments except on forum pages.
 */
function garland_preprocess_comment_wrapper(&$vars) {
  if ($vars['content'] && $vars['node']->type != 'forum') {
    $vars['content'] = '<h2 class="comments">'. t('Comments') .'</h2>'.  $vars['content'];
  }
}

/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function phptemplate_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
  if ($language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "'. base_path() . path_to_theme() .'/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}
 