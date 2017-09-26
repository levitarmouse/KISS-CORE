<?php

namespace levitarmouse\common_tools\security;

/**
 * Description of InjectionTestResponse
 *
 * @author gabriel
 */
class InjectionTestResult
{
    protected $status;
    protected $validList;
    protected $invalidList;
    protected $original;
    protected $sanitized;
    protected $countTotal;
    protected $countValid;
    protected $countInValid;
    public $emitMessageError;

    public function __construct()
    {
        $this->status = 'VALID';
        $this->validList = array();
        $this->invalidList = array();

        $this->original  = null;
        $this->sanitized = null;

        $this->countTotal = 0;
        $this->countValid = 0;
        $this->countInValid = 0;
    }

    public function setValidList($index, $mixed, $array = null) {
        if ($array) {
            $this->validList = $array;
            $this->countValid = count($array);
            $this->countTotal += count($array);
        } else {
            if ($mixed) {
                $this->countTotal += 1;
                $this->validList[$index] = $mixed;
            }
        }
    }

    public function setInvalidList($index, $mixed, $array = null) {
        if ($array) {
            $this->invalidList = $array;
            $this->countInValid = count($array);
            $this->countTotal += count($array);
        } else {
            if ($mixed) {
                $this->countTotal += 1;
                $this->invalidList[$index] = $mixed;
            }
        }
        if (count($this->countInValid) > 0 ) {
            $this->status = 'INVALID';
        }
    }

    public function emitMessageError() {
        return $this->emitMessageError;
    }

    public function setOriginal($mixed) {
        $this->original = $mixed;
    }

    public function getOriginal() {
        return $this->original;
    }

    public function getSanitized() {
        return $this->sanitized;
    }

    public function setSanitized($mixed) {
        $this->sanitized = $mixed;
    }

    public function setEmitMessageError($value = false) {
        $this->emitMessageError = $value;
    }

    public function getInvalid() {
        return $this->invalidList;
    }

    public function getCountvalid() {
        return count($this->validList);
    }

    public function getValid() {
        return $this->validList;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCountInvalid() {
        return count($this->invalidList);
    }
}