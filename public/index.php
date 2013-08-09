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

$request_extras = array();

/* is this http or https? */
$request_extras['is_https'] = (strstr('https',strtolower($c['Request']->server->get('SERVER_PROTOCOL'))) === TRUE);

/* is this a ajax request? */
$request_extras['is_ajax'] = ($c['Request']->server->get('HTTP_X_REQUESTED_WITH') !== NULL && strtolower($c['Request']->server->get('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

/* what is the base url */
$request_extras['base_url'] = ($request_extras['is_https'] ? 'https' : 'http').'://'.trim($c['Request']->server->get('HTTP_HOST').dirname($c['Request']->server->get('SCRIPT_NAME')),'/');

/* what is the requested method? */
$request_extras['request'] = ucfirst(strtolower($c['Request']->server->get('REQUEST_METHOD')));

/* what is the uri */
$request_extras['uri'] = trim(urldecode(substr(parse_url($c['Request']->server->get('REQUEST_URI'),PHP_URL_PATH),strlen(dirname($c['Request']->server->get('SCRIPT_NAME'))))),'/');

/* put these in parameters */
$request_extras['parameters'] = explode('/',$request_extras['uri']);

$c['Request.extras'] = $request_extras;

$c['Router']->route();
$c['Dispatcher']->dispatch();

//$c['Response']->setContent('Hello World');
$c['Response']->headers->set('Content-Type', 'text/plain');
$c['Response']->setStatusCode(200);
$c['Response']->setCharset('UTF-8');
$c['Response']->prepare($c['Request']);
$c['Response']->send();