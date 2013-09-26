<?php
return array(
	'kacken' => array(
		'type' => 'Literal',
		'options' => array(
			'route' => '/kacken',
			'defaults' => array(
				'$$__NAMESPACE__' => 'Test\Controller',
				'controller' => 'Hallo',
				'action' => 'kackwurst',
			),
			'may_terminate' => '1',
			'child_routes' => array(
			),
		),
	),
);