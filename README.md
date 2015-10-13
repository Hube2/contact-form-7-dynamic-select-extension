Dynamic Select for Contact Form 7
---------------------------------------

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
