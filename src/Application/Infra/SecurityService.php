<?php

namespace Printdeal\Voyager\Application\Infra;

use Auth0\SDK\API\Management;
use Printdeal\Voyager\Application\Infra\SecurityService\Classes\UserInfo;
use Printdeal\Voyager\Application\Infra\SecurityService\Exceptions\ConfigNotFoundException;
use Auth0\SDK\Auth0;

class SecurityService
{
    /**
     * @var Auth0
     */
    private $auth0;

    /**
     * @var array
     */
    private $configs;

    /**
     * SecurityService constructor.
     * @param array $configs
     * @throws ConfigNotFoundException
     */
    public function __construct(array $configs)
    {
        if (empty($configs)) {
            throw new ConfigNotFoundException();
        }
        $this->configs = $configs;
    }

    /**
     * @return null|UserInfo
     */
    public function redirect()
    {
        $userInfo = $this->getUserInfo();
        if (!$userInfo) {
            $this->getAuth0()->login();
        }
        return $userInfo;
    }

    /**
     * @return null|UserInfo
     */
    public function getUserInfo()
    {
        $auth0 = $this->getAuth0();
        $userInfo = $auth0->getUser();

        if (!$userInfo) {
            return null;
        }
        return UserInfo::createFromUserInfo($userInfo);
    }

    /**
     * @param string $token
     * @param string $domain
     * @return array
     */
    public function getUsers(string $token, string $domain)
    {
        $management = new Management($token, $domain);
        $users = $management->users->getAll();
        $userInfos = [];
        foreach ($users as $userInfo) {
            $userInfo = UserInfo::createFromUserInfo($userInfo);
            $userInfos[] = $userInfo;
        }
        return $userInfos;
    }

    /**
     * @return Auth0
     */
    private function getAuth0()
    {
        if (!$this->auth0) {
            $this->auth0 = new Auth0($this->configs);
        }
        return $this->auth0;
    }
}
