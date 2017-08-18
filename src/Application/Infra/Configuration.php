<?php

namespace Printdeal\Voyager\Application\Infra;

class Configuration
{
    public function securityService() :SecurityService
    {
        return new SecurityService();
    }
}