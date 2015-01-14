<?php

	EabModulsImporter::import('Ebl/Validation/*');
	EabModulsImporter::import('Ebl/HtmlComponents/*');
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
			
			$form = new EblHtmlForm();
			
			$cbxList = new EblCheckBoxList('sex1');
			$cbxList->setTextPosition('right');
			$cbxList->setDirection('vertical');
			$cbxList->addElement(new EblCheckBoxComponent('', 'male', 'm', false));
			$cbxList->addElement(new EblCheckBoxComponent('', 'female', 'f', true));
			$this->cbxList = $cbxList;
			$form->cbxList = $cbxList;
			
			
			$rbList = new EblRadioButtonList('sex2');
			$rbList->setTextPosition('right');
			$rbList->setDirection('vertical');
			$rbList->addElement(new EblRadioButtonComponent('','male', 'm', false));
			$rbList->addElement(new EblRadioButtonComponent('','female', 'f', true));
			$this->rbList = $rbList;
			
			
			
			// Validation
			//$formValidator = new EblFormValidator();
			//$formValidator->getValidator('sometextbox')->addRule(EblValidationRulesTypes::REQUIRED)
//													   ->addRule(EblValidationRulesTypes::LENGTH, '5:20')
//													   ->addRule(EblValidationRulesTypes::EMAIL);
//			$this->formValidator = $formValidator;

			Eab::debug($_REQUEST);

			if($_REQUEST['subBtn']){
				//$formValidator->validate();
			}

			$this->renderView("index.view.php");
		}
		
		public function test()
		{
			echo __METHOD__.'()';
		}
	}

?>
