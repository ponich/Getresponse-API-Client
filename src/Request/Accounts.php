<?php

namespace Crmoz\GetresponseApi\Request;


class Accounts extends AbstractAction
{
    /**
     * Список кастомных полей в аккаунте
     * https://apidocs.getresponse.com/api/1.5.0/Accounts/get_account_customs
     *
     * @param $accountDomain
     * @return $this
     */
    public function getAccountCustoms($accountDomain = null)
    {
        $this->method = 'get_account_customs';

        if ($accountDomain) {
            $this->addParam('account_domain', $accountDomain);
        }

        return $this;
    }
}