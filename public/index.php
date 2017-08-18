<?php declare(strict_types = 1);

namespace Printdeal\Voyager;

use bitExpert\Disco\AnnotationBeanFactory;
use bitExpert\Disco\BeanFactoryRegistry;
use IceHawk\IceHawk\IceHawk;

require(__DIR__ . '/../vendor/autoload.php');

$beanFactory = new AnnotationBeanFactory(Config::class);
BeanFactoryRegistry::register($beanFactory);

//$securityService = $beanFactory->get('securityService');

/** @var IceHawk $application */
$application = $beanFactory->get('icehawk');

$application->init();
$application->handleRequest();
