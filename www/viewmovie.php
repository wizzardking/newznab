<?php

require_once("config.php");
require_once(WWW_DIR."/lib/page.php");
require_once(WWW_DIR."/lib/users.php");
require_once(WWW_DIR."/lib/movie.php");

$page = new Page;
$users = new Users;
$movie = new Movie;

if (!$users->isLoggedIn())
	$page->show403();
	
if (isset($_GET["id"]))
{
	$mov = $movie->getMovieInfo($_GET['id']);
	
	if (!$mov)
		$page->show404();
	
	$mov['actors'] = $movie->makeFieldLinks($mov, 'actors');
	$mov['genre'] = $movie->makeFieldLinks($mov, 'genre');
	$mov['director'] = $movie->makeFieldLinks($mov, 'director');
	
	$page->smarty->assign('movie', $mov);

	$page->title = "Info for ".$mov['title'];
	$page->meta_title = "";
	$page->meta_keywords = "";
	$page->meta_description = "";
	$page->smarty->register_modifier('ss', 'stripslashes');
	
	$modal = false;
	if (isset($_GET['modal'])) 
	{
		$modal = true;
		$page->smarty->assign('modal', true);
	}
	
	$page->content = $page->smarty->fetch('viewmovie.tpl');

	if ($modal)
		echo $page->content;
	else
		$page->render();
}

?>
