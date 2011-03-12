<?
  class Widget {
    protected $name;
    protected $params;
    protected $path;
  
    protected function __construct($name, $params, $path) {
      $this->name = $name;
      $this->params = $params;
      $this->path = $path;
    }
     
    public function create($name, $params, $path) {
      if (!file_exists('widgets/'.$name.'.php')) die ('No such widget: ' . $name);;
      include_once('widgets/'.$name.'.php');
      $widget = new $name($name, $params, $path);
      return $widget;
    }  
  
    public function render() {
    	die ('Render function not implemented for widget ' . $this->name);
    }
    
    protected function getParam($p, $default) {
    	if (isset($this->params[$p])) return $this->params[$p];
    	return $default;
    }
  }

?>
