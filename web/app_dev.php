<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\IpUtils;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$clientIps = $request->getClientIps();
$lastClientIp = $clientIps[count($clientIps) - 1];
if (!IpUtils::checkIp($lastClientIp, ['127.0.0.1', 'fe80::1', '::1', '193.108.249.215', '78.137.6.142', '10.0.0.0/8'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
