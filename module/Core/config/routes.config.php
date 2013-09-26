<?php
return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/',
            'defaults' => array(
                'controller' => 'Application\Controller\Index',
                'action'     => 'index',
            )
        )
    ),
    'application' => array(
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/application',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller'    => 'Index',
                'action'        => 'index',
            )
        )
    )
);