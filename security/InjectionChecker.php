<?php

namespace levitarmouse\common_tools\security;

include_once 'InjectionTestResult.php';

/**
 * CheckInjection
 * @author     Gabriel Prieto <gabriel@liqipigi.com>
 * @copyright  2014 levitarmouse
 * @license
 * @link       http://www.levitarmouse.com
 */

class InjectionChecker
{
    protected $allowedAsciiTable;
    protected $allowedExtendedAsciiTable;

    protected $pattern;

    protected $replacements;
    protected $angularReplacements;
    protected $sqlReplacements;

    protected $allowSpaces;

    private $_firstPattern;

    protected $trim;

    protected $result;

    protected $sanitizingChar;

    protected $omissions;

    protected $checkJs;

    protected $emitMessageError;

    const SeparatorOfOmmitions = "|-|&&|-|";

    /**
     *
     * @param type $types List of Regular Expressions, serpared by "-"
     * NUMBERS-ALPHA-SQL-ANGULAR
     * @param type $oLogger
     */
    public function __construct(InjectionCheckerRequest $dto)
    {
        ini_set('memory_limit','512M');

        $types        = $dto->types;
        $oLogger      = $dto->oLogger;
        $omissions    = $dto->omissions;
        $specialChars = $dto->specialChars;

        $this->emitMessageError = $dto->emitMessageError;

        $this->allowedAsciiTable = array();
        $this->allowedExtendedAsciiTable = array();

        $this->sanitizingChar = $dto->sanitChar;

        $this->omissions = array();
        if ($omissions) {
            foreach ($omissions as $key => $value) {
                $this->omissions[] = strtoupper($value);
            }
        }

        if (!$types) {
            $this->initNumeric();

        } else {

            $expGroups = explode('-', $types);

            foreach ($expGroups as $key => $group) {

                switch (strtoupper($group)) {
                    case 'NUMBERS':
                        $this->initNumeric();
                        break;
                    case 'ALPHA':
                        $this->initAlpha();
                        $this->initAlphaSpecials();
                        break;
                    case 'SQL':
                        $this->initSql();
                        break;
                    case 'ANGULAR':
                        $this->initAngular();
                        break;
    //                case 'JS':
    //                    $this->initJavascript();
    //                    break;
                }
            }
        }

        if ($specialChars) {
            foreach ($specialChars as $key => $value) {
                $this->setSpecialSymbolsGroup($value);
            }
        }

        $this->trim = $dto->autoTrim;

        $this->checkJs = $dto->checkJS;

        return;
    }

    public static function getSeparatorOfOmissions() {
        return self::SeparatorOfOmmitions;
    }

    protected function init() {

        $this->result = new InjectionTestResult();

        $this->result->setEmitMessageError($this->emitMessageError);

        return;
    }

//    /**
//     *
//     * @param type $types List of Regular Expressions, serpared by "-"
//     * NUMBERS-ALPHA-SQL-ANGULAR
//     */
//    protected function initGroup($charsGroup = null) {
//
//        $this->result = new InjectionTestResult();
//        return;
//    }

    public function getValidTable() {

        $validTable = array();

        if ($this->allowedAsciiTable) {
            foreach ($this->allowedAsciiTable as $key => $value) {

                $validTable[] = $value;
            }
        }

        if ($this->allowedExtendedAsciiTable) {
            foreach ($this->allowedExtendedAsciiTable as $key => $value) {

                $validTable[] = $value;
            }
        }

        return $validTable;
    }

    /**
     *
     * @param type $array String or Matrix of Strings to you want set as valid
     */
    public function setSpecialSymbolsGroup($str = '', $array = array()) {

        if ($str) {
            $this->allowedExtendedAsciiTable[] = trim($str);
        } else {
            if (is_array($array) && !empty($array)) {
                foreach ($array as $key => $symbol) {
                    $this->allowedExtendedAsciiTable[] = $symbol;
                }
            }
        }
    }

