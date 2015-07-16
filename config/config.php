<?php

/*
 * This file is part of WPToLaravel.
 *
 * (c) Jason Herndon <jason@icaruscreative.org> <twitter.com/jasononjourney>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

	/*
	|--------------------------------------------------------------------------
	| Note On Url Style Options
	|--------------------------------------------------------------------------
	|
	| Similiar to the Wordpress option, let the script know how you want your
	| images styled. Do not change these unless you're modifying the package. 
	| I include them here as an fyi. You have to modify the package to change
	| how these behave if that's what you'd like to do:
	| 
	| Can be any of the following choices:
	|
	| none = just use the file_storage_root path (Ex: yoursite.com/public/img.jpg)
	| date = Like the default Wordpress option (Ex: yoursite.com/public/2015/05/img.jpg)
	| slug = Uses built in Laravel slug option (Ex: yoursite.com/public/your-new-post/img.jpg).
	| id = Uses the id for each blog post by id (Ex: yoursite.com/public/324/img.jpg)
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Meta
	|--------------------------------------------------------------------------
	*/
	'endpoint' 				=> '',
	'lookup_my_author_by'	=> 'nickname',
	'lookup_post_author_by'	=> 'login', // Choose: email (may not be public), login (recommended), nice_name, id
	

	/*
	|--------------------------------------------------------------------------
	| Destination Database Settings
	|--------------------------------------------------------------------------
	|
	| These are the fields and model information that the content from your
	| Wordpress site will dump into. Feel free to change these to whatever
	| best suits your need. Please note that the keys from the Source
	| settings should match the keys here. So I don't recommend that
	| you change them, but if you do, just make sure they match.
	|
	*/
	'destination' => [
		'posts_table' 	=> 'posts',
		'wp_id'			=> 'wp_id',
		'title' 		=> 'title',
		'slug' 			=> 'slug',
		'content'		=> 'content',
		'excerpt'		=> 'excerpt',
		'featured_img'	=> 'featured_img',
		'status'		=> 'status',
		'author'		=> 'author_name',
		'published'		=> 'created_at',
		'updated'		=> 'updated_at',
	]

];
