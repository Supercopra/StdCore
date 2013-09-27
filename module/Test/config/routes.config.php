<?php
return array(
	'test' => array(
		'type' => 'Regex',
		'options' => array(
			'spec' => '/test+%id%.html',
			'regex' => '/test\+(?<id>.+)\.html',
			'defaults' => array(
				'__NAMESPACE__' => 'Test\Controller',
				'controller' => 'Test',
				'action' => 'test',
			),
		),
		'may_terminate' => '1',
	),
);