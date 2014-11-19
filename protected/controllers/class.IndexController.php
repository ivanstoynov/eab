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
			$cbxList->addElem('male', 'm', false);
			$cbxList->addElem('female', 'f', true);
			$this->cbxList=$cbxList;
			
			
			$rbList = new EblRadioButtonList('sex');
			$rbList->setTextPosition('right');
			$rbList->setDirection('vertical');
			$rbList->addElem('male', 'm', false);
			$rbList->addElem('female', 'f', true);
			$this->rbList=$rbList;
			
			//$validator
			EblFormValidator::GetInstance()->make('sometextbox')->addRule(array('required'))
															 ->addRule(array('length', '5:20'))
															 ->addRule(array('email'));

			if($_REQUEST['subBtn']){
				EblFormValidator::GetInstance()->validate();
			}

			$this->renderView("index.view.php");
		}
		
		public function test()
		{
			echo __METHOD__.'()';
		}
	}

?>
