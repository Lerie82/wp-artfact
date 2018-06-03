<?php
class Article
{
	private $title;
	private $date;
	private $content;
	private $results = array();
	private $maxPages = 0;
	private $url;

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

	function getMaxPages()
	{
		return $this->maxPages;
	}

    function fillFromUrl($u)
    {
        $f = file_get_contents($u);

        preg_match_all("/<title>(.+)<\/title>/", $f, $matches, PREG_PATTERN_ORDER);
        preg_match_all("/\s+<p><b>(.*)<\/b><\/p>/s", $f, $matches3, PREG_PATTERN_ORDER);
        preg_match_all("/<p>(.+?)<\/p>/s", $f, $matches4, PREG_PATTERN_ORDER);
        preg_match_all("/<a class=\"small-link\" href=\"(http[:]\/\/www[.]articlesfactory[.]com\/articles\/day\/\d{4}[-]\d{1,2}[-]\d{1,2}[.]html)/", $f, $matches2, PREG_PATTERN_ORDER);

        $content = filter_var($matches4[1][0], FILTER_SANITIZE_STRING);

	for($i=0;$i<count($matches4[0]);$i++)
	{
		$content .= $matches4[1][$i];
	}

		$this->title = $matches[1];
		$this->date = $matches2[1];
		$this->content = $content;
		$this->url = $u;
    }

	function search($query)
	{
		$url = "http://www.articlesfactory.com/search/".urlencode($query);
		$s = file_get_contents($url);

		//match the date, partial content, title
		preg_match_all("/(\w+\s\d{1,2}[,]\s\d{4})<\/a>/s", $s, $date, PREG_PATTERN_ORDER);
		preg_match_all("/<div>(.+?)<\/div>\s/", $s, $content, PREG_PATTERN_ORDER);
		preg_match_all("/<a\sclass[=]\"h2\"\shref[=]\".+?\">(.+?)<\/a>/", $s, $title, PREG_PATTERN_ORDER);
		preg_match_all("/<a class=\"small-link\" href=\"(http[:]\/\/www[.]articlesfactory[.]com\/articles\/day\/\d{4}[-]\d{1,2}[-]\d{1,2}[.]html)/", $s, $url, PREG_PATTERN_ORDER);
            
		//get last page number
        preg_match_all("/\/page(\d{1,3})[.]html\">Last/", $f, $matches5, PREG_PATTERN_ORDER);
        $this->maxPages = $matches5[0];

		for($i=0;$i<=10;$i++)
		{
			$d = array("date"=>$date[0][$i], "title"=>$title[0][$i], "content"=>$content[0][$i], "url"=>$url[1][$i-1]);
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
