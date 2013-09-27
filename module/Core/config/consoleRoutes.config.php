<?php
return array(
    'core-create' => array(
        'options' => array(
            'route'    => 'core-create',
            'defaults' => array(
                'controller' => 'Core\Controller\Setup',
                'action'     => 'create'
            )
        )
    ),
    'core-create-module' => array(
        'options' => array(
            'route'    => 'core-create-module <name>',
            'defaults' => array(
                'controller' => 'Core\Controller\Setup',
                'action'     => 'createModule'
            )
        )
    ),
    'core-create-controller' => array(
        'options' => array(
            'route'    => 'core-create-controller <name>',
            'defaults' => array(
                'controller' => 'Core\Controller\Setup',
                'action'     => 'createController'
            )
        )
    ),
    'core-create-action' => array(
        'options' => array(
            'route'    => 'core-create-action',
            'defaults' => array(
                'controller' => 'Core\Controller\Setup',
                'action'     => 'createAction'
            )
        )
    ),
    'core-create-route' => array(
        'options' => array(
            'route'    => 'core-create-route',
            'defaults' => array(
                'controller' => 'Core\Controller\Setup',
                'action'     => 'createRoute'
            )
        )
    ),
    'core-create-table' => array(
        'options' => array(
            'route'    => 'core-create-table',
            'defaults' => array(
                'controller' => 'Core\Controller\Setup',
                'action'     => 'createTable'
            )
        )
    )
);