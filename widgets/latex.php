<?
  class Latex extends Widget {
    public function render() {
    
      $value = $this->getParam('value', null);

      if ($value == null) return;

      $hash = md5($value);
      
      umask(0);
      if (!file_exists('tmp/latex/')) 
        if (!@mkdir('tmp/latex/', 0755, true)) 
          return 'Latex widget needs folder /tmp/latex with writing permissions<br />';
      
      if (!file_exists('tmp/latex/'.$hash.'.png')) {
      
	      $latex = '\documentclass[fleqn]{article} \usepackage{amssymb,amsmath,bm,color} \usepackage[latin1]{inputenc} \begin{document} \thispagestyle{empty} \mathindent0cm \parindent0cm \begin{displaymath}'.$value.'\end{displaymath}\end{document}';

	      $file = 'tmp/latex/'.$hash.'.';

	      $fh = @fopen($file.'latex', 'w');
	      if (!$fh) return 'Latex widget needs folder /tmp/latex with writing permissions<br />';
	      
	      fwrite($fh, $latex);
	      fclose($fh);
	      
	      exec('cd tmp/latex; latex '.$hash.'.latex');
	      exec('cd tmp/latex; dvipng q -T tight -D 130 -o '.$hash.'.png '.$hash.'.dvi');
	      exec('cd tmp/latex; rm *.latex; rm *.dvi; rm *.aux; rm *.log');
      }
            
      return '<img class="latex" src="tmp/latex/'.$hash.'.png" />';
    }
  }
?>
