<?php
class SysCallException extends sfException
{
    public function __construct($syscall, $message = null, $code = null, $previous = null)
    {
        parent::__construct(is_null($message) ? sprintf("%s", $syscall) : sprintf("%s: %s", $syscall), $code, $previous );
    }
}