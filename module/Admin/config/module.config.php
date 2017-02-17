<?php
return array(
	/**
	 * @todo Set controller aliases
	 */
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\User' => 'Admin\Controller\UserController'
        )
    ),

	/**
	 * @todo Routing configuration
	 */
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                )
            ),
            'user-admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/users[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'User',
                        'action' => 'index'
                    )
                )
            ),
            'admin-login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'login'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'logout'
                    )
                )
            )


        )
    ),

	/**
	 * @todo Configuring models as a services
	 */
    'di' => array(
        'services' => array(
            'Admin\Model\BarTable' => 'Admin\Model\BarTable',
            'Admin\Model\UserTable' => 'Admin\Model\UserTable',
            'Admin\Model\AdminTable' => 'Admin\Model\AdminTable',
            'Admin\Model\UserSessionTable' => 'Admin\Model\UserSessionTable',
            'Admin\Model\UserClickBarTable' => 'Admin\Model\UserClickBarTable',
        )
    ),

	/**
	 * @todo Layout and views pages configuration
	 */
	 'view_manager' => array(
         'template_path_stack' => array(
             'admin' => __DIR__ . '/../view'
         ),

         'template_map' => array(
             'login' => __DIR__ . '/../view/layout/login.phtml'
         )
     )
);
