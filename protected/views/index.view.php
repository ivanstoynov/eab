index view here
<br />
<br />
<br />
<br />
<br />
<form>

<?php 
	//$this->formValidator->getValidator('sometextbox')->displayErrors();
	 $textbox = new EblTextComponent('sometextbox','test');
	 $textbox->render();
?>
<br />
<?php $sex =  new EblSelectComponent('sex', array(
		new EblOptionComponent('', 'select'),
		new EblOptionComponent('m', 'male', true),
		new EblOptionComponent('f', 'female')
	)); 
	//$sex->setMultiple(TRUE);
	$sex->render();
?>
	
<br />
<?php $textarea =  new EblTextareaComponent('textarea','ala bala',array(
		'rows'=>'10',
		'cols'=>'50',
	)); 
	$textarea->render();
?>

<br />
<?php $this->form->rbList->render(); ?>
<br />
<div style="clear:both"></div>
<?php $this->form->cbxList->render(); ?>
<div style="clear:both"></div>
<br />
<?php 
	$button = new EblSubmitComponent('subBtn','Submit form'); 
	$button->render();
?>

</form>