    // Range of numbers in Ascci code
    protected function initNumeric()
    {
        // ASCII dec
        $first = 48;
        $last  = 57;

        for ($i = $first; $i <= $last; $i++) {
            $this->allowedAsciiTable[$i] = chr($i);
        }
        return;
    }

    //
    protected function initAlpha()
    {
        // ASCII dec
        $first = 65;
        $last  = 90;

        $firstSD = 97;
        $lastSD  = 122;

        // append the Space
        $this->allowedAsciiTable[32] = chr(32);

        for ($i = $first; $i <= $last; $i++) {
            $this->allowedAsciiTable[$i] = chr($i);
        }

        if ($firstSD) {
            for ($i = $firstSD; $i <= $lastSD; $i++) {
                $this->allowedAsciiTable[$i] = chr($i);
            }
        }

        return;
    }

    protected function initAlphaSpecials()
    {
        // ASCII dec
        $extended = array();
        $extended[] = 'á';
        $extended[] = 'é';
        $extended[] = 'í';
        $extended[] = 'ó';
        $extended[] = 'ú';

        $extended[] = 'Á';
        $extended[] = 'É';
        $extended[] = 'Í';
        $extended[] = 'Ó';
        $extended[] = 'Ú';

        $extended[] = 'ñ';
        $extended[] = 'Ñ';

        $extended[] = "\n";

        $extended[] = "(";
        $extended[] = ")";
        $extended[] = "_";
        $extended[] = ",";
        $extended[] = "-";
        $extended[] = "@";
        $extended[] = ".";

        if ($extended) {
            foreach ($extended as $key => $value) {
                $this->allowedExtendedAsciiTable[$key] = $value;
            }
        }
    }

    protected function initAngular()
    {
        $this->pattern = implode('', $r);
        $this->pattern = '@'.$this->pattern.'@i';
    }

    protected function initSql()
    {
//        $r[] = '(';

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

//        $r[] = ')';

        $this->pattern = implode('', $r);
        $this->pattern = '@'.$this->pattern.'@i';

        $replacementDictionary = array();
        $replacementDictionary[] = array("'","");
        $replacementDictionary[] = array("-"," ");
        $replacementDictionary[] = array(";"," ");
        $replacementDictionary[] = array("%"," percent ");
        $replacementDictionary[] = array("select", " mostrar ");
        $replacementDictionary[] = array("union", " juntar ");
        $replacementDictionary[] = array("and", " y ");
        $replacementDictionary[] = array("or", " Ã³ ");
        $replacementDictionary[] = array("=", " igual ");
        $replacementDictionary[] = array("insert", " insertar ");
        $replacementDictionary[] = array("delete", " borrar ");
        $replacementDictionary[] = array("drop", " limpiar ");

        $this->sqlReplacements = $replacementDictionary;
    }

    public function checkString($string, $sanitize = false) {
        return $this->checkStringASCII($string, $sanitize );
    }

    /**
     * CheckString Function
     *
     * @param type $string
     */
    protected function checkStringASCII($string, $sanitize = false) {

        if ($this->trim) {
            $string = trim($string);
        }

        $result = new \stdClass();
        $result->valid = true;
        $result->string = '';
        $result->wrongPositions = array();

        if (is_string($string) && strlen($string) > 0) {

            $Prospect = str_split(utf8_decode($string));

            $size = count($Prospect);
            $size = ($size > 100) ? 5 : $size;

            $valid = true;

            // validate string ones character at time
            $b_Second_Step = true;
            foreach ($Prospect as $key => $value) {

                $ascii = ord($value);

                // validate directly the dec ascii code
                $b_First_Step = isset($this->allowedAsciiTable[$ascii]);

                // if ascii is not valid, check if was added
                // to the extended table mannualy
                if (!$b_First_Step) {
                    $exist = in_array(utf8_decode($value), $this->allowedExtendedAsciiTable);
                    $exist = in_array(utf8_encode($value), $this->allowedExtendedAsciiTable);
                    if (!$exist) {
                        $result->wrongPositions[] = $key;
                        $b_Second_Step = false;
                    }
                }
            }

            // if the chars validation was invalid, checks the entire string
            $b_Thrird_Step = true;
            if (!$b_Second_Step) {
                $newProspect = trim (implode('', $Prospect));
                $exist = in_array(utf8_encode($newProspect), $this->allowedExtendedAsciiTable);
                if (!$exist) {
                    $b_Thrird_Step = false;
//                    $result->wrongPositions = 'FULL';
                }
            }

            if (!$b_Thrird_Step) {
                $result->valid = false;
                $result->wrong[] = $key+1;

                error_log(time()."This STRING have invlid chars:  \"".($string)."\" at Column $key \n", 3, '/tmp/validation.log');
            }

            if (!$result->valid) {
                $result->string = $string;
            }
        }

        return $result;
    }

