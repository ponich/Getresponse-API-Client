## Getresponse API Client
[![Packagist License](https://poser.pugx.org/crmoz/getresponse-api/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/crmoz/getresponse-api/version.png)](https://packagist.org/packages/crmoz/getresponse-api)
[![Total Downloads](https://poser.pugx.org/crmoz/getresponse-api/d/total.png)](https://packagist.org/packages/crmoz/getresponse-api)

### Важно: На данный момент библиотека заточена под Laravel >5.4

Реализация клиента для Getresponse API v1.5

### Установка

``composer require crmoz/getresponse-api:dev-master``


#### @todo

- написать небольшую замену `GuzzleHttp`
- добавить собственные helper functions, уменьшения зависимостей Laravel (array_get, array_set)
- заменить `Crmoz\GetresponseApi\Request\AbstractAction::newCarbon`
- тестирование