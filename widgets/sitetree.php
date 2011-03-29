<?

  class Sitetree extends Widget {
    public function render() {
      return $this->getdir('pages/');
    }

    protected function getdir($dir, $level = 0) {
	$maxlevel = $this->getParam('level', -1);
        if ($maxlevel <> -1 && $level >= $maxlevel) return '';
    	$list = '<ul>';
    	if ($dh = opendir($dir)) {
    		while (($file = readdir($dh)) !== false) {
    		     if ($file == 'index') continue;
    		     if (strpos($file, '.') !== false) continue;
    		     if (strpos($file, '-blocked') !== false) continue;
    		     if ((!file_exists($dir . $file . '/index') && !is_dir($dir . $file)) || 
    		          file_exists($dir . $file . '/index'))
          		$name = '<a href="?p='.substr($dir.$file,strpos($dir, '/')+1).'">'.Page::getFileName($file).'</a>';
		     else
                        $name = Page::getFileName($file);

		     if (is_dir($dir . $file)) { 
			     $childs = $this->getdir($dir . $file . '/', $level + 1);
			     if (substr($name, 0, 1) == '<' || ($childs != '<ul></ul>' && $childs != ''))
        			     $list .= '<li>' . $name . '</li>' . $childs;
        	     } else $list .= '<li>' . $name . '</li>';
        	}
    	}
    	$list .= '</ul>';
    	return $list;
    }
        
    protected function getDefaultParam() {
      return 'level';
    }
  }
  
?>