    public function sanitize($string, $position)
    {
        $sanitizingCharacter = ($this->sanitizingChar !== null) ? $this->sanitizingChar : ' ';

        foreach ($position as $key => $value) {
            $string[$value] = $sanitizingCharacter;
        }
        return $string;
    }

    /**
     *
     * @param type $mixed
     * @param type $sanitize
     * @return InjectionTestResult
     */
    public function check($mixed, $sanitize = false) {

        $this->init();

        if ($mixed !== null) {
            $original = (is_object($mixed)) ? clone $mixed : $mixed;

            $this->result->setOriginal($original);

            $result = $this->checkObjectOrArray($mixed, $sanitize);
        } else {

            $result = new \stdClass();
            $result->message = "Empty Object";
            $result->data    = "Empty";
        }

        $status = ($result->getCountInvalid() == 0) ? 'VALID' : 'INVALID';
        $result->setStatus($status);

        return $result;
    }

    protected function checkHtmlJavascript($string) {

        $string = str_replace(' ', '', $string);

        $string = strtolower($string);

        $wanted = array();
        $wanted[] = '-_-_-_-'; // para testear la validación!!!
        $wanted[] = '<%';
        $wanted[] = '%>';
        $wanted[] = '{{';
        $wanted[] = '}}';
        $wanted[] = '()';
        $wanted[] = '{%';
        $wanted[] = '%}';
        $wanted[] = '<script>';
        $wanted[] = '</script>';
        $wanted[] = 'alert(';
        $wanted[] = 'constructor.constructor';
        $wanted[] = 'document.';
        $wanted[] = 'window.';
        $wanted[] = '.location';
        $wanted[] = '.createElement';

        $has = false;

        foreach ($wanted as $key => $value) {

            $dangerousTerm = strtolower($value);

            if (!$has) {
                $position = strpos($string, $dangerousTerm);
            }
            if ($position !== false) {
                $has = true;
            }
        }
        return $has;
    }

