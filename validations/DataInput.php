<?php

namespace levitarmouse\core\validations;

class DataInput {

    public $name;
    public $type;
    public $minSize;
    public $maxSize;

    /**
     * @param string $name
     * @param string $type
     * @param integer $minSize
     * @param integer $maxSize
     */
    public function __construct($name = '', $type = '', $minSize = '', $maxSize = '') {

        $this->name    = $name;
        $this->type    = $type;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
    }
}
