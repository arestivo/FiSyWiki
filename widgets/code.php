<?
  include_once('lib/geshi.php');

  class Code extends Widget {
    public function render() {
    
      $lang = $this->getParam('lang', 'php');
      $source = $this->getParam('source', null);

      if ($source == null) return;

      $geshi = new GeSHi(trim($source), $lang);      
      $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      $geshi->enable_classes(false);
      return $geshi->parse_code();
    }
  }

  protected function getDefaultParam() {
    return 'source';
  }
?>
