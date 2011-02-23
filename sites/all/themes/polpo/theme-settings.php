<?php

function polpo_settings($saved_settings) {
	$settings = theme_get_settings('polpo');
	$defaults = array(
		'fast_tasks' => 1 ,
		'title_text' => 1 ,
		'title_text_custom' => "" /*,
		'fast_task_roles' => NULL,*/
	);
	$settings = array_merge($defaults, $settings);  
	$form['fast_tasks'] = array(
		'#type' => 'checkbox',
		'#title' => t('Enable Fast Tasks Menu'),
		'#description' => t('Check the box above to enable Polpo&rsquo;s Fast Tasks'),
		'#default_value' => $settings['fast_tasks'],
	);
	
	/*$roles = user_roles(FALSE);
	$form['fast_task_roles'] = array(
	  '#type' => 'select',
	  '#options' => $roles,
	  '#attributes' => array('multiple' => 'multiple'),
	  '#description' => t('Select which roles can use the Fasts Tasks Menu'),
	);*/

  	$form['title_text'] = array(
		'#type' => 'checkbox',
		'#title' => t('Enable Title Text'),
		'#description' => t('Uncheck the box above to disable the site name in the title bar'),
		'#default_value' => $settings['title_text'],
	);
   
  	$form['title_text_custom'] = array(
		'#type' => 'textfield',
		'#title' => t('Custom Title Text'),
		'#description' => t('Enter custom text to appear in the title bar instead of the site name'),
		'#default_value' => $settings['title_text_custom'],
	);

  return $form;
}
