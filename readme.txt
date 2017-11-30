=== Dynamic Select for Contact Form 7 ===
Contributors: Hube2
Tags: contact form 7 dynamic select drop down menu
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 2.0.3
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=hube02%40earthlink%2enet&lc=US&item_name=Donation%20for%20WP%20Plugins%20I%20Use&no_note=0&cn=Add%20special%20instructions%20to%20the%20seller%3a&no_shipping=1&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Dynamic Select Fields in Contact Form 7.


== Description ==

Create dynamic select fields in contact form 7. Requires Contact Form 7.

Field values of dynamic select field are populated by using filters.


How To Use
----------

1) Create a filter to be called from your CF7 Dynamic Select Field.

Example Filter:

`function cf7_dynamic_select_do_example1($choices, $args=array()) {
	// this function returns an array of 
	// label => value pairs to be used in
	// a the select field
	$choices = array(
		'-- Make a Selection --' => '',
		'Choice 1' => 'Choice 1',
		'Choice 2' => 'Choice 2',
		'Choice 3' => 'Choice 3',
		'Choice 4' => 'Choice 4',
		'Choice 5' => 'Choice 5'
	);
	return $choices;
} // end function cf7_dynamic_select_do_example1
add_filter('wpcf7_dynamic_select_example1', 
             'cf7_dynamic_select_do_example1', 10, 2);`

2) Enter the filter name and any arguments into the Filter Field when adding a Dynamic Select Field.
For example, if we need to supply a term_id so that the filter can get the posts in a category the
filter value entered would look something like this:

`my-filter term_id=9`

***Do Not Include any extra spaces or quotes arround values, names or the =***

You can pass any number are arguments to your filter and they will be converted into an array. For example the
following:

`my-filter product-type=101 brand=500`

This will call the function assocaited with the filter hook 'my-filter' with an arguments the argument array of:
`$args = array(
    'product-type' => 101,
    'brand'        => 500
)`

Your filter must return an array. The array must be a list of "Label" => "Value" pairs.
For more information see the example in cf7-dynamic-select-examples.php included in the plugin folder.

[Also on GitHub](https://github.com/Hube2/contact-form-7-dynamic-select-extension)

== Installation ==

1. Upload the files to the plugin folder of your site
2. Activate it from the Plugins Page


== Screenshots ==

1. Create Dynamic Select Field


== Frequently Asked Questions ==

= Why Filters? =

Many other plugins of this type use shortcodes. I'm not a real fan of shortcodes, but that's not the only
reason. 

Filters are much more flexible that shortcodes. 

For example, a shortcode cannot return an array. A shortcode pretty much requires that only a text value is returned.


== Changelog ==

= 2.0.3 =
* correct fatal error called to undefined function when CF7 in not active.

= 2.0.2 =
* corrected to work with CF7 >= 4.8

= 2.0.1 =
* corrected fatal function call error due to update in CF7

= 2.0.0 =
* Replaced depricated CF7 function calls

= 1.2.1 =
* corrected possible fatal error from duplicate function

= 1.2.0 =
* Added default value logic
* Corrected Bug: allow multiple not working when inserting new field

= 1.1.3 =
* Corrected PHP notice "Indirect modification of overloaded element of WPCF7_Validation has no effect"

= 1.1.2 =

* Fix validation error removing other attributes, thanks pjgalbraith
* Changed name of plugin to meet new WP guidelines

= 1.1.1 = 

* Corrected a bug, incorrect field type name when creating new field, introduced in 1.1.0

= 1.1.0 =

* Updated tag pane to be completely compatible w/ CF7 >= V4.2
* Backwards compatible w/ CF7 < V4.2

= 1.0.2 =

* updated to work with CF7 V4.2 (tag pane still needs some work to make it 100% but it can be used and it will still work with previous versions of CF7)

= 1.0.1 =

* Preserve $_GET value - If the value of the field is present in query string, preset the selected values.

= 1.0.0 =

* initial release