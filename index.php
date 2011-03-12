<?
  include_once('lib/setup.php');

  $path = Page::getPath();
  $page = Page::create($path);
  $page->render();
?>
