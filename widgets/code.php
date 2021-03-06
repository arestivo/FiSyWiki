<?
  include_once('lib/geshi.php');

  class Code extends Widget {
    public function render() {
    
      $lang = $this->getParam('lang', 'php');
      $source = $this->getParam('source', null);
      $lines = $this->getParam('lines', 'yes');

      if ($source == null) return;

      $source = str_replace('&#124;', '|', $source);

      $geshi = new GeSHi(trim($source), $lang);      
      if ($lines == 'yes') $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      $geshi->enable_classes(false);
      return $geshi->parse_code();
    }
  
    protected function getDefaultParam() {
      return 'source';
    }
  }
?>
