<?php

namespace JasonHerndon\WPToLaravel;

use Illuminate\Support\Collection;
use GuzzleHttp;
use Exception;
use Config;
use Log;
use DB;

class WPToLaravel
{

    /**
     * Setup the WPToLaravel instance
     * 
     * @return void
     * @link http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.getPosts
     */
    public function __construct()
    {
	    $this->client = new \GuzzleHttp\Client();
	    $this->endPoint = Config::get('wptolaravel.endpoint') . '.wordpress.com';
	    $this->destinationPostsTable = Config::get('wptolaravel.destination.posts_table');

	    $this->lookupMyAuthorBy = Config::get('wptolaravel.lookup_my_author_by');
	    $this->lookupPostAuthorBy = Config::get('wptolaravel.lookup_post_author_by');
    }
 
    /**
     * Sync the posts, create or update
     * 
     * @return void
     */
	public function syncPostsWithDatabase()
	{
		// try to get the posts off of the response
		try {
			
			$response = $this->getPosts();
			if ($response) 
			{
				$posts = $response['posts'];	
				if ($posts)
				{
					foreach ($posts as $post){

						// Sync the authors
						$author = DB::table('users')->where($this->lookupMyAuthorBy, $post['author'][$this->lookupPostAuthorBy])->first();

						if ($author){
							$postAuthor = $author->id;
						} else {
							$postAuthor = $post['author']['ID'];	
						}

						// LOOK UP POST IN DATABASE
						$lookupPost = DB::table($this->destinationPostsTable)->whereSlug($post['slug'])->first();

						// IF FOUND
						if ($lookupPost)
						{
							// UPDATE
							DB::table($this->destinationPostsTable)->whereSlug($post['slug'])->update([
						    	Config::get('wptolaravel.destination.title') => $post['title'],
						    	Config::get('wptolaravel.destination.slug') => $post['slug'],
						    	Config::get('wptolaravel.destination.content') => $post['content'],
						    	Config::get('wptolaravel.destination.excerpt') => $post['excerpt'],
						    	Config::get('wptolaravel.destination.featured_img') => $post['featured_image'],
						    	Config::get('wptolaravel.destination.author') => $postAuthor,
						    	Config::get('wptolaravel.destination.published') => $post['date'],
						    	Config::get('wptolaravel.destination.updated') => $post['modified'],
					        ]);

						} else {
							// CREATE
							DB::table($this->destinationPostsTable)->insert([
							    [
							    	Config::get('wptolaravel.destination.title') => $post['title'],
							    	Config::get('wptolaravel.destination.slug') => $post['slug'],
							    	Config::get('wptolaravel.destination.content') => $post['content'],
							    	Config::get('wptolaravel.destination.excerpt') => $post['excerpt'],
							    	Config::get('wptolaravel.destination.featured_img') => $post['featured_image'],
							    	Config::get('wptolaravel.destination.author') => $postAuthor,
							    	Config::get('wptolaravel.destination.published') => $post['date'],
							    	Config::get('wptolaravel.destination.updated') => $post['modified'],
							    ],
							]);

						} // end if post
					} // end for each post
				} // end if posts
			} // end if response

		} catch (Exception $e) {
			// throw exception here
			Log::info('So, I have some bad news. That wordpress blog sync package failed. Here is the report. ' . $e);
			return 'So, I have some bad news. That wordpress blog sync package failed. Here is the report. ' . $e;
		}

		Log::info('Your Posts are now synced');
		return true;

	}


    /**
     * Setup the getPosts request
     * 
     * @return void
     */
	public function getPosts()
	{
	    $params = ['endpoint' => $this->endPoint];
	    return Collection::make($this->sendRequest('posts',$params));
	}

    /**
     * Send the request
     * 
     * @return json
     */
	public function sendRequest($requestname, array $params)
	{
		$results = $this->client->get('https://public-api.wordpress.com/rest/v1.1/sites/' . $params['endpoint'] . '/' . $requestname);
		if ($results)
		{
		    return json_decode($results->getBody(), true);
		} else {
			return json_decode($results=[], true);
		}
	}

}