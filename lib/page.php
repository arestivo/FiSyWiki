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
	    if (preg_match('/\[\[(.*?)\]\]/s', $layout, $matches) == 0) break; // No more widgets

	    $widget = $matches[1];
	    $params = "";
	
	    // Get widget parameters    
    	    if (preg_match('/(.*)\|(.*)/s', $widget, $matches) == 1) {
	      $widget = $matches[1];
	      $params = $matches[2];
	    }

	    // Use widget factory to create widget
    	    $widget = Widget::create($widget, $path);
	    $widget->addParams($params);
	    if (isset($global[$widget->getName()])) 
	      $widget->addParams($global[$widget->getName()]);

	    // Render widget
	    $layout = preg_replace('/\[\[.*?\]\]/s', $widget->render(), $layout, 1);    	    
      }
      $layout = preg_replace('/~~.*~~/', '', $layout);
      
      return $layout;
    }

    public function getFileName($file) {
        $file = str_replace('-', ' ', $file);
        $file = str_replace('_', ' ', $file);
        $words = explode(' ', $file);
	$name = '';
        foreach($words as $w) {
        	if (preg_match('/[a-z]/i', $w))
 		       	$name .= ' ' . ucfirst($w);
        }
	$name = trim($name);        
	return $name;
    }

  }
?>
