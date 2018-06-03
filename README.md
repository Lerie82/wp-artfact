![GitHub Logo](/images/logo.png)

# wp-artfact
This plugin will gather free articles from articlesfactory.com to help populate a demo wordpress theme with filler data.

### examples
```
//fill with info from url
$article = new Article();
$article->fillFromUrl("http://www.articlesfactory.com/articles/fitness/dream-wedding-dresses-at-affordable-rates-for-the-average-person.html");
```

```
//create a new one from the factory
//title, date, content
$article = ArticleFactory::create("test","test","test");
```

```
//search for articles
$article = new Article();
$article->search("dental");
$results = $article->getResults();
```

### install
To install the plugin either upload it via the plugins upload form or unzip it into your plugins directory. Currently there is no configuration needed.
