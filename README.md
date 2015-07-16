# WordpressToLaravel

This package syncs post data from a wordpress.com blog to Laravel (see note at bottom for self-hosted wordpress)

## Table of Contents

* [Version Compatibility](#version-compatibility)
* [Install](#install)
* [Setup](#setup)
* [Config Options](#config-options)
* [Adding As a Job](#adding-as-a-job)

## Version Compatibility

Compatibilite on Laravel 5.* (untested on 4, but should work)

## Install

Add the following to your composer.json file:

````bash
"jasonherndon/wptolaravel": "~1.0",
````

Run composer update.


## Setup

We'll need to move the configuration file out of the package folder and into your config folder. So run the following:

````bash
php artisan vendor:publish
````


## Config Options

Inside of the wptolaravel.php config file, you'll want to change the following:

_ENDPOINT_
Add your blog url here. For the given url, http://www.yoursite.wordpress.com, you'd just add:

````bash
	'endpoint' 				=> 'yoursite',
````

_AUTHORLOOKUP_
The first field is the database field you want to lookup by on your user model. The second is the field that you want to use
from Wordpress. So if I wanted to lookup by username, I might do:

````bash
	'lookup_my_author_by'	=> 'username',
	'lookup_post_author_by'	=> 'login',
````

But if I wanted to lookup by id, I might do:

````bash
	'lookup_my_author_by'	=> 'wordpress_id',
	'lookup_post_author_by'	=> 'id',
````

The next set of options gives you the ability to specify where the information coming from Wordpress will write to your post
model on your database. 

_NOTE_: You are going to need to make sure that your posts table has a field for each of these values.

````bash
	'destination' => [
		'posts_table' 	=> 'posts', // this is the table on your database where posts are stored
		'wp_id'			=> 'wp_id', // this is a way of storing the blog id from wordpress
		'title' 		=> 'title', // the title of the post
		'slug' 			=> 'slug', // the slug
		'content'		=> 'content', // the blog content
		'excerpt'		=> 'excerpt', // the blog excerpt
		'featured_img'	=> 'featured_img', // url of the image
		'status'		=> 'status', // visibility status
		'author'		=> 'author_name', // author id or name
		'published'		=> 'created_at', // blog creation date
		'updated'		=> 'updated_at', // blog last modified date
	]
````


## Adding As a Job

Ideally, this is something that runs in the background. To get that setup, just do:

````bash
	php artisan make:console RunWordpressSync
````

Then go to the file app/Console/Commands directory and open the RunWordpressSync file. At the top, include:

````bash
	use JasonHerndon\WPToLaravel\WPToLaravel;
````

Give the command a name like:

````bash
	protected $name = 'syncwordpress';
````

Then change the fire() method to:

````bash
	public function fire(WPToLaravel $wptolaravel)
	{
		$wptolaravel->syncPostsWithDatabase();
	}
````

This means you can fire the task at any time by running:

````bash
	php artisan syncwordpress
````

To schedule it to run (at whatever intervel you'd like), just return to app/Console/Kernel.php and add the task:

````bash
	$schedule->command('syncwordpress')->everyFiveMinutes();
````

For more info on scheduling, see: [Laravel Docs](http://laravel.com/docs/master/scheduling)


## On Self-Hosted Wordpress Installs
I'm working on getting this up for self-hosted Wordpress sites, and would welcome any help there.
The big issue in doing so has been trying to find an implementation that did not require any changes
to be made on the server which is hosting the wordpress site. I host my WP sites where I have root
access to the server, but I would love an implementation that would be of benefit to all those
who perhaps do not as well.

Be excellent to one another.