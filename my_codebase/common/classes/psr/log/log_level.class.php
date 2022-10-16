<?php
namespace Psr\Log;

/**
 * Describes log levels.
 * PSR-3
 * @see: https://www.php-fig.org/psr/psr-3/
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
}