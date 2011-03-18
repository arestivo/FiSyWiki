<?
  class Latex extends Widget {
    public function render() {
    
      $value = $this->getParam('value', null);

      if ($value == null) return;

      $hash = md5($value);
      
      if (!file_exists('tmp/'.$hash.'.png')) {
      
	      $latex = '\documentclass[fleqn]{article} \usepackage{amssymb,amsmath,bm,color} \usepackage[latin1]{inputenc} \begin{document} \thispagestyle{empty} \mathindent0cm \parindent0cm \begin{displaymath}'.$value.'\end{displaymath}\end{document}';

	      $file = 'tmp/'.$hash.'.';

	      $fh = fopen($file.'latex', 'w');
	      fwrite($fh, $latex);
	      fclose($fh);
	      
	      exec('cd tmp; latex '.$hash.'.latex');
	      exec('cd tmp; dvipng q -T tight -D 130 -o '.$hash.'.png '.$hash.'.dvi');
	      exec('cd tmp; rm *.latex; rm *.dvi; rm *.aux; rm *.log');
      }
            
      return '<img src="tmp/'.$hash.'.png" />';
    }
  }
?>
