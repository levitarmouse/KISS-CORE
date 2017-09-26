<?php

namespace levitarmouse\common_tools\logs;

include_once './Log.php';

/**
 * Description of Logger
 *
 * @author gabriel
 */
class Logger {

    protected static $instance = null;
    protected static $ready = false;

    protected static function init() {
        
        if (defined('LOGS_PATH')) {
            $path = LOGS_PATH;
        } else {
            $path = '/tmp/levitarmouseLogs.log';
        }
        
        self::$instance = new Log($path);
        self::$ready = true;
    }

    public static function log($log) {
        if (self::$ready) {
            self::$instance->append($log);
        } else {
            self::init();
            self::log($log);
        }
    }
}