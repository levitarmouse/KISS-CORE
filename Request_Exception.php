<?php


namespace levitarmouse\core;

/**
 * Description of HTTP_Exception
 *
 * @author gabriel
 */
class Request_Exception extends HTTP_Exception {

    public function __construct(string $message = "", int $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
