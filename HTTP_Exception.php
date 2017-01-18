<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
//    
//    public function message() {
//        return parent::getMessage();
//    }
}
