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

class Article
{
	private $title;
	private $date;
	private $content;
	private $results = array();

	function __construct($t=null, $d=null, $c=null)
	{
		$this->title = $t;
		$this->date = $d;
		$this->content = $c;
	}

	function getTitle()
	{
		return $this->title;
	}

	function getDate()
	{
		return $this->date;
	}

	function getContent()
	{
		return $this->content;
	}

        function fillFromUrl($u)
        {
                $f = file_get_contents($u);

                preg_match_all("/<title>(.+)<\/title>/", $f, $matches, PREG_PATTERN_ORDER);
                preg_match_all("/\s+<p><b>(.*)<\/b><\/p>/s", $f, $matches3, PREG_PATTERN_ORDER);
                preg_match_all("/<p>(.+?)<\/p>/s", $f, $matches4, PREG_PATTERN_ORDER);
                preg_match_all("/<a class=\"small-link\" href=\"(http[:]\/\/www[.]articlesfactory[.]com\/articles\/day\/\d{4}[-]\d{1,2}[-]\d{1,2}[.]html)/", $f, $matches2, PREG_PATTERN_ORDER);
                $content = filter_var($matches4[1][0], FILTER_SANITIZE_STRING);

		$this->title = $matches[1];
		$this->date = $matches2[1];
		$this->content = $content;
        }

	function search($query)
	{
		$url = "http://www.articlesfactory.com/search/".urlencode($query);
		$s = file_get_contents($url);

		preg_match_all("/(\w+\s\d{1,2}[,]\s\d{4})<\/a>/s", $s, $date, PREG_PATTERN_ORDER);
		preg_match_all("/<div>(.+?)<\/div>\s/", $s, $content, PREG_PATTERN_ORDER);
		preg_match_all("/<a\sclass[=]\"h2\"\shref[=]\".+?\">(.+?)<\/a>/", $s, $title, PREG_PATTERN_ORDER);

		for($i=0;$i<=10;$i++)
		{
			$d = array("date"=>$date[0][$i], "title"=>$title[0][$i], "content"=>$content[0][$i]);
			array_push($this->results, $d);
		}
	}

	function getResults()
	{
		return $this->results;
	}
}

class ArticleFactory
{
	public static function create($t, $d, $c)
	{
		return new Article($t, $d, $c);
	}
}

//fill with info from url
//$article = new Article();
//$article->fillFromUrl("http://www.articlesfactory.com/articles/fitness/dream-wedding-dresses-at-affordable-rates-for-the-average-person.html");

//create a new one from the factory
//$article = ArticleFactory::create("test","test","test");

//search for articles
//$article = new Article();
//$article->search("dental");
//$results = $article->getResults();

?>
