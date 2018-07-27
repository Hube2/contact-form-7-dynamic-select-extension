# Dynamic Select for Contact Form 7

# Adopt my Contact Form 7 (CF7) Plugins

I am putting my 3 CF7 plugins up for adoption.

The reasons for this are:
* I no longer use CF7 for my clients.
  * It is too difficult to use for my average client
  * It does not support the features my clients want
  * The authour of CF7 makes too many backwards compatibility breaking changes

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

## Quick Start

### Create your filter

**Example Filter:**

Here is an example filter for pulling in an `event` custom post type and then passing the events into the select dropdown. This filter is named `cf7_custom_get_events`, but you can name your filter whatever you want:

```
add_filter('cf7_custom_get_events', function($choices, $args = []){

    $events = get_posts("post_type=event&posts_per_page=-1");

	// Add an optional empty option
    $choices = [
        '' => '',
    ];

	// Populate our events into the choices array
	foreach($events as $event) {
		$event_name = $event->post_title;
		$choices[$event_name] = $event_name;
	}

	// Return our choices
    return $choices;
}, 10, 2);
```

### Adding in parameters

Let's use the filter from our previous example to get only the events of a custom taxonomy called `event_cat` by the term slug.

We would pass in the paramater through the form. **Do Not Include any extra spaces or quotes arround values, names, or the equals sign:**
```
cf7_custom_get_events event_cat_slug=concerts
```
You can pass any number of arguments to your filter. These parameters will be will be converted into an array:
```
cf7_custom_get_events event_cat_slug=concerts event_start_date=20180531
```
This would pass our filter an array (here we call it `$args`) that we can use to refine our query:
```
add_filter('cf7_custom_get_events', function($choices, $args = []){

	// Grab the parameters we passed down when we created the dynamic select field
	$event_category_slug = $args['event_cat_sug'];
	$event_start_date = $args['event_start_date'];

	$events = get_posts([
		'post_type' => 'event',
		'posts_per_page' => -1,
		'tax_query' => [
            [
                'taxonomy' => 'event_cat',
                'field' => 'slug',
                'terms' => $event_category_slug,
            ]
		],
        'meta_query' => [
            [
                'key' => 'start_date',
                'value' => $event_start_date,
                'compare' => '>='
            ]
        ],
	]);

	// Add an optional empty option
    $choices = [
        '' => '',
    ];

	// Populate our events into the choices array
	foreach($events as $event) {
		$event_name = $event->post_title;
		$choices[$event_name] = $event_name;
	}

	// Return our choices
    return $choices;
}, 10, 2);
```

Your filter must return an array. The array *must* be a list of "key" => "value" pairs.
