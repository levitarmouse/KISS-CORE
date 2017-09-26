<?php

namespace levitarmouse\common_tools\validations;

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
    
     use levitarmouse\core\LmIterator;
    
    protected $wrongData;
    
    function __construct() {
        $this->wrongData = array();
    }
    
    public function isValid() {
        
        $valid = ($this->getCollectionSize() === 0);
        return $valid;
    }
}
