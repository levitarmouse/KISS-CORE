<?php

namespace levitarmouse\core;

/**
 * Description of HTTP_Exception
 *
 * @author gabriel
 */
class HTTP_Exception extends \Exception {

    public $errorId;
    public $httpCode;
    public $httpMethod;
    public $exceptionDescription;

    public function __construct(string $message = "", int $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
