<?php
namespace Core\Model;

class Create extends AbstractConsoleModel
{
    /**
     * @var \stdClass
     */
    private $_doctrineConfig;

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function create($console)
    {
        $this->_doctrineConfig = new \stdClass();
        $this->_c = $console;
        $this->info(PHP_EOL . PHP_EOL . 'Du kannst jederzeit Abbrechen, indem du "exit" eingibst');
        $this->headline('Doctrine Datenbank Konfiguration' .PHP_EOL);
        $this->setDoctrineConfig();
    }

    public function testColors()
    {
        for($i = 0; $i < 16; $i++) {
            $this->_c->writeLine('test ' .$i, $i);
        }
    }

    public function setDoctrineConfig()
    {
        $this->question('DB Treiber ? [PDO]');
        $this->choose(array('PDO'));
        switch ($this->getInput('0')) {
            case 0:
                $this->_doctrineConfig->driver  = $driver = 'Doctrine\DBAL\Driver\PDOMySql\Driver';
                break;
            default:
                $this->info('unbekannte Eingabe, Wiederhole!');
                $this->setDoctrineConfig();
        }

        $this->question('DB Host ? [127.0.0.1]');
        $this->_doctrineConfig->host = $host = $this->getInput('127.0.0.1');

        $this->question('DB Port ? [3306]');
        $this->_doctrineConfig->port = $port = $this->getInput('3306');

        $this->question('DB Username ? [root]');
        $this->_doctrineConfig->user = $user = $this->getInput('root');

        $this->question('DB Passwort ? []');
        $this->_doctrineConfig->passwd = $passwd = $this->getInput('');

        $this->question('DB Name ?');
        $this->_doctrineConfig->dbname = $dbname = $this->getInput();

        $driverOptions = '';
        if ($this->_doctrineConfig->driver == 'Doctrine\DBAL\Driver\PDOMySql\Driver') {
            $this->question('DB Charset ? [utf8]');
            $this->_doctrineConfig->charset = $charset = $this->getInput('utf8');
            $driverOptions = '\'driverOptions\' => array(PDO::MYSQL_ATTR_INIT_COMMAND => \'SET NAMES ' .
                         $this->_doctrineConfig->charset . '\')';
        }

        $file =
<<<EOL
<?php
return array(
  'doctrine' => array(
    'connection' => array(
      'orm_default' => array(
        'driverClass' => '$driver',
        'params'      => array(
            'host'     => '$host',
            'port'     => '$port',
            'user'     => '$user',
            'password' => '$passwd',
            'dbname'   => '$dbname',
            $driverOptions
        )
      )
    )
  )
);
EOL;

        file_put_contents(getcwd() . '/config/autoload/global.php', $file);
        if ($this->_doctrineConfig->driver == 'Doctrine\DBAL\Driver\PDOMySql\Driver') {
            $this->success('Die Konfigurationsdatei wurde erfolgreich angelegt. Soll die Datenbank erstellt werden ? ([y]/n)');
            if ($this->getInput('y')) {
                $this->createDatabase();
            }
        } else {
            $this->success('Die Konfigurationsdatei wurde erfolgreich angelegt. Datenbank wurde NICHT erstellt.');
        }
    }

    public function createDatabase()
    {
        $config = require getcwd() . '/config/autoload/global.php';
        $config = $config['doctrine']['connection']['orm_default'];
        $c = $config['params'];
        $dns  = 'mysql:host=' . $c['host'];
        $pdo = new \PDO($dns, $c['user'], $c['password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $dbn = $c['dbname'];
        $pdo->exec('create database ' . $dbn . ' character set utf8 collate utf8_general_ci');
        $this->success('Datenbank wurde erfolgreich angelegt');
    }


}