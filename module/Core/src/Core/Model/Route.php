<?php
namespace Core\Model;

use Core\Model\AbstractConsoleModel;

class Route extends AbstractConsoleModel
{
	private $_name;
	private $_module;
	private $_controller;
	private $_action;

	private static $_types = array(
		'Literal',
		'Segment',
		'Regex'
	);

	private $_type;

	public function create($parmas = null)
	{
		$str = new StructureChoose();
		$str->setConsole($this->getConsole());

		if (!empty($parmas['module'])) {
		    $this->_module = $parmas['module'];
		} else {
		    $this->_module 	   = $str->chooseModule();
		}
		if (!empty($parmas['action'])) {
		    $this->_action = $parmas['action'];
		} else {
		    $this->_action 	   = $str->chooseAction();
		}
		if (!empty($parmas['controller'])) {
		    $this->_controller = $parmas['controller'];
		} else {
		    $this->_controller 	   = $str->chooseController();
		}

		$this->_name = $this->_getName();
		$this->_type = $this->_getType();
		$class = 'Core\Model\Routes\\' . $this->_type;
		$class = new $class();
		$class->setConsole($this->getConsole());
		$options = array(
		    'name'       => $this->_name,
		    'module'     => $this->_module,
		    'controller' => $this->_controller,
		    'action'     => $this->_action
		);
		$class->create($options);
		$this->success('Route wurde erfolgreich angelegt.');
	}

	private function _getType()
	{
		$this->question('Typ der Route ? [Literal]');
		$this->choose(self::$_types);
		$in = $this->getInput('0');
		if (!key_exists($in, self::$_types)) {
			$this->error('Dieser Typ existiert nicht ("exit" um zu beenden)');
			return $this->_getType();
		}
		return self::$_types[$in];
	}

	private function _getName()
	{
		$this->question('(Aufruf-)Name der Route ?');
		$in = $this->getInput();
		if (empty($in)) {
			$this->error('Eingabe ungültig ("exit" um zu beenden)');
			return $this->_getName();
		}
		return $in;
	}
}