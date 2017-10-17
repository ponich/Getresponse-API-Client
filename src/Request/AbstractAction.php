<?php

namespace Crmoz\GetresponseApi\Request;

use Crmoz\GetresponseApi\Exceptions;
use GuzzleHttp;

abstract class AbstractAction
{
    /**
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $customConfigure = [];

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $requestMethod = 'POST';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @param string $token
     * @param string $url
     * @param array $customConfigure
     */
    public function __construct($token, $url, $customConfigure = [])
    {
        $this->token = $token;
        $this->url = $this->domainValidate($url);
        $this->customConfigure = $customConfigure;

        $this->httpClient = $this->getHttpClient(
            array_get($this->customConfigure, 'http.client', [])
        );
    }

    /**
     * Валидация домена
     *
     * @param $url
     * @return string
     * @throws Exceptions\DomainNotValidException
     */
    protected function domainValidate($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = "https://{$url}";

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw (new Exceptions\DomainNotValidException())->handle("Ошибка валидации URL адреса: {$url}");
            }
        }

        return $url;
    }

    /**
     * Добавить параметр в запрос
     *
     * @param $key
     * @param string $operator
     * @param null $value
     * @return $this
     */
    public function addParam($key, $operator = '=', $value = null)
    {
        // значения по умолчанию
        $operatorOrigin = $operator;
        $valueOrigin = $value;

        $operator = (is_null($valueOrigin)) ? ':' : $operatorOrigin;
        $value = (is_null($valueOrigin)) ? $operatorOrigin : $valueOrigin;

        // проверяем, являеться ли строка датой
        $type = gettype($value);
        $type = ($type == 'string' && $carbonDate = $this->newCarbon($value)) ? 'date' : $type;
        $value = ($type == 'date') ? $carbonDate : $value;
        $value = ($type == 'date') ? $value->toDateString() : $value;

        // парсим оператор сравнения
        $operator = $this->getParseOperator($operator, $type);

        // форматирование значения
        $value = ($type == 'array')
            ? array_merge(array_get($this->params, $key, []), $value)
            : array_merge(array_get($this->params, $key, []), [$operator => $value]);

        $value = ($operator == ':') ? $operatorOrigin : $value;

        // задать параметр
        array_set($this->params, $key, $value);

        return $this;
    }

    /**
     * Вернет распарсенный оператор для сравнения в Getresponse API
     *
     * @param $operator
     * @param $type string|date|integer
     * @return null|string
     */
    protected function getParseOperator($operator, $type = 'string')
    {
        $operator = strtolower($operator);

        if ($type == 'string') {
            $operator = ($operator == '=') ? 'EQUALS' : $operator;
            $operator = ($operator == '!=' || $operator == '<>') ? 'NOT_EQUALS' : $operator;
            $operator = ($operator == 'like') ? 'CONTAINS' : $operator;
            $operator = ($operator == 'not_like') ? 'NOT_CONTAINS' : $operator;
        }

        if ($type == 'date') {
            $operator = ($operator == '=') ? 'AT' : $operator;
            $operator = ($operator == '>=' || $operator == '>') ? 'FROM' : $operator;
            $operator = ($operator == '<=' || $operator == '<') ? 'TO' : $operator;
        }

        if ($type == 'integer') {
            $operator = ($operator == '<') ? 'LESS' : $operator;
            $operator = ($operator == '<=') ? 'LESS_OR_EQUALS' : $operator;
            $operator = ($operator == '=') ? 'EQUALS' : $operator;
            $operator = ($operator == '>=') ? 'GREATER_OR_EQUALS' : $operator;
            $operator = ($operator == '>') ? 'GREATER' : $operator;
        }


        return $operator;
    }

    /**
     * Отправка запроса Getresponse API
     *
     * @param string $method
     * @param null $url
     * @return array
     */
    public function request($method = 'POST', $url = null)
    {
        $url = (is_null($url)) ? $this->url : $url;

        $responseRawData = $this->httpClient
            ->request($method, $url, ['body' => $this->getRequestBodyParams()])
            ->getBody()
            ->getContents();

        return $this->parseResponseData($responseRawData);
    }


    /**
     * Парсит ответ от API
     *
     * @param $responseRawData
     * @return mixed
     * @throws Exceptions\RequestErrorException
     */
    protected function parseResponseData($responseRawData)
    {
        $response = json_decode($responseRawData, true);

        $error = array_get($response, 'error');
        $result = array_get($response, 'result');

        if ($error) {
            throw new Exceptions\RequestErrorException(array_get($error, 'message'), array_get($error, 'code'));
        }

        return $result ?: [];
    }

    /**
     * Вернет уже конвертированный обьект тела запроса
     *
     * @param string|null $token
     * @param string|null $method
     * @param array $params
     * @param mixed $id
     * @return string
     */
    protected function getRequestBodyParams($token = null, $method = null, $params = [], $id = null)
    {
        $token = (is_null($token)) ? $this->token : $token;
        $method = (is_null($method)) ? $this->method : $method;
        $params = (count($params) == 0) ? $this->params : $params;
        $id = (is_null($id)) ? $this->id : $id;

        $bodyRaw = json_encode($bodyArray = [
            'method' => $method,
            'params' => [$token, (object)$params],
            'id' => $id
        ], JSON_UNESCAPED_UNICODE);

        return $bodyRaw;
    }

    /**
     * Инстализация HTTP клиента
     *
     * @param array $customConfigure
     * @param boolean $override
     * @return GuzzleHttp\Client
     */
    protected function getHttpClient($customConfigure = [], $override = false)
    {
        $config = array_merge($customConfigure, [
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);

        $this->httpClient = ($this->httpClient && !$override)
            ? $this->httpClient
            : new GuzzleHttp\Client($config);

        return $this->httpClient;
    }

    /**
     * Создать экземпляр Carbon с указаной датой
     * В случаи неудачи, вернет FALSE
     *
     * @param $str
     * @param string $format
     * @return \Carbon\Carbon|boolean
     */
    protected function newCarbon($str, $format = 'Y-m-d')
    {
        try {
            if (\Carbon\Carbon::createFromFormat($format, $str) !== false) {
                return new \Carbon\Carbon($str);
            }
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return false;
    }
}