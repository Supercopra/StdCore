<?php
namespace Core\Controller;

use Core\Model\Route;

use Core\Model\Action;

use Core\Model\Controller;

use Core\Model\ArrayToTextParser;
use Core\Model\Module;
use Core\Model\Create;

class SetupController extends AbstractController
{
    public function createAction()
    {
        $create = new Create();
        $create->create($this->getConsole());
    }

    public function createModuleAction()
    {
        $p = $this->getRequest()->getParams();
        $module = new Module();
        $module->setConsole($this->getConsole());
        $module->create($p['name']);
    }

    public function createControllerAction()
    {
        $params = $this->getRequest()->getParams();
        $name   = $params['name'];

        $controller = new Controller();
        $controller->setConsole($this->getConsole());
        $controller->create($name);
    }

    public function createActionAction()
    {
        $action = new Action();
        $action->setConsole($this->getConsole());
        $action->create();
    }

    public function createRouteAction()
    {
    	$routes = new Route();
    	$routes->setConsole($this->getConsole());
    	$routes->create();
    }

    public function createTable()
    {

    }
}