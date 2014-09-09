<?php
##
## Custom Request object initialization
## http://laravel.ru/articles/odd_bod/extending-request-and-response#наследование_класса_phprequest-12
##
########################################################################
$app = new Illuminate\Foundation\Application;
########################################################################
//$request = Fideloper\Example\Http\Request::createFromGlobals();
//$app = new Illuminate\Foundation\Application( $request );
########################################################################

$env = $app->detectEnvironment(array(
	'az' => array('Acer_5742G'),
	'kd' => array('DobriyMac.local'),
	'sc' => array('MAC-PC'),
	'server1.grapheme.ru' => array('www.grapheme.ru'),
	'server2.grapheme.ru' => array('grapheme'),
	#'server3.grapheme.ru' => array('samoilovi.ru'), ## FreeBSD, Laravel NOT available! 
));
$app->bindInstallPaths(require __DIR__.'/paths.php');
$framework = $app['path.base'].'/vendor/laravel/framework/src';
require $framework.'/Illuminate/Foundation/start.php';
return $app;