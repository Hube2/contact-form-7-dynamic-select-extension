Dynamic Select for Contact Form 7
---------------------------------------

# Adopt my Contact Form 7 (CF7) Plugins

I am putting my 3 CF7 plugins up for adoption.

The reasons for this are:
* I no longer use CF7 for my clients.
  * It is too difficult to use for my average client
  * It does not support the features my clients want
  * The authour of CF7 makes too many backwards compatibility breaking changes
  * I simply do not have time to keep up with maintenence for plugins I no longer use

These plugins do have users and are usefull for those that use CF7 and I'd rather someone else takes them over than let them die.

The 3 plugins involved are:
* At WP
  * [Dynamic Select for Contact Form 7](https://wordpress.org/plugins/contact-form-7-dynamic-select-extension/)
  * [Hidden Field for Contact Form 7](https://wordpress.org/plugins/contact-form-7-simple-hidden-field/)
  * [Dynamic Recipient for Contact Form 7](https://wordpress.org/plugins/contact-form-7-dynamic-mail-to/)
* On Github
  * [Dynamic Select for Contact Form 7](https://github.com/Hube2/contact-form-7-dynamic-select-extension)
  * [Hidden Field for Contact Form 7](https://github.com/Hube2/contact-form-7-simple-hidden-field)
  * [Dynamic Recipient for Contact Form 7](https://github.com/Hube2/contact-form-7-dynamic-mail-to)

These plugins were built to work togther
* The dynamic select to populate drop downs
* The dynamic recipient send emails to different people
* The hidden field to supply the fields that the recipient plugin needs to work

If you are interested in adopting all 3 of these plugins please open up an issue in any of the Github repos for any of these plugins. Below is how I would like to do this, if you have other ideas you can bring them up when you open the issue.

* You fork all of these repos
* You maintian, update and improve these plugins as needed an send me pull requests
* You take over supporting these plugins on Github and WP (answer questions etc)
* I will, for a short time continue to update the WP repo with your changes
* At some time in the future I will transfer ownership of the both the Github and WordPress repos to you.

Add dynamically generated select fields (Drop-down menus) to forms in Contact Form 7 using filters

How To Use
----------

1) Create a filter to be called from your CF7 Dynamic Select Field.

Example Filter:

```
function cf7_dynamic_select_do_example1($choices, $args=array()) {
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
add_filter('wpcf7_dynamic_select_example1', 'cf7_dynamic_select_do_example1', 10, 2)
```

2) Enter the filter name and any arguments into the Fitler Field when adding a Dynamic Select Field.
For example, if we need to supply a term_id so that the filter can get the posts in a category the
filter value entered would look something like this:
```
my-filter term_id=9
```

***Do Not Include any extra spaces or quotes arround values, names or the =***

You can pass any number are arguments to your filter and they will be converted into an array. For example the
following:
```
my-filter product-type=101 brand=500
```
This will call the function assocaited with the filter hook 'my-filter' with an arguments the argument array of:
```
$args = array(
    'product-type' => 101,
    'brand'        => 500
)
```

Your filter must return an array. The array must be a list of "Label" => "Value" pairs.
For mor information see the example in cf7-dynamic-select-examples.php
