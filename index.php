<?
  include_once('lib/setup.php');
  header('Content-Type: text/html; charset=UTF-8');
  
  $path = Page::getPath();

  if ($path == 'index' && !file_exists('pages/index') && file_exists('pages/help/index')) {
    header("Location: ?p=help");
    die;
  }

  $page = Page::create($path);
  $page->render();
?>
