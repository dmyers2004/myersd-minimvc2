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

$c['Response'] = $c->share(function($c) {
	return new \Symfony\Component\HttpFoundation\Response();
});

$c['Router'] = $c->share(function($c) {
	return new Router($c);
});

$c['Dispatcher'] = $c->share(function($c) {
	return new dispatcher($c);
});

//$cookies = new Symfony\Component\HttpFoundation\Cookie();

/* call our router and dispatch */
$c['Router']->route();
$c['Dispatcher']->dispatch();

//$c['Response']->setContent('Hello World');
$c['Response']->headers->set('Content-Type', 'text/plain');
$c['Response']->setStatusCode(200);
$c['Response']->setCharset('UTF-8');
$c['Response']->prepare($c['Request']);
$c['Response']->send();