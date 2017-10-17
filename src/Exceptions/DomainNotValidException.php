<?php

namespace Crmoz\GetresponseApi\Exceptions;

class DomainNotValidException extends HandleException
{
    protected $code = 1001;

    protected $message = "Ошибка валидации URL адреса";

}