    protected function checkObjectOrArray($mixed, $sanitize = false, $objectLevel = '') {

        $bJSON = false;
        $toProcess = $mixed;

        $checkedArray = array();
        $validArray = array();
        $invalidArray = array();

        if (is_string($mixed)) {
            if ($jsonObject = json_decode($mixed)) {
                $bJSON = true;
                $toProcess = $jsonObject;
            }
        }

        $newJson = $toProcess;
        $size = count($toProcess);

        
            if (is_array($toProcess) || is_object($toProcess)) {
                
            foreach ($toProcess as $key => $value) {

                $os = self::SeparatorOfOmmitions;

                if (empty($objectLevel)) {
                    $ommitionProspect = $key;
                } else {
                    $ommitionProspect = $objectLevel.$os.$key;
                }

                $ommit = ( in_array(strtoupper($ommitionProspect), $this->omissions));

                if (!$ommit) {
                    $ommit = ( in_array(strtoupper($key), $this->omissions));
                }

                if ( $ommit
                    || (is_numeric($value)
                    ||     is_bool($value) ) ) {

                    /**
                     * Si el campo debe ser omitido de la validación CharByChar ($ommit = true)
                     * y es un string, se lo valida por expresiones armadas
                     */
                    if (is_string($value) && $this->checkJs) {

                        $hasJS = $this->checkHtmlJavascript($value);
                        if ($hasJS) {
                            $invalidArray[$key] = $value;
                        } else {
                            $validArray[$key] = $value;
                        }
                    } else {
                        $validArray[$key] = $value;
                    }
                }
                else {
                    $bValid = false;
                    if (is_string($value)) {

                        $jsonInside = json_decode($value);
                        if (is_object($jsonInside)) {

                            $currentValue = clone $jsonInside;
                            unset($jsonInside);

                            $bValid = true; // si el objeto es un JSON, es valido. Se valida su contenido
                            $recValue = $this->checkObjectOrArray($value, $sanitize);
                            $sanitized = $recValue->getSanitized();

                            if ($sanitize) {
                                if (is_array($mixed)) {
                                    $mixed[$key] = $sanitized;
                                } else {
                                    $mixed->$key = $sanitized;
                                }
                            }
                        } else {
                            $checked = $this->checkStringASCII($value);

                            $bValid = $checked->valid;

                            if ($sanitize) {
                                $toSanitize = $checked->wrongPositions;
                                if (!$bValid) {
                                    $sanitized = $this->sanitize($value, $toSanitize);
                                    if ($bJSON) {
                                        $newJson->$key = $sanitized;
                                    } else {
                                        if (is_object($mixed)) {
                                            $mixed->$key = $sanitized;
                                        }
                                        if (is_array($mixed)) {
                                            $mixed[$key] = $sanitized;
                                        }
                                    }
                                }
                            }
                        }

                        if ($bValid && $this->checkJs) {
                            $hasJS = $this->checkHtmlJavascript($value);
                            $bValid = !$hasJS;
                        }


                        if ($bValid) {
                            $validArray[$key] = $value;
                        } else {
                            $invalidArray[$key] = $value;
                        }
                    }
                    else {
                        if (empty($value)) {

                            $validArray[$key] = $value;

                        } else {

                            $hierValidToAppend = array();
                            $hierInvalidToAppend = array();

                            $prefix = $key."->";

                            $recursiveCallValue = array();
                            if (is_object($value)) {
                                $recursiveCallValue = clone $value;
                            } else {
                                $recursiveCallValue = $value;
                            }

                            $recvalue = $this->checkObjectOrArray($recursiveCallValue, $sanitize, $key);

                            $recBValid = $recvalue->getStatus();

                            if ($sanitize) {
                                if (is_object($mixed)) {
                                    $mixed->$key = $recvalue->getSanitized();
                                }
                                if (is_array($mixed)) {
                                    $mixed[$key] = $recvalue->getSanitized();
                                }
                                if (is_string($mixed)) {
                                    $toProcess->$key = $recvalue->getSanitized();
                                }
                            }

                            if ($recBValid == 'VALID') {
                                $validToAppend = $recvalue->getValid();

                                foreach ($validToAppend as $recKey => $recValue) {
                                    $hierValidToAppend[$prefix.$recKey] = $recValue;
                                }

                                $validArray = array_merge($validArray, $hierValidToAppend);
                            } else {
                                $invalidToAppend = $recvalue->getInvalid();

                                foreach ($invalidToAppend as $recKey => $recValue) {
                                    $hierInvalidToAppend[$prefix.$recKey] = $recValue;
                                }

                                $invalidArray = array_merge($invalidArray, $hierInvalidToAppend);
                            }

                            $recvalue = null;
                        }
                    }
                }
            }
        }


        $result = &$this->result;

        $result->setValidList(null, null, $validArray);

        $result->setInvalidList(null, null, $invalidArray);

        if ($bJSON) {
            $sanitized = ($sanitize) ? json_encode($newJson) : null;
        } else {
            $sanitized = ($sanitize) ? $mixed : array();
        }
        $result->setSanitized($sanitized);

        return $result;

    }

    protected function logChecking ($str) {
        error_log(date('Y-n-j H:i:s '). $str.PHP_EOL, 3, '/tmp/sqlinj.log');
    }
}