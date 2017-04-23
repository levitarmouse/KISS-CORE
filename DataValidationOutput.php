<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sm\classes;

/**
 * Description of ValidationDto
 *
 * @author gabriel
 */
class DataValidationOutput {
    
    CONST NotSetted    = 'NotSetted';
    CONST EmptyValue   = 'EmptyValue';
    CONST WrongType    = 'WrongType';
    CONST PoorSize     = 'PoorSize';
    CONST ExceededSize = 'ExceededSize';
    
    use LmIterator;
    
    protected $wrongData;
//    protected $valid;
    
    function __construct() {
        $this->wrongData = array();
//        $this->valid = true;
    }
    
//    public function append($attribName, $result) {
//        $this->wrongData[$attribName] = $result;
//        $this->valid = false;
//    }
    
    public function isValid() {
        
        $valid = ($this->getCollectionSize() === 0);
        return $valid;
    }
    
//    public function getWrongData() {
//        return $this->wrongData;
//    }
}
