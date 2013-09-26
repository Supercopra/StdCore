<?php
return array(
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action' => 'index',
					),
				),
			),
		),
	),
	'console' => array(
		'router' => array(
			'routes' => array(
			),
		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'0' => 'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'1' => 'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases' => array(
			'translator' => 'MvcTranslator',
		),
	),
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			'0' => array(
				'type' => 'gettext',
				'base_dir' => '/data/j.horn/Projects/skeleton/module/Application/config/../language',
				'pattern' => '%s.mo',
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'Application\Controller\Index' => 'Application\Controller\IndexController',
			'Application\Controller\Neu' => 'Application\Controller\NeuController',
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => '1',
		'display_exceptions' => '1',
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array(
			'layout/layout' => '/data/j.horn/Projects/skeleton/module/Application/config/../view/layout/layout.phtml',
			'application/index/index' => '/data/j.horn/Projects/skeleton/module/Application/config/../view/application/index/index.phtml',
			'error/404' => '/data/j.horn/Projects/skeleton/module/Application/config/../view/error/404.phtml',
			'error/index' => '/data/j.horn/Projects/skeleton/module/Application/config/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			'0' => '/data/j.horn/Projects/skeleton/module/Application/config/../view',
		),
	),
	'doctrine' => array(
		'driver' => array(
			'_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(
					'0' => '/data/j.horn/Projects/skeleton/module/Core/../src//Entity',
				),
			),
			'orm_default' => array(
				'drivers' => array(
					'\Entity' => '_driver',
				),
			),
		),
	),
);