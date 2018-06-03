jQuery(document).ready(function($)
{
	//
});

function addPost(b)
{
	jQuery.post(myScript.pluginsUrl+"/wp-artfact/artfact.php?page=add_post", { url: b.value }).done(function(data)
	{
		alert(data);
	});
}
