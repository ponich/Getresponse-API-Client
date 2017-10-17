<?php

namespace Crmoz\GetresponseApi\Request;


class Campaigns extends AbstractAction
{
    /**
     * Получить список кампаний текущей учетной записи
     * https://apidocs.getresponse.com/api/1.5.0/Campaigns/get_campaigns
     *
     * @param array $params
     * @return $this
     */
    public function getСampaigns($params = [])
    {
        $this->method = 'get_campaigns';
        $this->params = $params;

        return $this;
    }
}