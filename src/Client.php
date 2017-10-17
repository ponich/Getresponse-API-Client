<?php

namespace Crmoz\GetresponseApi;

use Crmoz\GetresponseApi\Request;

class Client
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $url = 'http://api2.getresponse.com';

    /**
     * @var array
     */
    protected $customConfigure = [];

    /**
     * @param string $token
     * @param string|null $url
     * @param array $customConfigure
     */
    public function __construct($token, $url = null, $customConfigure = [])
    {
        $this->token = $token;
        $this->url = (is_null($url)) ? $this->url : $url;
    }

    /**
     * Контакты
     *
     * @return Request\Contacts
     */
    public function Contacts()
    {
        return (new Request\Contacts($this->token, $this->url, $this->customConfigure));
    }

    /**
     * Аккаунт
     *
     * @return Request\Accounts
     */
    public function Accounts()
    {
        return (new Request\Accounts($this->token, $this->url, $this->customConfigure));
    }

    /**
     * Кампании
     *
     * @return Request\Campaigns
     */
    public function Campaigns()
    {
        return (new Request\Campaigns($this->token, $this->url, $this->customConfigure));
    }

    /**
     * Тестирование
     *
     * @return Request\Testing
     */
    public function Testing()
    {
        return (new Request\Testing($this->token, $this->url, $this->customConfigure));
    }


}