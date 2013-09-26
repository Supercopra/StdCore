<?php
namespace Core\Model;

class Action extends AbstractConsoleModel
{
    /**
     * @var string
     */
    private $_module;

    /**
     * @var string
     */
    private $_controller;

    /**
     * @var string
     */
    private $_name;


    public function create($module = null, $controller = null)
    {
        $dirs = array_filter(glob(getcwd() . '/module/*'), 'is_dir');
        $resultdirs = array();
        foreach($dirs as $dir) {
            $resultdirs[] = basename($dir);
        }

        if (!empty($module) && !empty($controller)) {
            $this->_module     = $module;
            $this->_controller = $controller;
        } else {
            $module     = $this->_chooseModule($resultdirs);
            $controller = $this->_chooseController();
        }

        $this->_name = $this->_getName();

        $this->_createAction();
        $this->_createView();
        $this->success('Die Action wurde erfolgreich angelget!');
    }

    private function _createNameForView()
    {
        $name = '';
        for ($i = 0, $length = strlen($this->_name); $i < $length; $i++) {
            $char = $this->_name[$i];
            if ($char === strtoupper($char)) {
                $name .= '-' . strtolower($char);
            } else {
                $name .= $char;
            }
        }
        return $name;
    }

    private function _createView()
    {
        $content = '<h1>' . $this->_module . '\\' . $this->_controller . '::' . $this->_name . 'Action</h1>';
        $name    = $this->_createNameForView();
        $controller = preg_replace('/controller$/i', '', $this->_controller);
        $path    = getcwd() . '/module/' . $this->_module . '/view/' . strtolower($this->_module) .
                   '/' . strtolower($controller) . '/' . $name . '.phtml';
        file_put_contents($path, $content);
    }

    private function _createAction()
    {
        $path = getcwd() . '/module/' . $this->_module . '/src/' .
                $this->_module . '/Controller/' . $this->_controller . '.php';

        $content  =  PHP_EOL;
        $content .= "\t" . 'public function ' . $this->_name . 'Action()' . PHP_EOL;
        $content .= "\t" . '{' . PHP_EOL;
        $content .= "\t\t" . '$returnVal = array();' . PHP_EOL;
        $content .= "\t\t" . 'return $returnVal;' . PHP_EOL;
        $content .= "\t" . '}' . PHP_EOL . PHP_EOL;
        $content .= '}';

        $controller = file_get_contents($path);
        $controller = preg_replace('/\}[^}{]*$/D', $content, $controller);
        file_put_contents($path, $controller);
    }

    private function _getName()
    {
        $this->question('Name der Action ?');
        $in = $this->getInput();
        if (empty($in)) {
            $this->error('Fehlerhafter Name!');
            return $this->_getName();
        }
        return $in;
    }

    private function _getControllerByModule($module)
    {
        $config      = require getcwd() . '/module/' . $module . '/config/module.config.php';
        $controllers = $config['controllers']['invokables'];
        $c = array();
        foreach ($controllers as $val) {
            $c[] = str_replace($this->_module . '\Controller\\', '', $val);
        }
        return $c;
    }

    private function _chooseController()
    {
        $controllers = $this->_getControllerByModule($this->_module);
        $this->question('Controller ?');
        $this->choose($controllers);
        $in = $this->getInput();
        if (!key_exists($in, $controllers)) {
            $this->error('Dieser Controller existiert nicht!');
            $this->_chooseController();
        } else {
            $this->_controller = $controllers[(int) $in];
            return $this->_controller;
        }
    }

    private function _chooseModule($resultdirs)
    {
        $this->question('Module ? [Application]');
        $this->choose($resultdirs);

        $input = $this->getInput();
        if($input == '') {
            $this->_module = $module = 'Application';
            return $module;
        } else {
            if(!empty($resultdirs[$input])) {
                $this->_module = $module = $resultdirs[$input];
                return $module;
            } else {
                $this->error('Dieses Modul existiert nicht!');
                $this->chooseModule($resultdirs);
            }
        }
    }
}