<?php
/**
 * About namespaces you need to know that a consortium has studied about best-practices in PHP,
 * in order to allow developers to have common coding-standards.
 * 
 * These best-practices are called - PHP Standard Recommendations (PSR)
 * @see https://www.php-fig.org/psr
 *
 * The categories of the coding-standards:
 *
 * PSR-0: Autoloading Standard, which goal is to make the use of namespaces easier, in order to convert a namespace into a file-path.
 * PSR-1: Basic Coding Standard, basically, standards. (Deprecated)
 * PSR-2: Coding Style Guide, where to put braces, how to write a class, etc.
 * PSR-3: Logger Interface, how to write a standard logger.
 * PSR-4: Improved Autoloading, to resolve more namespaces into paths.
 *
 * As PSR-0 now is deprecated we will offcause go for a solution using the PSR-4 standard.
 * They use namespaces to resolve a Fully Qualified Class Name (FQCN) into a file-path.
 * = full namespace + class name into a file-path.
 *
 * Basic example, you have this directory structure:
 * ./src/Pierstoval/Tools/MyTool.php
 * 
 * When using spl_autoload_register() with class methods,
 * it might seem that it can use only public methods, though it can use private/protected methods as well,
 * if registered from inside the class:
 * 
 * @example:
 *
 *  // Example of usage:
 *  $autoLoader = CustomAutoloader::getInstance();
 *  $obj = new Class1();
 *  $obj = new Class2();
 *
 * Read more:
 *  @see: https://www.php.net/manual/en/function.spl-autoload.php
 *  @see: https://www.php.net/manual/en/language.oop5.autoload.php
 *  @see: https://www.php.net/manual/en/language.namespaces.rationale.php
 *  @see: https://www.php.net/manual/en/function.spl-autoload-extensions.php
 *  @see: https://www.php-fig.org/psr/psr-4/examples/
*/

class CustomAutoloader
{
   public function __construct() {
      // Register the auto-loading function that will be called by PHP-core functions.
      spl_autoload_register(array($this, 'autoload'), TRUE);
   }

   public function __destruct() {
      // Unregister all registered auto-load functions.
      $autoloadFunctions = $this->getList_autoloadFunctions();
      foreach($autoloadFunctions as $curFunction) {
        spl_autoload_unregister($curFunction);
      }
   }

   public function getList_autoloadFunctions() : array {
      return spl_autoload_functions();
   }

   /**
    * @return CustomAutoloader
    */
   public static function getInstance() : CustomAutoloader {
     return new CustomAutoloader();
   }

   public static function splitCamelCase(string $p_str) {
     return preg_split('/(?<=\\w)(?=[A-Z])/', $p_str);
   }

   /**
    * @param string $p_className
    * @return bool
    * @todo implement Find sub-string Interface
    */
   private function isInterface(string $p_className) : bool {
      return mb_strpos($p_className, 'Interface', 0, 'utf8');
   }

   /**
    * @param string $p_className Eg.: StdController
    * @return string Eg. std_controller.class.php
    */
   public function getAutoloadFilename($p_className) : string {
      $arrCamelCase = self::splitCamelCase($p_className);
      if (is_array($arrCamelCase)) {
         $autoloadFileName ='';
         reset($arrCamelCase);
         $count =0;
         foreach ($arrCamelCase as $curCamelCase) {
            if ($count == 0) {
              $autoloadFileName = strtolower($curCamelCase);
            } else {
              $autoloadFileName .= sprintf('_%s', strtolower($curCamelCase));
            }

            $count++;
         }

         if ($this->isInterface($p_className)) {
           $fullFilename = sprintf('%s.interface.php', str_replace('\\', PATH_DELIMITER, $autoloadFileName));
         } else {
           $fullFilename = sprintf('%s.class.php', str_replace('\\', PATH_DELIMITER, $autoloadFileName));
         }

         $fullFilename = PATH_CODEBASE_ROOT . $fullFilename;
         if (DEBUG) {
           echo sprintf(__METHOD__ .': fullFilename = %s'.PHP_EOL, $fullFilename); 
         }
         return $fullFilename;
      }
   }

   /**
    * @param string $p_className
    * @return void
    */
   public function autoload(string $p_className) : void {
      // Set the file-name to load.
      $fullFilename = $this->getAutoloadFilename($p_className);
      if (!file_exists($fullFilename)) {
        // Display an error, if the needed file does NOT exists.
        trigger_error('Unable to auto-load requested file, because the file does NOT exist ... ('. $fullFilename .')', E_USER_ERROR);
        exit(1);
      } else {
        try {
// Try load the file.
require_once($fullFilename);
          if (DEBUG) {
            echo sprintf(__METHOD__ .': Auto-loading %s via %s'.PHP_EOL, $p_className, __METHOD__);
          }
        } catch (Exception $e) {
          echo 'Unable to auto-load requested file due to this error: '. $e->getMessage();
          exit(2);
        }
      }
   }
}

/*
class Autoloader {
    public static $loader;

    public static function init()
    {
        if (self::$loader == NULL)
            self::$loader = new self();

        return self::$loader;
    }

    public function __construct()
    {
        spl_autoload_register(array($this,'model'));
        spl_autoload_register(array($this,'helper'));
        spl_autoload_register(array($this,'controller'));
        spl_autoload_register(array($this,'library'));
    }

    public function library($class)
    {
        set_include_path(get_include_path().PATH_SEPARATOR.'/lib/');
        spl_autoload_extensions('.library.php');
        spl_autoload($class);
    }

    public function controller($class)
    {
        $class = preg_replace('/_controller$/ui','',$class);
       
        set_include_path(get_include_path().PATH_SEPARATOR.'/controller/');
        spl_autoload_extensions('.controller.php');
        spl_autoload($class);
    }

    public function model($class)
    {
        $class = preg_replace('/_model$/ui','',$class);
       
        set_include_path(get_include_path().PATH_SEPARATOR.'/model/');
        spl_autoload_extensions('.model.php');
        spl_autoload($class);
    }

    public function helper($class)
    {
        $class = preg_replace('/_helper$/ui','',$class);

        set_include_path(get_include_path().PATH_SEPARATOR.'/helper/');
        spl_autoload_extensions('.helper.php');
        spl_autoload($class);
    }

}

//call
Autoloader::init();
*/
