<?php 
	
	// If this file is called directly, abort.
	if (!defined('WPINC')) { die; }
	
	/*
			This is an example of adding a shortcode for dynamic select using functions and hooks
	*/
	
	function cf7_dynamic_select_do_example1($choices, $args=array()) {
		// this function returns and array of label => value pairs to be used in the select field
		$choices = array(
			'-- Make a Selection --' => '',
			'Choice 1' => 'Choice 1',
			'Choice 2' => 'Choice 2',
			'Choice 3' => 'Choice 3',
			'Choice 4' => 'Choice 4',
			'Choice 5' => 'Choice 5',
			'default' => array('Choice 2', 'Choice 3')
		);
		return $choices;
	} // end function cf7_dynamic_select_do_example1
	add_filter('wpcf7_dynamic_select_example1', 'cf7_dynamic_select_do_example1', 10, 2);
	
?>