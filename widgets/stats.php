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

	  if ($db = new SQLite3('tmp/stats/' . $hash . '.sql')) {
//	        chmod('tmp/stats/' . $hash . '.sql', 0777);
	        $q = @$db->query('SELECT views FROM stats WHERE week = ' . $week);
	        if ($q === false) {
	            $db->exec('CREATE TABLE stats (week integer, views integer); INSERT INTO stats VALUES ('.$week.',1)');
        	    $hits = 1;
        	} else {
        	    $result = $q->fetchArray();
        	    $hits = $result[0] + 1;
        	}
	        $db->exec('UPDATE stats SET views = ' . $hits . ' WHERE week = ' . $week);
    	  } else {
        	return $err;
    	  }

	  for ($w = 1; $w <= 52; $w++)
	    $data[$w] = 0;

          $q = @$db->query('SELECT week, views FROM stats');
       	  while ($result = $q->fetchArray()) {
		$data[$result[0]] = $result[1];
       	  }
       	  
 	  $sparkline = new Sparkline_Bar();
	  for ($w = 1; $w <= 52; $w++)
         	  $sparkline->setData($w, $data[$w]);
          $sparkline->Render(20);
          $sparkline->Output('tmp/stats/file.png');
          return '<img class="stats" src="tmp/stats/file.png" />';
    }
  
    protected function getDefaultParam() {
      return null;
    }
  }
?>
