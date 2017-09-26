<?php

namespace levitarmouse\common_tools\validations;

use levitarmouse\common_tools\validations\DataValidationInput as DVI;
use levitarmouse\common_tools\validations\DataValidationOutput as DVO;

/**
 * Description of RequestValidation
 *
 * @author gabriel
 */
class DataValidation {

    /**
     * validate
     *
     * @param DVI $required
     * @param \levitarmouse\core\Object $request
     * @return DVO
     */
    public static function validate(DVI $required, $request) {

        $output = new DVO();

        while ($item = $required->getNext()) {

            if (isset($item->name)) {

                $expName    = $item->name;
                $expType    = strtoupper($item->type);
                $expMinSize = ($item->minSize != DVI::Undefined) ? $item->minSize : false;
                $expMaxSize = ($item->maxSize != DVI::Undefined) ? $item->maxSize : false;

                $isset = isset($request->$expName);

                $possError = new \stdClass();
                $possError->attribName = $expName;
                
                if (!$isset) {
                    $possError->errorCode  = DVO::NotSetted;
                    $output->add($possError);
                } else {
                    $currValue = $request->$expName;

                    $numberAsString = false;
                    if ( (is_string($currValue) && is_numeric($currValue) ) ) {
                        $numberAsString = true;
                        $currType  = strtoupper(gettype(1));
                    }

                    if ( is_numeric($currValue) ) {
                        $currType  = strtoupper(gettype(1));
                    }
                    else {
                        $currType  = strtoupper(gettype($currValue));
                    }

                    $currLenght = ($currType == 'STRING' || $numberAsString) ? strlen($currValue) : 0;
                    
                    $currType = ($numberAsString) ? 'STRING' : $currType;

                    if ($currType != $expType) {
                        if (!$numberAsString) {
                            if (empty($currValue)) {
                                $possError->errorCode = DVO::EmptyValue;                                
                            } else {
                                $possError->errorCode = DVO::WrongType;                                
                            }
                            $output->add($possError);
                        }
                    } else {
                        switch ($currType) {
                            case 'INTEGER':
                                if ($expMinSize && $currValue < $expMinSize) {
                                    $possError->errorCode = DVO::PoorSize;
                                    $output->add($possError);
                                }
                                if ($expMaxSize && $currValue > $expMaxSize) {
                                    $possError->errorCode = DVO::ExceededSize;
                                    $output->add($possError);
                                }
                                break;
                            case 'STRING':
                                if ($expMinSize && $currLenght < $expMinSize) {
                                    $possError->errorCode = DVO::PoorSize;
                                    $output->add($possError);
                                }
                                if ($expMaxSize && $currLenght > $expMaxSize) {
                                    $possError->errorCode = DVO::ExceededSize;
                                    $output->add($possError);
                                }
                                break;
                        }
                    }
                }
            }
        }

        return $output;
    }
}
