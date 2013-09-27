<?php
return array(
	'test' => array(
		'type' => 'Literal',
		'options' => array(
			'route' => '/test',
			'defaults' => array(
				'__NAMESPACE__' => 'Tett\Controller',
				'controller' => 'Test',
				'action' => 'test',
			),
			'may_terminate' => '1',
			'child_routes' => array(
			),
		),
	),
);