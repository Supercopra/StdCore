<?php

namespace Core\Model;

class Table extends AbstractConsoleModel
{
    private $_module;
    private $_name;

    /**
     * @var \Core\Model\StructureChoose
     */
    private $_src;

    public function create()
    {
        $str = new StructureChoose();
        $str->setConsole($this->getConsole());
        $this->_src = &$str;
        $this->_module = $str->chooseModule();
        $this->_setName();
        $this->_createFiles();
        $this->success('Tabelle wurde erfolgreich erstellen.');
        $this->question('Spalten anlegen ? ([y]/n)');
        if ($this->getInput('y') == 'y') {
            $column = new Column();
            $column->setConsole($this->getConsole());
            $column->create($this->_module . '\Entity\\' . $this->_name);
        }
    }

    private function _createFiles()
    {
        $parser = new TmpToPhpParser();
        $file   = __DIR__ . '/../Sandbox/Util/Entity.php.tmp';
        $entityContent = $parser->parse($file, array(
            'name'   => $this->_name,
            'module' => $this->_module
        ));
        $path = $this->_src->getSrcPathByModule($this->_module) . '/Entity/' . $this->_name . '.php';
        file_put_contents($path, $entityContent);


        $file   = __DIR__ . '/../Sandbox/Util/Repository.php.tmp';
        $repoContent = $parser->parse($file, array(
            'name'   => $this->_name,
            'module' => $this->_module
        ));
        $path = $this->_src->getSrcPathByModule($this->_module) . '/Repository/' . $this->_name . '.php';
        file_put_contents($path, $repoContent);
    }

    private function _setName()
    {
        $this->question('Name der Tabelle ?');
        $in = $this->getInput();
        if (empty($in)) {
            $this->error('Ungültiger Name');
            $this->_setName();
        }
        $this->_name = $in;
    }
}