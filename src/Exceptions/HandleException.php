<?php

namespace Crmoz\GetresponseApi\Exceptions;

class HandleException extends \ErrorException
{
    public function handle($message)
    {
        $this->message = $message;

        return $this;
    }
}