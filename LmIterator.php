<?php

namespace levitarmouse\core;

trait LmIterator  {
    
    protected static $aCollection = array();
    protected static $collectionSize = 0;
    protected static $ready = false;
    
    protected static $collectionIndex = 0;
    protected static $collectionEnd = false;
    
    public function add($mixed) {

        array_push(self::$aCollection, $mixed);
        
        self::$collectionSize = count(self::$aCollection);
    }
    
    // TODO
    public function push($mixed) {
        return null;
    }
    
    // TODO
    public function pop($mixed) {
        return null;
    }
    
    // TODO
    public function sort($mixed) {
        return null;
    }
    
    public function getCollectionSize()
    {
        return self::$collectionSize;
    }
    
    public function getNext()
    {
        $item = null;
        if (self::$collectionSize > 0) {

            if (self::$collectionIndex < self::$collectionSize) {

                    $index = self::$collectionIndex;
                    self::$collectionIndex ++;

                $return = self::$aCollection[$index];

                $item = $return;
            }
        } else {
            if (!self::$collectionEnd) {
                self::$collectionEnd = true;
                $item = $this;
            } else {
                $item = null;
            }
        }
        return $item;
    }
}