<?php

namespace levitarmouse\common_tools\security;

class InjectionCheckerRequest {

    public $types;
    public $autoTrim;
    public $sanitChar;
    public $oLogger;
    public $omissions;
    public $specialChars;
    public $emitMessageError;

    public $checkJS;

    public function __construct() {
        $this->types = 'NUMBERS-ALPHA';
        $this->autoTrim = false;
        $this->sanitChar = ' ';
        $this->oLogger = null;
        $this->omissions = array();
        $this->specialChars = array();
        $this->emitMessageError = false;

        $this->checkJS = true;
    }
}
