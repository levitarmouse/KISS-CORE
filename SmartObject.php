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

        if (is_a($source, 'levitarmouse\core\Object')) {
            error_log('');
        } else {
            if (!empty($source)) {
                return $this->analize($source);
            }            
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

        $obj = new BasicObject();

        if (is_string($src)) {
            
            if ($oFromJson = json_decode($src)) {
                foreach ($oFromJson as $key => $value) {
                    $obj->$key = $value;
                }
            } else {
                $obj->string = $src;
            }
        } else {
            if (is_object($src)) {
                return $src;
            }
                
            if (is_array($src)) {
                foreach ($src as $key => $value) {
                    
                    if (is_string($value )) {
                        $objProspect = json_decode($value);
                        if (is_object($objProspect) || is_array($objProspect)) {
                            $obj->$key = $this->analize($objProspect);
                        } else {
                            $obj->$key = $value;
                        }                        
                    } else {
                        error_log(json_encode($value));
                        $obj->{$value->By} = $value->Direction;
                    }
                }
            } else {
                $type = gettype($src);
                $obj->$type = $src;
            }
        }

        return $obj;
    }

}
