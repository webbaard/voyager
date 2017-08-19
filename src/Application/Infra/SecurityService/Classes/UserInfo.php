<?php

namespace Printdeal\Voyager\Application\Infra\SecurityService\Classes;

class UserInfo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $department;

    /**
     * @var string
     */
    private $job;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var int
     */
    private $portalId;

    /**
     * @var bool
     */
    private $circleCaptain;

    /**
     * @var int
     */
    private $circleId;

    /**
     * UserInfo constructor.
     * @param string $name
     * @param string $email
     * @param bool $enabled
     * @param string $city
     * @param string $country
     * @param string $department
     * @param string $job
     * @param string $street
     * @param string $phoneNumber
     * @param array $groups
     * @param int $portalId
     * @param bool $circleCaptain
     * @param int|null $circleId
     */
    private function __construct(
        string $name,
        string $email,
        bool $enabled,
        string $city,
        string $country,
        string $department,
        string $job,
        string $street,
        string $phoneNumber,
        array $groups,
        int $portalId,
        bool $circleCaptain,
        int $circleId = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->enabled = $enabled;
        $this->city = $city;
        $this->country = $country;
        $this->department = $department;
        $this->job = $job;
        $this->street = $street;
        $this->phoneNumber = $phoneNumber;
        $this->groups = $groups;
        $this->portalId = $portalId;
        $this->circleCaptain = $circleCaptain;
        $this->circleId = $circleId;
    }

    public static function createFromUserInfo(array $userInfo)
    {
        return new self(
            $userInfo['name'] ?? '',
            $userInfo['email'] ?? '',
            $userInfo['account_enabled'] ?? false,
            $userInfo['city'] ?? '',
            $userInfo['country'] ?? '',
            $userInfo['department'] ?? '',
            $userInfo['job_title'] ?? '',
            $userInfo['street'] ?? '',
            $userInfo['phonenumber'] ?? '',
            self::getGroupsFromUserInfo($userInfo),
            $userInfo['app_metadata']['portalId'] ?? 0,
            $userInfo['app_metadata']['isCircleCaptain'] ?? false,
            $userInfo['app_metadata']['circleId'] ?? null
        );
    }

    /**
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail() :string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isEnabled() :bool
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getCity() :string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountry() :string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getDepartment() :string
    {
        return $this->department;
    }

    /**
     * @return string
     */
    public function getJob() :string
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getStreet() :string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getPhoneNumber() :string
    {
        return $this->phoneNumber;
    }

    /**
     * @return array
     */
    public function getGroups() :array
    {
        return $this->groups;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasGroup(string $name) :bool
    {
        return array_key_exists($name, $this->groups);
    }

    /**
     * @return int
     */
    public function getPortalId() :int
    {
        return $this->portalId;
    }

    /**
     * @return bool
     */
    public function isCircleCaptain() :bool
    {
        return $this->circleCaptain;
    }

    /**
     * @return int|null
     */
    public function getCircleId()
    {
        return $this->circleId;
    }

    /**
     * @param array $userInfo
     * @return array
     */
    private static function getGroupsFromUserInfo(array $userInfo) :array
    {
        $groups = [];
        if (isset($userInfo['authorization']['groups']) && is_array($userInfo['authorization']['groups'])) {
            foreach ($userInfo['authorization']['groups'] as $groupName) {
                $groups[$groupName] = $groupName;
            }
        }
        return $groups;
    }
}
