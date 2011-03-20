<?
  include_once('lib/sparkline/Sparkline_Bar.php');

  class Stats extends Widget {
    public function render() {
          umask(0);
          if (!file_exists('tmp/stats/')) 
             if (!@mkdir('tmp/stats/', 0755, true)) 
                return 'Stats widget needs folder /tmp/stats with writing permissions<br />';

          $hash = md5($this->path);
          $week = date('W');

	  if (!file_exists('tmp/stats/'.$hash.'.json')) {
	  	for ($w = 1; $w <= 52; $w++)
	    		$data[$w] = 0;
	  } else {
	  	$data = json_decode(file_get_contents('tmp/stats/'.$hash.'.json'), true);
	  }

	  $data[$week]++;
	  $fh = @fopen('tmp/stats/'.$hash.'.json', 'w');

          if (!$fh) return 'Stats widget needs folder /tmp/stats with writing permissions<br />';
	      
	  fwrite($fh, json_encode($data));
	  fclose($fh);
	         	  
 	  $sparkline = new Sparkline_Bar();
	  for ($w = 1; $w <= 52; $w++)
         	  $sparkline->setData($w, $data[$w], $w == $week?'blue':'black');
          $sparkline->Render(20);
          $sparkline->Output('tmp/stats/file.png');
          return '<img class="stats" src="tmp/stats/file.png" />';
    }
  
    protected function getDefaultParam() {
      return null;
    }
  }
?>
