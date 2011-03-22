<?

  class Toc extends Widget {
    public function render() {
      $path = $this->path;
      $page = substr($path, strrpos($path, '/') + 1);
      $path = substr($path, 0, strrpos($path, '/'));

      $toc = '';

      $handle = opendir('pages/' . $path);

      $files = array();      
      while (($file = readdir($handle))) {
        if (!is_dir('pages/' . $path . '/' . $file) && ($file != $page))
	  $files[] .= $file;
      }
      
      asort($files);
      foreach ($files as $file)
        $toc .= '<li><a href="?p='.$path.'/'.$file.'">'.ucfirst($file).'</a></li>';

      return '<ul>'.$toc.'</ul>';
    }
        
    protected function getDefaultParam() {
      return null;
    }
  }
  
?>
