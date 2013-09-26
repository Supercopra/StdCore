<?php
namespace Core\Model;

use Core\Model\AbstractConsoleModel;

class StructureChoose extends AbstractConsoleModel
{
	private $_module;
	private $_controller;
	private $_action;

	private $_resultsDir;
	private $_actions;

	/**
	 * @return the $_module
	 */
	public function getModule() {
		return $this->_module;
	}

	/**
	 * @return the $_controller
	 */
	public function getController() {
		return $this->_controller;
	}

	/**
	 * @return the $_action
	 */
	public function getAction() {
		return $this->_action;
	}

	/**
	 * @param field_type $_module
	 */
	public function setModule($_module) {
		$this->_module = $_module;
	}

	/**
	 * @param field_type $_controller
	 */
	public function setController($_controller) {
		$this->_controller = $_controller;
	}

	/**
	 * @param field_type $_action
	 */
	public function setAction($_action) {
		$this->_action = $_action;
	}

	private function _getResultsDir()
	{
		if ($this->_resultsDir === null) {
			$dirs = array_filter(glob(getcwd() . '/module/*'), 'is_dir');
			$resultdirs = array();
			foreach($dirs as $dir) {
				$resultdirs[] = basename($dir);
			}
			$this->_resultsDir = $resultdirs;
		}
		return $this->_resultsDir;
	}

	public function chooseModule()
	{
		$this->question('Module ?');
		$resultdirs = $this->_getResultsDir();
		$this->choose($resultdirs);

		$input = $this->getInput();
		if(!empty($resultdirs[$input])) {
			$this->_module = $module = $resultdirs[$input];
			return $module;
		} else {
			$this->error('Dieses Modul existiert nicht!');
			$this->chooseModule();
		}
	}

	public function chooseController()
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

	private function _getActions()
	{
		if ($this->_actions === null) {
			$path  = getcwd() . '/module/' . $this->_module . '/src/';
			$path .= $this->_module . '/Controller/' . $this->_controller . '.php';
			require $path;
			$namespace = $this->_module . '\Controller\\' . $this->_controller;
			$class   = new \ReflectionClass($namespace);
			$methods = $class->getMethods();
			//@todo rausfiltern von methoden durch herritage
			$this->_actions = array();
			foreach ($methods as $method) {
				if (preg_match('/Action$/', $method->name)) {
					$this->_actions[] = $method->name;
				}
			}
		}
		return $this->_actions;
	}

	public function chooseAction()
	{
		$actions = $this->_getActions();
		$this->question('Name der Action ?');
		$this->choose($actions);
		$in = $this->getInput();
		if (!key_exists($in, $actions)) {
			$this->error('Diese Action existiert nicht!');
			return $this->chooseAction();
		}
		$this->_action = $actions[$in];
		return $actions[$in];
	}
}