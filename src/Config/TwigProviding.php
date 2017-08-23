<?php


namespace Printdeal\Voyager\Config;


use Twig_Environment;
use Twig_Loader_Filesystem;

trait TwigProviding
{
    private function getTwig($config){
        $loader = new Twig_Loader_Filesystem($config['pathToTemplates']);
        $twig = new Twig_Environment($loader, array(

        ));
        return $twig;
    }
}