<?php declare(strict_types = 1);

namespace Printdeal\Voyager;

use bitExpert\Disco\AnnotationBeanFactory;
use bitExpert\Disco\BeanFactoryRegistry;
use IceHawk\IceHawk\IceHawk;
use Printdeal\Voyager\Application\Infra\SecurityService;

require(__DIR__ . '/../vendor/autoload.php');

$beanFactory = new AnnotationBeanFactory(Config::class);
BeanFactoryRegistry::register($beanFactory);
//
/** @var SecurityService $securityService */
$securityService = $beanFactory->get('securityService');
$securityService->redirect();

//$securityService->getUsers();

/** @var IceHawk $application */
$application = $beanFactory->get('icehawk');

$application->init();
$application->handleRequest();
