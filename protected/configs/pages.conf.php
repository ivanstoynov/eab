<?php
return array(

	'__defaults__'=>array(
		'js'=>array(
			
		),
		'styles'=>array(
			
		),
		'title'=>'some title',
		'meta'=>array(
			array('keywords'=>'some keywords'),
			array('description'=>'some description'),
			array('robots'=>'some robots'),
		)
	),
	
	'__controllers__'=>array(
		'__defaults__' =>array(
		),
		
		'some/controller1' => array(
			'action1'=>array(
				'js'=>array(
				),
				'styles'=>array(
				),
				'title'=>'',
				'meta'=>array('keywords'=>''),
				'meta'=>array('description'=>''),
			),
			
			'action2'=>array(
				'js'=>array(
				),
				'styles'=>array(
				),
				'title'=>'',
				'meta'=>array('keywords'=>''),
				'meta'=>array('description'=>''),
			),

			// ...
		),
		
		// ...
	)
);
?>