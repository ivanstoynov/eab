<?php EabModulsImporter::import('Ebl/HtmlComponents/*') ?>

index view here
<br/>
<?php echo $this->testt; ?>

<br />
<br />
<br />
<br />
<br />
<form>

<?php 
	//$this->formValidator->getValidator('sometextbox')->displayErrors();
	 $textbox = new EblTextComponent('sometextbox','test');
	 $textbox->printHtml();
?>
<br />
<?php $sex =  new EblSelectComponent('sex', array(
		new EblOptionComponent('', 'select'),
		new EblOptionComponent('m', 'male', true),
		new EblOptionComponent('f', 'female')
	)); 
	//$sex->setMultiple(TRUE);
	$sex->printHtml();
?>
	
<br />
<?php $textarea =  new EblTextareaComponent('textarea','ala bala',array(
		'rows'=>'10',
		'cols'=>'50',
	)); 
	$textarea->printHtml();
?>

<br />
<?php $this->rbList->printHtml(); ?>
<br />
<div style="clear:both"></div>
<?php $this->cbxList->printHtml(); ?>
<div style="clear:both"></div>
<br />
<?php 
	$button = new EblSubmitComponent('subBtn','Submit form'); 
	$button->printHtml();
?>

</form>