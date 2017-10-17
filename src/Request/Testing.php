<?php

namespace Crmoz\GetresponseApi\Request;


class Testing extends AbstractAction
{
    /**
     * Test connection with API
     * https://apidocs.getresponse.com/api/1.5.0/testing/ping
     *
     * @return $this
     */
    public function ping()
    {
        $this->method = 'ping';
        $this->params = [];

        return $this;
    }
}