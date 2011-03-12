<?
  include_once('lib/setup.php');

  $path = Page::getPath();

  if ($path == 'index' && !file_exists('pages/index') && file_exists('pages/help/index')) {
    header("Location: ?p=help");
    die;
  }

  $page = Page::create($path);
  $page->render();
?>
