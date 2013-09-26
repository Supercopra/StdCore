<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => require __DIR__ . '/routes.config.php'
    ),
    'console' => array(
        'router' => array(
            'routes' => require __DIR__ . '/consoleRoutes.config.php'
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
            'Core\Controller\Index' => 'Core\Controller\IndexController',
            'Core\Controller\Setup' => 'Core\Controller\SetupController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => require getcwd() . '/module/Core/DoctrineConfig.php'
);
