<?php

	EabModulsImporter::import('Ebl/Validation/class.EblFormValidator.php');
	EabModulsImporter::import('Ebl/HtmlComponents/Standart/class.EblRadioButtonList.php');
	EabModulsImporter::import('Ebl/HtmlComponents/Standart/class.EblCheckBoxList.php');

	class IndexController extends EabController
	{
		public function index()
		{
			echo 'This is '.__METHOD__.'()<br/>';
			$this->testt='testtt';
			
			Eab::debug($name);
			
			//$radio = new EblRadioButtonComponent('sex', 'male', 'male sex',false);
			//$radio->setTextPosition('right');
			
			$cbxList = new EblCheckBoxList('sex');
			$cbxList->setTextPosition('right');
			$cbxList->setDirection('vertical');
			$cbxList->addElement('male', 'm', false);
			$cbxList->addElement('female', 'f', true);
			$this->cbxList=$cbxList;
			
			
			$rbList = new EblRadioButtonList('sex');
			$rbList->setTextPosition('right');
			$rbList->setDirection('vertical');
			$rbList->addElement('male', 'm', false);
			$rbList->addElement('female', 'f', true);
			$this->rbList = $rbList;
			
			// Validation
			$formValidator = new EblFormValidator();
			$formValidator->getValidator('sometextbox')->addRule(array('required'))
													   ->addRule(array('length', '5:20'))
													   ->addRule(array('email'));
			$this->formValidator = $formValidator;

			if($_REQUEST['subBtn']){
				$formValidator->validate();
			}

			$this->renderView("index.view.php");
		}
		
		public function test()
		{
			echo __METHOD__.'()';
		}
	}

?>
