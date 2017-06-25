<?php

namespace levitarmouse\core;

class WarningsResponse {

//    public static $has;
//    public static $warningMessage;
//    public static $warningsList;
    
    protected static $instance;
    
    private function __construct() {
//        self::$warningMessage = '';
//        self::$warningsList = array();
//        self::$has = false;
    }
    
    /**
     * 
     * @return Warnings
     */
    public static function getInstance() {
        if (self::$instance ) {
            return self::$instance;
        } else {
            self::$instance = new Warnings();
            return self::$instance;
        }        
    }
    
//    public function has() {
//        $has = self::$instance->has;
//        return $has;
//    }
//    
//    public function appendWarning($mixed) {
//        self::$has = true;
//        self::$instance->appendWarning($mixed);
//    }
    
}
