<?php
return array(
	'_root_'  => 'categories/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

	'register' => array(array('GET', new Route('register/index')), array('POST', new Route('register/register'))),

	'login' => array(array('GET', new Route('login/index')), array('POST', new Route('login/login'))),
	
	    'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),

	'categories'            => 'categories/index',
	'categories/create'       => 'categories/create',
	'categories/edit/(:id)'   => 'categories/edit/$1',
	'categories/update/(:id)' => 'categories/update/$1',
	'categories/delete/(:id)' => 'categories/delete/$1',
	);
