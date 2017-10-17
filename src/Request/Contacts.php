<?php

namespace Crmoz\GetresponseApi\Request;


class Contacts extends AbstractAction
{
    /**
     * Получить список контактов текущей учетной записи
     * https://apidocs.getresponse.com/api/1.5.0/Contacts/get_contacts
     *
     * @param array $params
     * @return $this
     */
    public function getContacts($params = [])
    {
        $this->method = 'get_contacts';
        $this->params = $params;

        return $this;
    }

    /**
     * Дополнительные поля
     * https://apidocs.getresponse.com/api/1.5.0/Contacts/get_contact_customs
     *
     * @param string|null $contact
     * @return $this
     */
    public function getContactCustoms($contact = null)
    {
        $this->method = 'get_contact_customs';

        if ($contact) {
            $this->addParam('contact', $contact);
        }

        return $this;
    }


    /**
     * Информация о геолокации контакта
     * https://apidocs.getresponse.com/api/1.5.0/Contacts/get_contact_geoip
     *
     * @param string|null $contact
     * @return $this
     */
    public function getContactGeoip($contact = null)
    {
        $this->method = 'get_contact_geoip';

        if ($contact) {
            $this->addParam('contact', $contact);
        }

        return $this;
    }
}