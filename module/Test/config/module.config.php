<?php
return array(
	'router' => array(
		'routes' => require __DIR__. '/routes.config.php',
	),
	'console' => array(
		'router' => array(
			'routes' => require __DIR__. '/console-routes.config.php',
		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases' => array(
		),
	),
	'controllers' => array(
		'invokables' => array(
			'Test\Controller\Test' => 'Test\Controller\TestController',
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			__DIR__. '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__. '_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(
					__DIR__. '/../src/' . __NAMESPACE__ . '/Entity',
				),
			),
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__. '\Entity' => __NAMESPACE__. '_driver',
				),
			),
		),
	),
);