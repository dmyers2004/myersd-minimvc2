<?php

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

//error_reporting(0);

chdir('..');

$loader = require 'vendor/autoload.php';

$loader->add('', getcwd().'/app');

$c = new \Pimple;

/* load our config, input & output settings (or mocks for testing) */
$config_file = (getenv('CONFIG')) ? getenv('CONFIG') : 'config.php';

require 'app/'.$config_file;

/* setup our stuff */
$c['Request'] = $c->share(function($c) {
    return new \Symfony\Component\HttpFoundation\Request($c['input']['get'],$c['input']['post'],$c['input']['user'],$c['input']['cookie'],$c['input']['files'],$c['input']['server']);
});

$c['Response'] = $c->share(function() {
	return new \Symfony\Component\HttpFoundation\Response();
});

$c['Router'] = $c->share(function($c) {
	return new \Symfony\Component\Routing();
});

$routes = new \Symfony\Component\Routing\RouteCollection();

$routes->add('leap_year', new \Symfony\Component\Routing\Route('/is_leap_year/{year}',array('year' => null, '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction')));
$routes->add('root', new \Symfony\Component\Routing\Route('/', array('_controller' => 'Calendar\\Controller\\MainController::indexAction')));
$routes->add('hello', new \Symfony\Component\Routing\Route('/hello/{name}',array('name' => null, '_controller' => 'Calendar\\Controller\\MainController::helloAction')));

/* Set up the context and establish whether this request matches a route */
$context = new \Symfony\Component\Routing\RequestContext();
$context->fromRequest($c['Request']);
$matcher = new Symfony\Component\Routing\Matcher\UrlMatcher($routes, $context);
 
$match = $matcher->match($c['Request']->getPathInfo());

print_r($match);

die();

/*
$c['Router'] = $c->share(function($c) {
	return new Router($c);
});

$c['Dispatcher'] = $c->share(function($c) {
	return new dispatcher($c);
});
*/

//$cookies = new Symfony\Component\HttpFoundation\Cookie();

/* call our router and dispatch */
$c['Router']->route();
$c['Dispatcher']->dispatch();

$c['Response']->headers->set('Content-Type', 'text/html');
$c['Response']->setStatusCode(200);
$c['Response']->setCharset('UTF-8');
$c['Response']->prepare($c['Request']);
$c['Response']->send();