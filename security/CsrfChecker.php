<?php

namespace levitarmouse\common_tools\security;

/**
 * Description of CsrfChecker
 *
 * @author gabriel
 */
class CsrfChecker {

    /**
     * Verifica que exista en la sessión La variable indicada con el Valor pasado
     * al mismo tiempo que en las cabeceras del request
     * 
     * @param type $tokenToCheck
     * @param type $value
     */
//    public function verifyInHeaders($tokenToCheck) {
//
//            $headers = getallheaders();
//
//            $headerToken = (isset($headers[$tokenToCheck])) ? $headers[$tokenToCheck] : '';
//
//            $sessionToken = (isset($_SESSION[$tokenToCheck])) ? $_SESSION[$tokenToCheck] : '';
//            
//            return ($headerToken == $sessionToken);
//        }
//        
//    public function verifyInDB($tokenToCheck) {
//        
//
//        
//    }
    public function validateToken($token) {
        $valid = $this->validateTokenAgainstSession($token);
        $valid = ($valid) ? $valid : $this->validateTokenAgainstDB($token);
        return $valid;
    }

    public function validateTokenAgainstSession($token) {
        $sessionToken = (isset($_SESSION[CSRF_CODE_NAME])) ? $_SESSION[CSRF_CODE_NAME] : null;
        $valid = ($token == $sessionToken);
        return $valid;
    }

    public function validateTokenAgainstDB($token) {
        $dto = new \sm\mgmt\SessionDTO($token);
        $session = new \sm\mgmt\Session($dto);
        $valid = $session->exists();
        
        $valid = ($valid && ($session->status == 'ACTIVE' || $session->status = 'IDLE'));
        return $valid;
    }
}
