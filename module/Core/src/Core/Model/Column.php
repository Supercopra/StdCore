<?php

namespace Core\Model;

class Column extends AbstractConsoleModel
{
    /**
     * @var \Core\Model\StructureChoose
     */
    private $_str;
    private $_entities;
    private $_entity;
    private $_colums;
    private $_currentName;
    private $_currentType;
    private $_id;
    private $_auto;

    private static $TYPE = array(
        'String',
        'Text',
        'Integer',
        'Boolean',
        'Decimal',
        'DateTime'
    );

    public function create($entity = null)
    {
        $this->_str = new StructureChoose();
        $this->_str->setConsole($this->getConsole());
        if ($entity === null) {
            $this->_setEntity();
        } else {
            $this->_entity = $entity;
        }
        $this->_addColumn();
    }

    private function _addColumn()
    {
        $in = 'y';
        while ($in == 'y') {
            $this->_setName();
            $this->_chooseType();
            $this->_isId();
            if ($this->_currentType == 'Integer') {
                $this->_isAuto();
            }
            $this->_createProperty();
            $this->success('Spalte erfolgreich angelegt');
            $this->question('Weiter Spalte anlegen ? ([y]/n)');
            $in = $this->getInput('y');
        }
    }

    private function _isAuto()
    {
        $this->question('Autoincrement ? (y/[n])');
        if ($this->getInput('n') == 'y') {
            $this->_auto = true;
        } else {
            $this->_auto = false;
        }
    }

    private function _isId()
    {
        $this->_auto = false;
        $this->question('Dieser Spalte Primary Key zuweisen ? (y/[n])');
        if ($this->getInput('n') == 'y') {
            $this->_id = true;
        } else {
            $this->_id = false;
        }
    }

    private function _createProperty()
    {
        $content  = '/**' . PHP_EOL;
        if ($this->_id) {
            $content .= "\t" . ' * @ORM\Id' . PHP_EOL;
        }
        if ($this->_auto) {
            $content .= "\t" . ' * @ORM\GeneratedValue(strategy="AUTO")' . PHP_EOL;
        }
        $content .= "\t" . ' * @ORM\Column(type="' . $this->_currentType . '")' . PHP_EOL;
        $content .= "\t" . ' */' . PHP_EOL;
        $content .= "\t" . 'protected $' . $this->_currentName . ';' . PHP_EOL;
        $this->_str->prependMethodToClass($content, $this->_str->getPathFromNamespace($this->_entity));
    }

    private function _chooseType()
    {
        $this->question('Typ ?');
        $this->choose(self::$TYPE);
        $in = $this->getInput();
        if (!key_exists($in, self::$TYPE)) {
            $this->error('Typ existiert nicht!');
            return $this->_chooseType();
        }
        $this->_currentType = self::$TYPE[$in];
    }

    private function _setName()
    {
        $this->question('Spaltenname ?');
        $in = $this->getInput();
        if (empty($in)) {
            $this->error('ungültiger Name!');
            return $this->_setName();
        }
        $this->_currentName = $in;
    }

    private function _setEntity()
    {
        $modules = $this->_str->getAllModules();
        foreach ($modules as $m) {
            $entites = $this->_str->getAllEntitiesByModule($m);
            foreach ($entites as $e) {
                $this->_entities[] = $m . '\Entity\\' . preg_replace('/\.php$/', '', $e);
            }
        }
        $this->_chooseEntity();
    }

    private function _chooseEntity()
    {
        $this->question('Entity wählen!');
        $this->choose($this->_entities);
        $in = $this->getInput();
        if (!key_exists($in, $this->_entities)) {
            $this->error('Entity existiert nicht!');
            return $this->_chooseEntity();
        }
        $this->_entity = $this->_entities[$in];
    }
}