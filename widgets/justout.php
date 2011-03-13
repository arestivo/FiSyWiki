<?php
  class JustOut extends Widget {
    public function getFileLastModified($file){
        if (!is_dir($file)) return filemtime($file);
        else {
            $lm = filemtime($file);
            if ($dh = opendir($file)) {
        	while (($f = readdir($dh)) !== false) {
        	  if ($f != '..' && $f != '.') {
        	    $lm = max ($this->getFileLastModified($file . '/' .$f), $lm);
        	  }
        	}
	        closedir($dh);
	    }
        }
        return $lm;
    }

    public function render() {
      $url = $this->getParam('url', null);
      $path = Content::getCanonicalURL($url, $this->path);
        
      if (file_exists('pages/'.$path)) $file = 'pages/'.$path;
      else if (file_exists('pages/'.$path.'/index')) $file = 'pages/'.$path.'/index';
      else $file = false;
        
      if ($file) {
        $lm = $this->getFileLastModified($file);
        $span = $this->getParam('span', '1 day');
        $keyword = $this->getParam('keyword', 'new');
        $span = strtotime('now +' . $span) - strtotime('now');
        $now = strtotime('now');
        if ($now - $lm < $span) return '<span class="justout">'.$keyword.'</span>';
      }
    }
  }
?>
