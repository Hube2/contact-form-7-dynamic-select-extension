# Dynamic Select for Contact Form 7

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
