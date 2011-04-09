<?
  class Header extends Widget {
    public function render() {
      $header = file_get_contents('snippets/header');
      $header = preg_replace('/##theme##/', $this->getParam('theme', 'default'), $header);
      $header = preg_replace('/##title##/', $this->getParam('title', 'FisyWiki'), $header);
      $subtitle = $this->getParam('subtitle', $this->getPageId());
      $header = preg_replace('/##subtitle##/', $this->getParam('titleseparator', '::') . ' ' . $subtitle, $header);
      
      $analytics = $this->getParam('analytics', null);
      if ($analytics != null) {
         $snippet = file_get_contents('snippets/analytics');
         $snippet = preg_replace('/##analytics##/', $analytics, $snippet);
	 $header = preg_replace('/##analytics##/', $snippet, $header);
      } else 
	 $header = preg_replace('/##analytics##/', '', $header);
         
      return $header;
    }
    
    public function getPageId() {
        $id = $this->path;
        if ($id == 'index') return 'Home';
        $id = str_replace('/index', '', $id);
    	$pos = strrpos($id, '/'); if ($pos) $pos++;
    	return Page::getFileName(substr($id, $pos));
    }
    
    protected function getDefaultParam() {
    	return 'title';
    }
  }
?>
