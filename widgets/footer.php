<?

  class Footer extends Widget {
  	public function render() {
      		return Markdown(file_get_contents('snippets/footer'));
  	}
  }

?>
