<?php
namespace Core\Model\Routes;

use Core\Model\ArrayToTextParser;

use Core\Model\AbstractConsoleModel;

class Literal extends AbstractConsoleModel
{
    private $_matching;
    private $_options;

    public function create($options)
    {
        $this->_options  = $options;
        $this->_matching = $this->_getMatching();
        $routing = $this->_getRouteArray();

        $parser = new ArrayToTextParser();
        $file   = getcwd() . '/module/' . $this->_options['module'] . '/config/routes.config.php';
        $parser->prepare($file);
        $config = require $file;
        $config[$this->_options['name']] = $routing;
        $parser->parseAndSave($config, $file);
        $parser->refuse($file);
    }

    private function _getRouteArray()
    {
        $routing = array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => $this->_matching,
                'defaults' => array(
                    '__NAMESPACE__' => $this->_options['module'] . '\Controller',
                    'controller'    => preg_replace('/Controller$/', '', $this->_options['controller']),
                    'action'        => preg_replace('/Action$/', '', $this->_options['action'])
                ),
                'may_terminate' => true,
                'child_routes'  => array()
            )
        );
        return $routing;
    }

    private function _getMatching()
    {
        $this->question('Matching (Routenbezeichner) ? (Bsp.: "/home")');
        $in = $this->getInput();
        if (empty($in) || substr($in, 0, 1) != '/') {
            $this->error('Bezeichner fehlerhaft!');
            return $this->_getMatching();
        }
        return $in;
    }
}