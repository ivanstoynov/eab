<?php

	EabModulsImporter::import('Ebl/Validation/*');
	EabModulsImporter::import('Ebl/FormComponents/*');
	
	class IndexController extends EabController
	{
		public function index()
		{
			
			
			$books = BookModel::find(array('where'=>'id=' . 2));
			$book = $books[0];
			//$book->
			//Eab::debug($books);
			
			//$radio = new EblRadioButtonComponent('sex', 'male', 'male sex',false);
			//$radio->setTextPosition('right');
			
			$form = new EblForm();
			
			$cbxList = new EblCheckBoxList('sex1');
			$cbxList->setTextPosition('right');
			$cbxList->setDirection('vertical');
			$cbxList->addElement(new EblCheckBoxComponent('', 'male', 'm', false));
			$cbxList->addElement(new EblCheckBoxComponent('', 'female', 'f', true));
			$form->cbxList = $cbxList;
			
			
			$rbList = new EblRadioButtonList('sex2');
			$rbList->setTextPosition('right');
			$rbList->setDirection('vertical');
			$rbList->addElement(new EblRadioButtonComponent('','male', 'm', false));
			$rbList->addElement(new EblRadioButtonComponent('','female', 'f', true));
			$form->rbList = $rbList;
			
			
			 $txtName = new EblTextComponent('sometextbox','test');
			 //$txtName->addValidator();
			
			$this->form = $form;
			
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
