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
    )
);