<?php
$domainArray = explode('.', $_SERVER['HTTP_HOST']);
$subdomain = $domainArray[0];

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$debug = false;
switch($subdomain)
{
	case 'local':
		$env = 'dev';
		$debug = true;
		break;
	case 'www':
	default:
		$env = 'prod';
		break;
}

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$kernel->handle(Request::createFromGlobals())->send();
