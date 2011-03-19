<?
  class Footer extends Widget {
    public function render() {
          return file_get_contents('snippets/footer');
    }
  
    protected function getDefaultParam() {
      return null;
    }
  }
?>
