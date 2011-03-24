<?

  class Breadcrumbs extends Widget {
    public function render() {
      $separator = $this->getParam('separator', '>');
      
      $path = $this->path;

      $navigation = '<a href=".">'.$this->getParam('home','Home').'</a> ';

      while (true) {
        $pos = strpos($path, '/');
        if ($pos) {
          $page = substr($path, 0 , $pos);
          $path = substr($path, $pos + 1); 
        } else $page = $path;
        
        if ($page == 'index') break;

        if (isset($navigation)) $navigation .= ' ' . $separator . ' ';
        else $navigation = '';

        if (isset($cpath)) $cpath .= '/';
        else $cpath = '';
        $cpath .= $page;
  
        if ((file_exists('pages/' . $cpath . '/index') && !is_dir('pages/' . $cpath)) || 
             file_exists('pages/' . $cpath . '/index'))
          $navigation .= '<a href="?p='.$cpath.'">'.$this->getFileName($page).'</a> ';
        else
          $navigation .= $this->getFileName($page);
        if (!$pos) break;
      }
      return $navigation;
    }

    private function getFileName($file ) {
        if (strpos($file,'-')) return ucfirst(substr($file, strpos($file,'-') + 1));
        else return ucfirst($file);
    }
        
    protected function getDefaultParam() {
      return 'separator';
    }
  }
  
?>
