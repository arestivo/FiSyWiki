<?
  class Page {
    private $path;

    public function __construct($path) {
    	$this->path = $path;
    }

    public function getPath() {
      global $_GET;
    
      // Default page is index
      if (isset($_GET['p'])) 
        $path = $_GET['p']; 
      else 
        $path = 'index';

      // Append missing forward slash if page is a directory
      if ($path[strlen($path) - 1] != '/' && is_dir('pages/'. $path)) 
        $path .= '/';

      // Append missing index if page is a directory
      if ($path[strlen($path) - 1] == '/')
        $path .= 'index';

      return $path;
    }  
    
    public function create($path) {
    	return new Page($path);
    }
    
    public function render(){
    	$layout = $this->getLayout($this->path);
    	$content = $this->replaceWidgets($this->path, $layout);
    	echo $content;
    }
    
    private function getLayout($path) {
      // Page not found
      if (!file_exists('pages/'. $path)){
        header("HTTP/1.0 404 Not Found");
        $layout = file_get_contents('layouts/404');
      }

      // Get default template and render markdown
      else 
        $layout = file_get_contents('layouts/default');

      return $layout;
    }  

    public function getGlobalParams($path) {
        $global = array();
        if (file_exists('pages/'. $path)) {
         $content = file_get_contents('pages/' . $path);
          if (preg_match('/~~(.*)\|(.*)~~/', $content, $matches)) {
  	     $widget = $matches[1];
	     $params = json_decode($matches[2], true);
	     $global[$widget] = $params;
	  }
        }
	return $global;
    }
    
    private function replaceWidgets($path, $layout) {
      $global = $this->getGlobalParams($path);

      while (true) {
	    if (preg_match('/\[\[(.*)\]\]/', $layout, $matches) == 0) break; // No more widgets

	    $widget = $matches[1];
	    $params = array();

	    // Get widget parameters    
    	    if (preg_match('/(.*)\|(.*)/', $widget, $matches) == 1) {
	      $widget = $matches[1];
	      $params = json_decode($matches[2], true);
	    }

	    if (isset($global[$widget])) 
	      $params = array_merge($global[$widget], $params);

	    // Use widget factory to create widget
    	    $widget = Widget::create($widget, $params, $path);

	    // Render widget
	    $layout = preg_replace('/\[\[.*\]\]/', $widget->render(), $layout, 1);    	    
      }
      $layout = preg_replace('/~~.*~~/', '', $layout);
      
      return $layout;
    }
  }
?>
