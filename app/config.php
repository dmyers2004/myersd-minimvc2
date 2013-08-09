<?php
$c['input'] = array(
	'get' => $_GET,
	'post' => $_POST,
	'user' => array(),
	'cookie' => $_COOKIE,
	'files' => $_FILES,
	'server' => $_SERVER
);

$c['routes'] = array(
	'#^(http|https)/(Ajax|)/(Get)/(red)$#i' => '\\\example\\\controllers\\\$4Controller/index$2Action',
	'#^(http|https)/(Ajax|)/(Get)/(red)/([a-zA-Z0-9-_]*)(.*)$#i' => '\\\example\\\controllers\\\$4Controller/$5$2Action$6',

	'#^(http|https)/(Ajax|)/(Get)/(blue[-|_]shoes)$#i' => '\\\example\\\controllers\\\blueShoesController/index$2Action',
	'#^(http|https)/(Ajax|)/(Get)/(blue[-|_]shoes)/([a-zA-Z0-9-_]*)(.*)$#i' => '\\\example\\\controllers\\\blueShoesController/$5$2Action$6',

	/* default */
	'#^(http|https)/(Ajax|)/(Get)/$#i' => '\controllers\\\mainController/index$2Action',
	'#^(http|https)/(Ajax|)/(Get)/([a-zA-Z0-9-_]*)$#i' => '\controllers\\\$4Controller/index$2Action',
	'#^(http|https)/(Ajax|)/(Get)/([a-zA-Z0-9-_]*)/([a-zA-Z0-9-_]*)(.*)$#i' => '\controllers\\\$4Controller/$5$2Action$6',

	'#^(http|https)/(Ajax|)/(Post|Delete|Put)/$#i' => '\controllers\\\mainController/index$2$3Action',
	'#^(http|https)/(Ajax|)/(Post|Delete|Put)/([a-zA-Z0-9-_]*)$#i' => '\controllers\\\$4Controller/index$2$3Action',
	'#^(http|https)/(Ajax|)/(Post|Delete|Put)/([a-zA-Z0-9-_]*)/([a-zA-Z0-9-_]*)(.*)$#i' => '\controllers\\\$4Controller/$5$2$3Action$6'
);
