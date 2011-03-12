<?
  class Header extends Widget {
    public function render() {
      $header = file_get_contents('snippets/header');
      $header = preg_replace('/##theme##/', $this->getParam('theme', 'default'), $header);
      $header = preg_replace('/##title##/', $this->getParam('title', 'FisyWiki'), $header);
      $subtitle = $this->getParam('subtitle', $this->getPageId());
      $header = preg_replace('/##subtitle##/', $this->getParam('titleseparator', '::') . ' ' . $subtitle, $header);
      return Markdown($header);
    }
    
    public function getPageId() {
        $id = $this->path;
        if ($id == 'index') return 'Home';
        $id = str_replace('/index', '', $id);
    	$pos = strrpos($id, '/'); if ($pos) $pos++;
    	return ucfirst(substr($id, $pos));
    }
  }
?>
