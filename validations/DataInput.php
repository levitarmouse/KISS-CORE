<?php

namespace levitarmouse\common_tools\validations;

class DataInput {
    
    public $name;
    public $type;
    public $minSize;
    public $maxSize;
    
    /**
     * 
     * @param type $name
     * @param type $type
     * @param type $minSize
     * @param type $maxSize
     */
    public function __construct($name = '', $type = '', $minSize = '', $maxSize = '') {

        $this->name    = $name;
        $this->type    = $type;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
    }
}
