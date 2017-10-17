<?php

namespace Crmoz\GetresponseApi\Exceptions;

class RequestErrorException extends HandleException
{
    protected $code = 1003;

    protected $message = "Ошибка запроса";

}