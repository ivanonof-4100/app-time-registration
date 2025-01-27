<?php
namespace App;
use Exception;

/**
 * Use Composer to autoload PHP-classes from files using the PSR-4 autoloading-standard.
 * Read more about PHP Standard Recommendation (PSR):
 * https://www.php-fig.org/psr/
 * @author: Ivan Mark Andersen <ivanonof@gmail.com>
 */
try {
  require_once __DIR__ .DIRECTORY_SEPARATOR .'vendor'. DIRECTORY_SEPARATOR .'autoload.php';
} catch (Exception $e) {
  // Re-throw exception
  throw new Exception('Failed loading the autoload-file from the vendor-catelog ...'. $e->getMessage());
}