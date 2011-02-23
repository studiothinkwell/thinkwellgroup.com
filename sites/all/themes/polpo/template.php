<?php

function _polpo_fast_tasks() {
  	
  	global $user;

    $menu_tree[] = array('href' => 'node/add', 'title' => t('Create Content'));
    $menu_tree[] = array('href' => 'admin/content/node', 'title' => t('Manage Content'));
    $menu_tree[] = array('href' => 'admin/user/user', 'title' => t('Manage Users'));
    $menu_tree[] = array('href' => 'admin/settings/site-information', 'title' => t('Site Configuration'));
    $menu_tree[] = array('href' => 'admin/reports/status', 'title' => t('View Reports'));
    
	if ($menu_tree) {
		$output = '<div id="fast-tasks">';
		$output .= '<h4>Fast Tasks</h4>';
	    $output .= '<ul>';
	    $i=0;
	    foreach ($menu_tree as $key => $item) {
	      $id = ' id="fast-task-'.$i.'"';
	      $output .= '<li'. $id .'><a href="'. url($item['href']) .'">'. $item['title'] .'</a></li>';
	      $i++;
	    }
	    $output .= '</ul></div>';
  	}

  	return $output;
}


function polpo_preprocess_page(&$vars) {
	if (theme_get_setting('fast_tasks')) {
		$vars['fast_tasks'] = _polpo_fast_tasks(); 
	}

	if (theme_get_setting('title_text')) {
		if (theme_get_setting('title_text_custom')) {
			$vars['title_text'] = theme_get_setting('title_text_custom');
		} else {
			$vars['title_text'] = variable_get('site_name', 'drupal');
		}
	}

	// Hook into color.module
	
	
	if (module_exists('color')) {
		_color_page_alter($vars);
	}
}

/*
function polpo_help($path, $arg) {
	switch ($path) {
		case 'admin':
			$output = t('this is polpo help text');
			return $output;
	}


}
*/