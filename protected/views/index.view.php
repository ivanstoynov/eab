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
	$this->formValidator->getValidator('sometextbox')->displayErrors();
	EblHtmlComponent::RenderComponent( new EblTextComponent('sometextbox','test')); 
?>
<br />
<?php EblHtmlComponent::RenderComponent( new EblSelectComponent('sex', array(
		new EblOptionComponent('', 'select'),
		new EblOptionComponent('m', 'male', true),
		new EblOptionComponent('f', 'female')
	))); 
?>
	
<br />
<?php EblHtmlComponent::RenderComponent( new EblTextareaComponent('textarea','ala bala',array(
		'rows'=>'10',
		'cols'=>'50',
	))); 
?>

<br />
<?php $this->rbList->display(); ?>
<br />
<div style="clear:both"></div>
<?php $this->cbxList->display(); ?>
<div style="clear:both"></div>
<br />
<?php EblHtmlComponent::RenderComponent( new EblSubmitComponent('subBtn','Submit form')); ?>

</form>