<?php
/**
 * generated by StdCore
 * @copyright Routenbuddy.de
 */
return array(
  'doctrine' => array(
    'connection' => array(
      'orm_default' => array(
        'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'params'      => array(
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'user'     => 'root',
            'password' => '',
            'dbname'   => 'test',
            'driverOptions' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
        )
      )
    )
  )
);