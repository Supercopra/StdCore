<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Core;

use Zend\Mvc\ModuleRouteListener;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Mvc\MvcEvent;

class Module //implements ConsoleUsageProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConsoleUsage($console){
        return array(
            'core-create'               => 'Initialisiert ein neues Projekt und setzt Standardeinstellungen (Datenbank-Verbindung, etc)',
            'core-create-module [name]' => 'Legt ein neues Module mit angegebenen Namen an',
            'core-create-controller [name]' => 'Legt einen neuen Controller mit angegebenen Namen an',
            'core-create-action' => 'Legt eine neue Action in einem Controller an (inkl. View)',
            'core-create-route' => 'Legt eine Route an und verbindet diese mit einer Action',
        );
    }
}
