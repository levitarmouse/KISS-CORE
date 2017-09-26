<?php

namespace levitarmouse\common_tools\security;

/**
 * CheckSqlInjection
 *
 * PHP version 5.6+
 *
 * @category
 * @package
 * @subpackage Validate
 * @author     Gabriel Prieto <gabriel.prieto@intraway.com>
 * @copyright  2016 Intraway Corp.
 * @license    Intraway Corp. <http://www.intraway.com>
 * @link       http://www.intraway.com
 */

class SqlInjectionChecker
{
    protected $sqlPattern;
    protected $sqlReplacements;

    public function __construct($type = 'SQL', $oLogger = null)
    {
        switch (strtoupper($type)) {
            case 'SQL':
                $this->initSql();
                break;
        }
    }

    public function initSql()
    {
        $r[] = '(';

        // SELECT
        $r[] = '[(]*(\s)?(select)(\s)?[)]*';
        $r[] = '((\s)*([\s , * a-z A-Z 0-9 _ ]*))';
        $r[] = '((\s)?(from)(\s)?)';

        $r[] = '|(=)[\(\s]*?(select)(\s)?[)]*';

        $r[] = "|([\s](')[\s])";
        $r[] = "|(')[\s]?((select)|(create)|(delete)|(update)|(drop))(\s)*";

        // uso de %
        $r[] = "|^(%)$|^[\s]*(%)[^\s]|^[a-zA-Z]*(%)$";
        $r[] = "|^[0-9a-zA-Z]*([%]*)$";

        // cierre de query
        $r[] = "|(')[\s]*(;)";

        // DROP *
        $r[] = '|([\s](drop)[\s]+([a-zA-Z]*))';

        //DELETE
        $r[] = '|(\s)(delete)[\s]*(from)';

        //INSERT
        $r[] = '|(\s)(insert)[\s]*(into)';

        //CREATE TABLE
        $r[] = '|((\s)(create)[\s]+(table))';

        // 1 = 1
//        $r[] = '|(([\s]*[\']*[\s]*([0-9]+)[\s]*[\']*[\s]*)([\!\=]+)([\s]*[\']*[\s]*([0-9]+[\s]?[\']*)))';
//        $r[] = '|(([\s]*[\']*[\s]*([a-zA-Z]+)[\s]*[\']*[\s]*)([\!\=]+)([\s]*[\']*[\s]*([a-zA-Z]+[\s]?[\']*)))';
//        $r[] = '|[\s]*(and)[\s]*((1)[\s]*(=)[\s]*(1))';
        $r[] = '|(([\s]*[\']*[\s]*([0-9a-zA-Z]+)[\s]*[\']*[\s]*)([\!\=]+)([\s]*[\']*[\s]*([0-9a-zA-Z]+[\s]?[\']*)))';

        // AND
        $r[] = '|( [\s]*(and)[\s]*[a-zA-Z0-9_]*+(\s)(is)[\s]*?(not)?[\s]*?(null)? )';

        // OR
        $r[] = '|[\s]*(or)[\s]*([\"\']*[\s]*(=))';

        // IS NULL
        $r[] = '|[\s]*(or)[\s]*((1)[\s]*(=)[\s]*(1))';
        $r[] = '|((\s)(or)[\s]*[a-zA-Z0-9\s_]*(\s)(is)[\s]*?(not)?[\s]*(null)?)';

        $r[] = ')';

        $this->sqlPattern = implode('', $r);
        $this->sqlPattern = '@'.$this->sqlPattern.'@i';

        $replacementDictionary = array();
        $replacementDictionary[] = array("'","");
        $replacementDictionary[] = array("-"," ");
        $replacementDictionary[] = array(";"," ");
        $replacementDictionary[] = array("%"," percent ");
        $replacementDictionary[] = array("select", " mostrar ");
        $replacementDictionary[] = array("union", " juntar ");
        $replacementDictionary[] = array("and", " y ");
        $replacementDictionary[] = array("or", " ó ");
        $replacementDictionary[] = array("=", " igual ");
        $replacementDictionary[] = array("insert", " insertar ");
        $replacementDictionary[] = array("delete", " borrar ");
        $replacementDictionary[] = array("drop", " limpiar ");

        $this->sqlReplacements = $replacementDictionary;
    }

    /**
     * CheckString Function
     *
     * @param type $string
     */
    public function checkString($string) {
        $sqlProspect = $string;
//        $sqlProspect = strtolower($string);
        $count = 0;
        if (is_string($sqlProspect)) {
            $count = preg_match_all($this->sqlPattern, $sqlProspect);
        }
        return $count;
    }

    public function sanitize($string)
    {
        $file1 = $file2 = $file3 = '';
        if ($this->checkString($string)) {
            $stack = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 0 );

            $time = date('Y-n-j H:i:s ');

            $file1 = (isset($stack[1])) ? $stack[1]['file']." : ".$stack[1]['line'].PHP_EOL : '';;
            $file2 = (isset($stack[2])) ? $stack[2]['file']." : ".$stack[2]['line'].PHP_EOL : '';;
            $file3 = (isset($stack[3])) ? $stack[3]['file']." : ".$stack[3]['line'].PHP_EOL : '';;


            $this->logChecking($time.' - Posible SQL Injection Case !!!!! CALLSTACK');
//            $this->logChecking($time.' - CALLSTACK ');
            $this->logChecking($file1);
            if ($file2) {
                $this->logChecking($file2);
            }
            if ($file3) {
                $this->logChecking($file3);
            }

            foreach ($this->sqlReplacements as $key => $value) {
//                $string = str_replace($value[0], $value[1], $string);
                $string = preg_replace("@".$value[0]."@i", $value[1], $string);
            }
        }
        return $string;
    }

    public function checkObjectOrArray($mixed, $currMixed = array()) {
        $newMixedarray = array();
        $newMixedObject = new \stdClass();

        if (is_array($mixed) || is_object($mixed)) {
            foreach ($mixed as $key => $value) {
                if (is_string($value)) {
                    $newMixedarray[$key] = $this->sanitize($value);
                } else {
                    if (is_array($value) || is_object($value)) {
                        $newMixedarray[$key] = $this->checkObjectOrArray($value, $newMixedarray);
                    } else {
                        $newMixedarray[$key] = $value;
                    }
                }
            }
            return $newMixedarray;
        }
        else if (is_string($mixed)) {
            return $this->sanitize($mixed);
        }

        return $mixed;
    }

    public function logChecking ($str) {
        error_log(date('Y-n-j H:i:s '). $str.PHP_EOL, 3, '/tmp/sqlinj.log');
    }
}