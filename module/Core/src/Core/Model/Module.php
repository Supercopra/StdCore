<?php
namespace Core\Model;

class Module extends AbstractConsoleModel
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_base;

    public function create($name)
    {
        $this->_name = ucfirst(strtolower($name));
        if (file_exists(getcwd() . '/module/' . $this->_name)) {
            $this->error('Dieses Modul existiert bereits!');
        } else {
            $name  = $this->_name;
            $this->_base = $base = getcwd() . '/module/' . $name;
            $app   = '/src/' . $name;
            mkdir($base);
            $paths = array(
                '/config',
                '/src',
                '/view',
                $app,
                $app . '/Controller',
                $app . '/Model',
                $app . '/Form',
                $app . '/Entity',
                $app . '/Repository'
            );

            foreach ($paths as $path) {
                mkdir($base . $path);
            }
            $this->_createFiles();
            $this->_addModuleToConfig();
            $this->success('Modul wurde erfolgreich angelegt!');
            $this->question('Möchtest du einen Controller darin anlegen ? ([y]/n)');
            if ($this->getInput('y') == 'y') {
                $this->question('Name des Controllers ?');
                $name = $this->getInput();
                $controller = new Controller();
                $controller->setConsole($this->_c);
                $controller->create($name, $this->_name);
            }
        }
    }

    private function _createFiles()
    {
        $parser = new TmpToPhpParser();
        $module = $parser->parse(__DIR__ . '/../Sandbox/Module/module.config.php.tmp', array('namespace' => $this->_name));
        file_put_contents($this->_base . '/config/module.config.php', $module);

        file_put_contents($this->_base . '/config/routes.config.php', '<?php' . PHP_EOL . 'return array();');

        $module = $parser->parse(__DIR__ . '/../Sandbox/Module/Module.php.tmp', array('namespace' => $this->_name));
        file_put_contents($this->_base . '/Module.php', $module);
    }

    private function _addModuleToConfig()
    {
        $configdir = getcwd() . '/config/application.config.php';
        $parser = new ArrayToTextParser();
        $parser->prepare($configdir);
        $config = require $configdir;
        $config['modules'][] = $this->_name;
        file_put_contents($configdir, $parser->parse($config));
        $parser->refuse($configdir);
    }
}