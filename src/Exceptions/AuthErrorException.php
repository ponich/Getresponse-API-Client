<?php

namespace Crmoz\GetresponseApi\Exceptions;

class AuthErrorException extends HandleException
{
    protected $code = 1002;

    protected $message = "Ошибка авторизации API";

}