<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace levitarmouse\core;

/**
 *
 * @author gprieto
 */
interface LoggerInterface
{
    public function logDebug($message);

    public function logWarning($message);

    public function logNotice($message);

    public function logInfo($message);

}
