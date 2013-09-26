<?php
namespace Core\Model;

use Core\Model\AbstractModel;


abstract class AbstractConsoleModel extends AbstractModel
{
    /**
     * Farben
     */
    const RED     = '10';
    const GREEN   = '11';
    const YELLOW_H= '12';
    const YELLOW  = '4';
    const BLUE    = '13';
    const TEXT    = '8';

    /**
     * @var \Zend\Console\Adapter\AdapterInterface
     */
    protected $_c;

    public function setConsole($c)
    {
        $this->_c = $c;
    }

    /**
     * @return \Zend\Console\Adapter\AdapterInterface
     */
    public function getConsole()
    {
        return $this->_c;
    }

    public function success($text)
    {
        $this->_c->writeLine($text, self::GREEN);
    }

    public function error($text)
    {
        $this->_c->writeLine($text, self::RED);
    }

    public function getInput($default = null)
    {
        $this->_c->setColor(self::TEXT);
        $input = trim($this->_c->readLine());
        if (empty($input) && $default != null) {
            $input = $default;
        }
        if ($input == 'exit') {
            $this->_c->setColor(null);
            $this->error('Abgebrochen');
            $this->_c->setColor(null);
            exit;
        }
        return $input;
    }

    public function choose($list)
    {
        foreach ($list as $k => $l) {
            $this->_c->writeLine($k . ': ' . $l, self::YELLOW);
        }
    }

    public function info($text)
    {
        $this->_c->writeLine($text, self::YELLOW);
    }

    public function question($text)
    {
        $this->_c->writeLine($text, self::YELLOW_H);
    }

    /**
     * @param string $text
     */
    public function headline($text)
    {
        $this->_c->writeLine($text, self::BLUE);
    }
}