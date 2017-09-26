<?php
/**
 * Description of Log
 *
 * @author gabriel
 */
class Log {
    
    protected $path;
    protected $token;
    
    public function __construct($path) {
        $this->path = $path;
        $this->token = rand(1000000, 9999999);
    }
    
    public function append($log) {
        
        $msg = '';
        
        $type    = gettype($log);
        $objectName = '';
        if (is_string($log)) {
            $content = $log;
        } else {
            if (is_object($log)) {
                $objectName  = get_class($log);
                $aObjectName = explode('\\', $objectName);
                $objectName = array_pop($aObjectName);
            }
            $content = json_encode($log);
        }
        
        $time = date('ymd H:i:s');

        $objectName = (empty($objectName)) ? '' : $objectName.':';
        
        $msg = '['.$this->token.' '.$time.']'.$type.'->'.$objectName.$content;
        
        error_log($msg.PHP_EOL, 3, $this->path);            
    }
}
