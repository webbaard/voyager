<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager;

use IceHawk\IceHawk\IceHawk;

require(__DIR__ . '/../vendor/autoload.php');

$config      = new IceHawkConfig();
$delegate    = new IceHawkDelegate();
$application = new IceHawk( $config, $delegate );

$application->init();
$application->handleRequest();
