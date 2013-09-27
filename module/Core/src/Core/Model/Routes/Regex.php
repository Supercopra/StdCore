<?php

namespace Core\Model\Routes;

use Core\Model\ArrayToTextParser;

use Core\Model\AbstractConsoleModel;

class Regex extends AbstractConsoleModel
{
    private $_options;
    private $_spec;
    private $_regex;

    public function create($options)
    {
        $this->_options = $options;
        $this->_spec    = $this->_getSpec();
        $this->_setDefaultRegex();
        $this->_changeDefaultRegex();

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
            'type'    => 'Regex',
            'options' => array(
                'spec'     => $this->_spec,
                'regex'    => $this->_regex,
                'defaults' => array(
                    '__NAMESPACE__' => $this->_options['module'] . '\Controller',
                    'controller'    => preg_replace('/Controller$/', '', $this->_options['controller']),
                    'action'        => preg_replace('/Action$/', '', $this->_options['action'])
                )
            ),
            'may_terminate' => true,
        );
        return $routing;
    }

    private function _changeDefaultRegex()
    {
        $this->success($this->_regex);
        $this->question('Diesen Match verwenden ? ([y]/n)');
        $in = $this->getInput('y');
        if ($in == 'y') {
            return;
        }
        $this->_getOwnRegex();
    }

    private function _getOwnRegex()
    {
        $this->question('Eigenen Regex eingeben');
        $in = $this->getInput();
        if (empty($in)) {
            $this->error('Ungültige Eingabe!');
            $this->_getOwnRegex();
        }
        $this->_regex = $in;
    }

    private function _setDefaultRegex()
    {
        $spec = preg_quote($this->_spec);
        $this->_regex = preg_replace('/(%(.*?)%)/', '(?<\2>.+)', $spec);
    }

    private function _getSpec()
    {
        $this->question('Zend-Spezifikation für Route (Spec Bsp: "/test+%param%.html")');
        $in = $this->getInput();
        if (empty($in)) {
            $this->error('ungültige Angabe.');
            return $this->_getSpec();
        }
        return $in;
    }
}