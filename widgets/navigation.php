<?

  class Navigation extends Widget {
    public function render() {
      $start = $this->getParam('start', '');
      $index = $this->getParam('index', 'index');
    
      $path = $this->path;
      $page = substr($path, strrpos($path, '/') + 1);
      $path = substr($path, 0, strrpos($path, '/'));

      $navigation = '';

      $handle = opendir('pages/' . $path);

      $files = array();      
      while (($file = readdir($handle))) {
        if (!is_dir('pages/' . $path . '/' . $file) && substr($file, 0, strlen($start)) == $start && $file != $index && (!strpos($file,'.')))
	  $files[] .= $file;
      }

      sort($files);
      foreach ($files as $f => $file) {
        if ($f != 0 && $file == $page)
          $navigation .= '<a href="?p='.$path.'/'.$files[$f-1].'">&lt; '.Page::getFileName($files[$f-1]).'</a>';
        if ($f != 0 && $files[$f-1] == $page) {
          if ($navigation != '') $navigation .= ' | ';
          $navigation .= '<a href="?p='.$path.'/'.$file.'">'.Page::getFileName($file).' &gt;</a>';
        }
      }

      return $navigation;
    }
        
    protected function getDefaultParam() {
      return 'start';
    }
  }
  
?>
