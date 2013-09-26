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
		'Regex',
		'Console'		
	);
	
	private $_type;
	
	public function create()
	{
		$str = new StructureChoose();
		$str->setConsole($this->getConsole());
		$this->_module 	   = $str->chooseModule();
		$this->_controller = $str->chooseController();
		$this->_action 	   = $str->chooseAction();
		$this->_name = $this->_getName();
		$this->_type = $this->_getType();
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