<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
<?php $this->displayHeadTitle(); ?>
	<link rel="stylesheet" type="text/css" href="public/styles/base.css" />
	<link rel="stylesheet" type="text/css" href="public/Ebl/ebl_html_components.css" />
</head>

<body>

<?php 

//Sfw::debug($this);

?>

	<div>
		<?php EabSection::Create('HeaderLogin')->display(); ?>
	</div>
	<div>
		Master layout
	</div>
	<div>
		<?php echo $this->getContent(); ?>
	</div>
</body>

</html> 