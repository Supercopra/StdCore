<?php
namespace Core\Model;

class Controller extends AbstractConsoleModel
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_module;

    public function create($name, $module = null)
    {
        $this->_name = ucfirst(strtolower($name));
        $dirs = array_filter(glob(getcwd() . '/module/*'), 'is_dir');
        $resultdirs = array();
        foreach($dirs as $dir) {
            $resultdirs[] = basename($dir);
        }

        if ($module === null) {
            $module = $this->_chooseModule($resultdirs);
        } else {
            $this->_module = $module;
        }
        $this->_createController();
        $this->_createViewFolder();
        $this->_setConfig();


        $this->success('Der Controller wurde erfolgreich angelegt.');
        $this->_createAction();
    }

    private function _createAction()
    {
        $this->question('Möchtest du eine Action in diesem Controller anlegen ? ([y]/n)');
        $in = $this->getInput('y');
        if ($in == 'y') {
            $action = new Action();
            $action->setConsole($this->getConsole());
            $action->create($this->_module, $this->_name . 'Controller');
        }
    }

    private function _createViewFolder()
    {
        mkdir(getcwd() . '/module/' . $this->_module . '/view/' .
              strtolower($this->_module));
        mkdir(getcwd() . '/module/' . $this->_module . '/view/' .
              strtolower($this->_module) . '/' . strtolower($this->_name));
    }

    private function _createController()
    {
        $parser = new TmpToPhpParser();
        $content = $parser->parse(__DIR__ . '/../Sandbox/Util/Controller.php.tmp', array(
            'module' => $this->_module,
            'name'   => $this->_name
        ));
        file_put_contents(getcwd() . '/module/' . $this->_module . '/src/' .
                          $this->_module . '/Controller/' . $this->_name . 'Controller.php', $content);
    }

    private function _setConfig()
    {
        $path   = getcwd() . '/module/' . $this->_module . '/config/module.config.php';
        $parser = new ArrayToTextParser();
        $parser->prepare($path);
        $config = require $path;
        $config['controllers']['invokables'][$this->_module . '\Controller\\' . $this->_name] =
        $this->_module . '\Controller\\' . $this->_name . 'Controller';
        $config = $parser->parse($config);
        file_put_contents($path, $config);
        $parser->refuse($path);
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