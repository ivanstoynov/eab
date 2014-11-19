<?php 

class HeaderLoginSection extends EabSection
{
	public function render()
	{
		$this->testVal='some section val';
		$this->renderHtml("test.section.php");
	}
}
?>