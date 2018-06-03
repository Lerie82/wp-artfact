<?php
/*
Plugin Name: WP Articles Factory
Plugin Uri: https://github.com/Lerie82/wp-artfact
Author: Lerie Taylor
Description: This plugin allows you to search, find and publish articles from Articlesfactory.com to help fill out a demo WordPress theme.
Version: 1.0
Author Uri: https://lerie.us/
License Uri: https://opensource.org/licenses/LGPL-3.0
License: LGPL3
*/

/*if(!defined('WPINC'))
{
	die(var_dump("direct access diabled"));
}*/

require_once("article.php");

$path = explode('/wp-content/', dirname(__FILE__));
//die(var_dump($path[0]));
require_once($path[0]."/wp-load.php");

$page = filter_var(@$_GET['page'], FILTER_SANITIZE_STRING);

switch($page)
{
	default: break;

	case "add_post":
		$url = filter_var(@$_POST['url'], FILTER_VALIDATE_URL);

		$article = new Article();
		$article->fillFromUrl($url);

		$post = array(
			'post_title' => $article->getTitle()[0],
			'post_content' => $article->getContent(),
			'post_status' => 'publish',
			'post_author' => 'ArticleFactory'
		);

		$id = wp_insert_post($post, true);
		die("Post added!");
	break;
}

function ad_load_scripts()
{
	wp_enqueue_script('custom-js', plugins_url('wp-artfact/js/artfact.js', dirname(__FILE__)));

	wp_localize_script('custom-js', 'myScript', array(
    		'pluginsUrl' => plugins_url(),
	));
}
add_action('admin_enqueue_scripts', 'ad_load_scripts');

function artfact_index_page_create()
{
	add_menu_page('Article Factory', 'Article Factory', 'edit_posts', 'artfact_index_page', 'artfact_index_display', '', 24);
}
add_action('admin_menu', 'artfact_index_page_create');

function artfact_index_display()
{
	//start wrap
	echo '<div class="wrap">';

	//if the form is submitted
    	if(isset($_POST['wpquery']))
	{
		//update the query
        	update_option('wpquery', $_POST['wpquery']);

		//search for articles
        	$article = new Article();
        	$article->search(get_option('wpquery'));
	        $results = $article->getResults();

		//show search results
		echo "<h2>Search results for: ".get_option('wpquery')."</h2>";

		echo '<table class="widefat">
			<tr>
				<th class="row-title">Date</th>
				<th class="row-title">Title/Link</th>
				<th class="row-title">Action</th>
			</tr>';

        	foreach($results as $result)
        	{
        		preg_match_all("/(http:\/\/www[.]articlesfactory[.]com\/articles\/.+)\"/", $result['title'], $nUrl);

        		echo '	<tr>
                		<td scope="row">
					<label for="tablecell">
						'.$result['date'].'
					</label>
				</td>
                		<td>'.$result['title'].'</td>
						<td><button onClick="addPost(this)" value="'.trim($nUrl[0][0],'"').'" class="button-secondary">Add Post</button></td>
        			</tr>';
	        }

		echo '</table>';

    	} else {
    		$value = get_option('wpquery', 'wpquery');
    		include 'search-form.php';
	}

	//end wrap
	echo '</div>';
}

?>
