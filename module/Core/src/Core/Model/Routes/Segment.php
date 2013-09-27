<?php
namespace Core\Model\Routes;

use Core\Model\ArrayToTextParser;

use Core\Model\AbstractConsoleModel;

class Segment extends AbstractConsoleModel
{
    private $_matching;
    private $_options;
    private $_params;
    private $_constraints;
    private $_currentParam;

    private $_consts = array(
        'keine Validierung',
        '[0-9]+ - Nur Zahlen erlaubt',
        '[a-zA-Z]+ - Nur Buchstaben erlaubt',
        '[a-zA-Z0-9]+ - Nur Buchstaben und Zahlen erlaubt',
    );

    private $_constsVal = array(
        null,
        '[0-9]+',
        '[a-zA-Z]+',
        '[a-zA-Z0-9]+',
    );

    public function create($options)
    {
        $this->_options  = $options;
        $this->_matching = $this->_getMatching();
        $this->question('Parameter anlegen ? ([y]/n)');
        $in = $this->getInput('y');
        if ($in == 'y') {
            while ($in == 'y') {
                $this->question('Name des Parameters ? z.B. "id"');
                $this->_currentParam = $this->_addParam();
                $this->_params[]     = $this->_currentParam;
                $this->_setConstraints();
                $this->question('Weiterer Parameter ? ([y]/n)');
                $in = $this->getInput('y');
            }
        }

        $routing = $this->_getRouteArray();

        $parser = new ArrayToTextParser();
        $file   = getcwd() . '/module/' . $this->_options['module'] . '/config/routes.config.php';
        $parser->prepare($file);
        $config = require $file;
        $config[$this->_options['name']] = $routing;
        $parser->parseAndSave($config, $file);
        $parser->refuse($file);
    }

    private function _setConstraints()
    {
        $this->question('Validierung des Parameters bestimmen (wählen oder selbst eingeben) [keine Validierung]');
        $this->choose($this->_consts);
        $in = $this->getInput('0');
        if (is_numeric($in)) {
            if ($in != '0') {
                $this->_constraints[$this->_currentParam] = $this->_constsVal[$in];
            }
        } else {
            $this->_constraints[$this->_currentParam] = $in;
        }
    }

    private function _getRouteArray()
    {
        $params = '';
        foreach ($this->_params as $p) {
            $params .= '[/:' . $p . ']';
        }
        $routing = array(
            'type'    => 'Segment',
            'options' => array(
                'route'    => $this->_matching . $params,
                'defaults' => array(
                    '__NAMESPACE__' => $this->_options['module'] . '\Controller',
                    'controller'    => preg_replace('/Controller$/', '', $this->_options['controller']),
                    'action'        => preg_replace('/Action$/', '', $this->_options['action'])
                ),
                'constraints'   => $this->_constraints,
                'may_terminate' => true
            )
        );
        return $routing;
    }

    private function _addParam()
    {
        $in = $this->getInput();
        return $in;
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