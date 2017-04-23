<?php

namespace levitarmouse\core;

use Exception;
use stdClass;

class Object
{
    protected $aData;

    public function __construct()
    {
        $this->aData = array();
    }

    public function __get($name)
    {
        $return = ( isset($this->aData[$name]) ) ? $this->aData[$name] : null;
        return $return;
    }

    public function __set($name, $value)
    {
        $this->aData[$name] = $value;
    }

    public function __isset($name)
    {
        $exist = ( array_key_exists($name, $this->aData) ) ? true : false;

        $isNull = false;
        if ($exist) {
            $value  = $this->$name;
            $isNull = ($value === null);
        }
        
        $bIsSet = ($exist && !$isNull);

        return $bIsSet;
    }

    public function __call($name, $arguments)
    {
        throw new Exception('ERROR_METHOD_DOES_NOT_EXIST ['.$name.']');
    }

    public static function __callStatic($name, $arguments)
    {
        throw new Exception('ERROR_STATIC_METHOD_DOES_NOT_EXIST ['.$name.']');
    }

    /**
     * Unset
     *
     * @param type $name Name
     *
     * @return none
     */
    public function __unset($name)
    {
        //TODO
    }

    /**
     * getAttribs Devuelve todos los atributos ingresados al objeto
     * 
     */
    public function getAttribs($bAsObject = false, $bAsXml = false)
    {
        $mReturn = $this->aData;
        if ($bAsObject) {
            $mReturn = $this->_arrayToObject($mReturn);
        }
        else if ($bAsXml) {
            $mReturn = $this->_arrayToXML($mReturn);
        }
        return $mReturn;
    }

    /**
     * ArrayToObject
     *
     * @param type $aArray Array
     *
     * @return \stdClass
     */
    protected function _arrayToObject($aArray = null)
    {
        $obj = new stdClass();
        ksort($aArray, SORT_STRING);
        if (is_array($aArray) && count($aArray) > 0) {
            foreach ($aArray as $sAttrib => $sValue) {
                $obj->$sAttrib = $sValue;
            }
        }
        return $obj;
    }

    /**
     * ArrayToXml
     *
     * @param type $aArray String
     *
     * @return type
     */
    protected function _arrayToXML($aArray = null)
    {
        ksort($aArray, SORT_STRING);
        $xml = '';
        if (is_array($aArray)) {
            foreach ($aArray as $sAttrib => $sValue) {
                $xml .= "<{$sAttrib}>{$sValue}</{$sAttrib}>\n";
            }
        }
        return $xml;
    }
}