<?php

namespace levitarmouse\core;

use Exception;
use stdClass;

class SmartObject
{
    private $_source;

    public function __construct($source = null)
    {
        $this->_source       = $source;

        if (!empty($source)) {
            $this->analize($source);
        }
    }

    public function getObject($source = null) {

        if ($source) {
            $this->_source = $source;
        }

        $obj = $this->analize();

        return $obj;
    }

    public function analize($src = null) {

        $obj = new Object();

        if (is_string($src)) {
            
            if ($oFromJson = json_decode($src)) {
                foreach ($oFromJson as $key => $value) {
                    $obj->$key = $value;
                }
            } else {
                $obj->string = $src;
            }
        } else {
            if (is_object($src) || is_array($src)) {
                foreach ($src as $key => $value) {
                    $obj->$key = $value;
                }
            } else {
                $type = gettype($src);
                $obj->$type = $src;
            }
        }

        return $obj->getAttribs(true);
    }

}