<?
  class Content extends Widget {
    public function getCanonicalURL($url, $current){
         $pos = strrpos($current,'/');
         if (!$pos) $path = '';
         else $path = substr($this->path, 0, $pos + 1);
         
         if (substr($url,0,7) != 'http://' && substr($url,0,8) != 'https://') {
           if (substr($url,0,1) == '/')
             $path = substr($url,1);
           else
             $path = $path . $url;
           return $path;
         } else return $url;
    }

    public function render() {
      $content = file_get_contents('pages/' . $this->path);
      preg_match_all('/\[(.*?)\]\((.*?)\)/', $content, $matches);
      foreach($matches[0] as $id => $match) {
         $complete = $match;
         $title = $matches[1][$id];
         $url = $matches[2][$id];
         if (substr($url,0,7) != 'http://' && substr($url,0,8) != 'https://') {
           $pos = strrpos($this->path,'/');
           $path = $this->getCanonicalURL($url, $this->path);
           if (strpos($url,'.')) $prefix = "pages/"; else $prefix = '?p=';

           if (!((file_exists('pages/'.$path) && !is_dir('pages/'.$path)) || file_exists('pages/'.$path.'/index'))) 
              $class = "notfound";
	   else $class = "";

	   if ($class)
	     $content = str_replace($complete, '<span class="' . $class . '">' . "[$title]($prefix$path)</span>", $content);
	   else
	     $content = str_replace($complete, "[$title]($prefix$path)", $content);
	 }
      }
      
      $content = Markdown($content);
      while (true) {
        if (preg_match('/\[\[(.*)\]\]/', $content, $matches) == 0) break;
        $widget = $matches[1];
        $params = array();
    
        if (preg_match('/(.*)\|(.*)/', $widget, $matches) == 1) {
          $widget = $matches[1];
          $params = json_decode($matches[2], true);
        }

        $widget = Widget::create($widget, $params, $this->path);

        $content = preg_replace('/\[\[.*\]\]/', $widget->render(), $content, 1);    
      }
      return $content;
    }
  }
?>
