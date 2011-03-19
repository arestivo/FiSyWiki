<?
  abstract class Widget {
    protected $name;
    protected $params;
    protected $path;
  
    protected function __construct($name, $path) {
      $this->name = $name;
      $this->path = $path;
      $this->params = array();
    }
     
    public function getName() {
      return $this->name;
    }
    
    public function create($name, $path) {
      $name = strtolower($name);
      if (!file_exists('widgets/'.$name.'.php')) die ('No such widget: ' . $name);;
      include_once('widgets/'.$name.'.php');
      $widget = new $name($name, $path);
      return $widget;
    }  
  
    public function render() {
    	die ('Render function not implemented for widget ' . $this->name);
    }

    public function addParams($params) {
        if ($params == null) return;
        $params = explode("##", $params);
        foreach ($params as $p) {
          if ($p == '') continue;
          if (preg_match('/(.*?)=(.*)/s', $p, $matches) == 1)
            $this->params[$matches[1]] = $matches[2];
 	  else if ($this->getDefaultParam() != null)
 	    $this->params[$this->getDefaultParam()] = $p;
        }
    }
  
    abstract protected function getDefaultParam();
        
    protected function getParam($p, $default) {
    	if (isset($this->params[$p])) return $this->params[$p];
    	return $default;
    }
  }

?>
