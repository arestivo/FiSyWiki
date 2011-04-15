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

	   if (strpos($path, '"')) $path_without_title = substr($path, 0, strpos($path, '\"'));
	   else $path_without_title = $path;
           if (!((file_exists('pages/'.$path_without_title) && !is_dir('pages/'.$path_without_title)) || file_exists('pages/'.$path_without_title.'/index'))) 
              $class = "notfound";
	   else $class = "";

	   if ($class)
	     $content = str_replace($complete, '<span class="' . $class . '">' . "[$title]($prefix$path)</span>", $content);
	   else
	     $content = str_replace($complete, "[$title]($prefix$path)", $content);
	 }
      }
      
      $global = Page::getGlobalParams($this->path);
      
      while (true) {
        if (preg_match('/\[\[(.*?)\]\]/s', $content, $matches) == 0) break;
        $widget = $matches[1];

        if (preg_match('/(.*)\|(.*)/s', $widget, $matches) == 1) {
          $widget = $matches[1];
          $params = $matches[2];
        } 

        $widget = Widget::create($widget, $this->path);
        $widget->addParams($params);
        if (isset($global[$widget->getName()]))
          $widget->addParams($global[$widget->getName()]);

        $content = preg_replace('/\[\[.*?\]\]/s', $widget->render(), $content, 1);    
      }
      $content = Markdown($content);
      return $content;
    }
    
    protected function getDefaultParam() {
      return null;
    }
  }

?>
