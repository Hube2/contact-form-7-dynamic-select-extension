<?php 

	/*
		Plugin Name: Contact Form 7 - Dynamic Select Extension
		Plugin URI: https://github.com/Hube2/contact-form-7-dynamic-select-extension
		Description: Provides a dynamic select field that accepts any shortcode to generate the select values. Requires Contact Form 7
		Version: 1.0.0
		Author: John A. Huebner II
		Author URI: https://github.com/Hube2/
		License: GPL
	*/
	
	// If this file is called directly, abort.
	if (!defined('WPINC')) { die; }
	
	include(dirname(__FILE__).'/cf7-dynamic-select-examples.php');
	
	new wpcf7_dynamic_select();
	
	class wpcf7_dynamic_select {
		
		public function __construct() {
			add_action('plugins_loaded', array($this, 'init'), 20);
		} // end public function __construct
		
		public function init() {
			if(function_exists('wpcf7_add_shortcode')){
				/* Shortcode handler */		
				wpcf7_add_shortcode('dynamicselect', array($this, 'shortcode_handler'), true);
				wpcf7_add_shortcode('dynamicselect*', array($this, 'shortcode_handler'), true);
			}
			add_filter('wpcf7_validate_dynamicselect', array($this, 'validation_filter'), 10, 2);
			add_filter('wpcf7_validate_dynamicselect*', array($this, 'validation_filter'), 10, 2);
			add_action('admin_init', array($this, 'add_tg_generator'), 25);
		} // end public function init
		
		public function shortcode_handler($tag) {
			// generates html for form field
			if (!is_array($tag)) {
				return '';
			}
			$name = $tag['name'];
			if (empty($name)) {
				return '';
			}
			$type = $tag['type'];
			$options = $tag['options'];
			$values = $tag['values'];
			$wpcf7_contact_form = WPCF7_ContactForm::get_current();
			
			$atts = '';
			$name_att = $name;
			$id_att = '';
			$class_att = '';
			$multiple_att = '';
			$tabindex_att = '';
		
			$class_att .= ' wpcf7-select';

			if ($type == 'dynamicselect*') {
				$class_att .= ' wpcf7-validates-as-required';
				$atts .= ' aria-required="true"';
			}
			
			$multiple = false;
			if (count($options)) {
				foreach ($options as $option) {
					if ($option == 'multiple') {
						$multiple_att = ' multiple="multiple"';
						$multiple = true;
					} elseif (preg_match('%^id:([-0-9a-zA-Z_]+)$%', $option, $matches)) {
						$id_att = $matches[1];
					} elseif (preg_match('%^class:([-0-9a-zA-Z_]+)$%', $option, $matches)) {
						$class_att .= ' '.$matches[1];
					} elseif (preg_match('%^tabindex:(\d+)$%', $option, $matches)) {
						$tabindex_att = intval($matches[1]);
					}
				} // end foreach options
			} // end if count $options
			
			if ($multiple) {
				$name_att .= '[]';
			}
			
			$atts .= ' name="'.$name_att.'"';
			if ($id_att) {
				$atts .= ' id="'.trim($id_att).'"';
			}
			if ($class_att) {
				$atts .= ' class="'.trim($class_att).'"';
			}
			if ($tabindex_att) {
				$atts .= ' tabindex-"'.$tabindex_att.'"';
			}
			$atts .= ' '.$multiple_att;
			
			$value = '';
			if (is_a($wpcf7_contact_form, 'WPCF7_ContactForm') && $wpcf7_contact_form->is_posted()) {
				if (isset($_POST['_wpcf7_mail_sent']) && $_POST['_wpcf7_mail_sent']['ok']) {
					$value = '';
				} else {
					$value = stripslashes_deep($_POST[$name]);
				}
			} else {
				if (isset($_GET[$name])) {
					$value = $_GET[$name];
				}
			}
			$filter = '';
			$filter_args = array();
			$filter_string = '';
			if (isset($values[0])) {
				$filter_string = $values[0];
			}
			if ($filter_string != '') {
				$filter_parts = explode(' ', $filter_string);
				$filter = trim($filter_parts[0]);
				$count = count($filter_parts);
				for($i=1; $i<$count; $i++) {
					if (trim($filter_parts[$i]) != '') {
						$arg_parts = explode('=', $filter_parts[$i]);
						if (count($arg_parts) == 2) {
							$filter_args[trim($arg_parts[0])] = trim($arg_parts[1], ' \'');
						} else {
							$filter_args[] = trim($arg_parts[0], ' \'');
						}
					} // end if filter part
				} // end for
			} // end if filter string
			if ($filter == '') {
				return $filter;
			}
			//$field_options = do_shortcode('['.$shortcode.']');
			//echo '<pre>'; print_r($field_options); echo '</pre>';
			
			$field_options = apply_filters($filter, array(), $filter_args);
			
			//echo 'FILTER: ',$filter.'<br />FILTER_ARGS:  <pre>'; print_r($filter_args); echo '</pre> FIELD_OPTIONS: <pre>'; print_r($field_options); echo '</pre>';
			
			//echo '<pre>'; print_r($field_options); echo '</pre>';
			
			if (!is_array($field_options) || !count($field_options)) {
				// filter did not return an array of values
				return '';
			}
			
			$validation_error = '';
			if (is_a($wpcf7_contact_form, 'WPCF7_ContactForm')) {
				$validation_error = $wpcf7_contact_form->validation_error($name);
			}
			//echo '<pre>'; print_r($wpcf7_contact_form); echo '</pre>';
			$invalid = 'false';
			if ($validation_error) {
				$invalid = true;
				$atts = ' aria-invalid="'.$invalid.'"';
			}
			
			
			ob_start();
			?>
				<span class="wpcf7-form-control-wrap <?php echo $name; ?>">
					<select <?php echo trim($atts); ?>>
						<?php 
							foreach ($field_options as $option_label => $option_value) {
								$option_value =  esc_attr($option_value);
								$option_label = esc_attr($option_label);
								?>
									<option value="<?php echo $option_value; ?>"<?php 
												if (!is_array($value) && $value == $option_value) {
													echo ' selected="selected"';
												} elseif (is_array($value) && in_array($option_value, $value)) {
													echo ' selected="selected"';
												}
											?>><?php echo $option_label; ?></option>
								<?php 
							} // end foreach field value
						?>
					</select>
					<?php echo $validation_error; ?>
				</span>
			<?php 
			$html = ob_get_clean();
			return $html;
		} // end public function shortcode_handler
		
		public function validation_filter($result, $tag) {
			// valiedates field on submit
			$wpcf7_contact_form = WPCF7_ContactForm::get_current();
			$type = $tag['type'];
			$name = $tag['name'];
			$result['valid'] = false;
			$result['reason'][$name] = $wpcf7_contact_form->message('invalid_required');
			if ($type != 'dynamicselect*') {
				return $result;
			}
			$value_found = false;
			if (isset($_POST[$name])) {
				$value = $_POST[$name];
				if (!is_array($value) && trim($value) != '') {
					$value_found = true;
				}
				if (is_array($value) && count($value)) {
					foreach ($value as $item) {
						if (trim($item) != '') {
							$value_found = true;
							break;
						}
					} // end foreach value
				} // end if array && count
			} // end if set
			if (!$value_found) {
				$result->invalidate($tag, wpcf7_get_message('invalid_required'));
			}
			return $result;
		} // end public function validation_filter
		
		public function add_tg_generator() {
			// called on init to add the tag generator or cf7
			// wpcf7_add_tag_generator($name, $title, $elm_id, $callback, $options = array())
			if (!function_exists('wpcf7_add_tag_generator')) {
				return;
			}
			$name = 'dynamicselect';
			$title = __('Dynamic Select field', 'wpcf7');
			$elm_id = 'wpcf7-tg-pane-dynamicselect';
			$callback = array($this, 'tg_pane_');
			wpcf7_add_tag_generator($name, $title, $elm_id, $callback);
		} // end public function add_tag_generator
		
		public function tg_pane_($form) {
			// not sure I understand why this is the callback and then we call the tag generator
			// I believe it is to dump the whatever is in $form
			$this->tg_pane('dynamicselect');
		} // end public function tag_pane_
		
		public function tg_pane($type='dynamicselect') {
			// output the code for CF7 tag generator
			?>
				<div id="wpcf7-tg-pane-<?php echo $type; ?>" class="hidden">
					<form action="">
						<table>
							<tr>
								<td>
									<input type="checkbox" name="required" />
									<?php echo esc_html(__('Required field?', 'wpcf7')); ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo esc_html(__( 'Name', 'wpcf7')); ?><br />
									<input type="text" name="name" class="tg-name oneline" />
								</td>
								<td></td>
							</tr>
						</table>
						<table>
							<tr>
								<td>
									<code>id</code> (<?php echo esc_html(__('optional', 'wpcf7')); ?>)<br />
									<input type="text" name="id" class="idvalue oneline option" />
								</td>
								<td>
									<code>class</code> (<?php echo esc_html(__('optional', 'wpcf7')); ?>)<br />
									<input type="text" name="class" class="classvalue oneline option" />
								</td>
							</tr>
							<tr>
								<td>
									<?php echo esc_html(__('Filter', 'wpcf7')); ?>
										(<?php echo esc_html(__('Filter','wpcf7' ) ); ?>)<br />
										<input type="text" name="values" class="oneline" />
										<?php echo esc_html(__('You can enter any filter. Use single quotes only. See docs &amp; examples.', 'wpcf7')); ?>
								</td>
								<td>
									<br />
									<input class="option" type="checkbox" name="multiple">
									Allow multiple selections?
								</td>
							</tr>
						</table>
						<div class="tg-tag">
							<?php echo esc_html(__('Copy this code and paste it into the form left.', 'wpcf7')); ?><br />
							<input type="text" name="<?php 
									echo $type; ?>" class="tag" readonly="readonly" onfocus="this.select()" />
						</div>
						<div class="tg-mail-tag">
							<?php echo esc_html(__('And, put this code into the Mail fields below.', 'wpcf7')); ?><br />
							<input type="text" class="mail-tag" readonly="readonly" onfocus="this.select()" />
						</div>
					</form>
				</div>
			<?php 
		} // end public function tag_pane
		
	} // end class cf7_dynamic_select
	
?>
