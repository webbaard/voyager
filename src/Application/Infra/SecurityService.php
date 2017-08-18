<?php

namespace Printdeal\Voyager\Application\Infra;

use Printdeal\Voyager\Application\Infra\SecurityService\Exceptions\ConfigNotFoundException;
use Auth0\SDK\Auth0;

class SecurityService
{
    private $auth0;

    private $configs;

    public function __construct(array $configs)
    {
        if (empty($configs)) {
            throw new ConfigNotFoundException();
        }
        $this->configs = $configs;

    }

    public function checkedLogin()
    {
        $userInfo = $this->getUserInfo();
        if (!$userInfo) {
            $this->getAuth0()->login();
        }
        return $userInfo;
    }

    public function getUserInfo()
    {
        $auth0 = $this->getAuth0();
        $userInfo = $auth0->getUser();

        if (!$userInfo) {
            return null;
        }

        return $userInfo;
    }

    private function getAuth0()
    {
        if (!$this->auth0) {
            $this->auth0 = new Auth0($this->configs);
        }
        return $this->auth0;
    }
}
