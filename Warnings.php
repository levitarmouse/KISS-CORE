<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace levitarmouse\core;

/**
 * Description of WarningsResponse
 *
 * @author gabriel
 */
class Warnings {

    public $has;
    public $size;
    public $warningsList;
    
    protected $instance;
    
    public function __construct() {
        $this->warningsList = array();
        $this->has = false;
    }
    
    public function appendWarning($key, $mixed) {
        $this->has = true;
        
        if (isset($key)) {
            $this->warningsList[$key] = $mixed;            
        } else {
            $this->warningsList[] = $mixed;            
        }
        
        $this->size = count($this->warningsList);
    }
    
}
