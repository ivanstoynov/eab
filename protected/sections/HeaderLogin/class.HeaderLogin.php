<?php 

class HeaderLoginSection extends EabSection
{
	public function display()
	{
		$this->testVal='some section val';
		$this->renderHtml("test.section.php");
	}
}
?>