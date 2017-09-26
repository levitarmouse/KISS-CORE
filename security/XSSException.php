<?php

namespace levitarmouse\common_tools\security;

/**
 * Description of XSSException
 *
 * @author gabriel
 */
class XSSException extends \Exception
{
    protected $wrongValidation;

    public function setWrongs($mixed = null) {
        if (is_string($mixed)) {
            $jsonObj = json_decode($mixed);

            if (is_object($jsonObj)) {
                $this->wrongValidation = $jsonObj;
            }
            else {
                $this->wrongValidation = $mixed;
            }
        } else {
            $this->wrongValidation = $mixed;
        }
        return;
    }

    public function getWrongs() {
        return $this->wrongValidation;
    }
}
