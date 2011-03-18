<?
  class SiFEUP extends Widget {
  	public function render() {
  	
  	  $type = $this->getParam('type', 'course');
  	  $code = $this->getParam('code', null);
  	  $year = $this->getParam('year', null);
	  $semester = $this->getParam('semester', null);

	  if ($code == null) return;
	  if ($type == 'course') {
	  	if ($year == null || $semester == null) 
	  	  return '<a href="https://www.fe.up.pt/si/disciplinas_geral.formview?p_cad_codigo='.$code.'">SiFEUP</a>';
	  	else 
	  	  return '<a href="https://www.fe.up.pt/si/disciplinas_geral.formview?p_cad_codigo='.$code.'&p_ano_lectivo='.$year.'/'.($year+1).'&p_periodo='.$semester.'S">SiFEUP</a>';
	  }
  	}
  }
?>
