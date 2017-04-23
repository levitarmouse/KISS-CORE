<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sm\classes;

/**
 * Description of ValidateRequestInput
 *
 * @author gabriel
 */
class DataValidationInput {
    
    CONST TypeNull    = 'NULL';
    CONST TypeInteger = 'Integer';
    CONST TypeFloat   = 'Double';
    CONST TypeDouble  = 'Double';
    CONST TypeString  = 'String';
    CONST TypeArray   = 'Array';
    CONST TypeObject  = 'Object';
    CONST Undefined   = 'Undefined';
    
    use LmIterator;

//    protected $aCollection;
    
//    public function add($mixed) {
//        if ($mixed->name) {
//            parent::add($mixed);            
//        }
//    }

    public function __construct() {
        self::$aCollection = array();
    }

    
}
