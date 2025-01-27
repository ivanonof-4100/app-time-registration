<?php
namespace Common\Classes;
use Common\Classes\FileHandler;
use Exception;

class JsonConfigReader extends FileHandler {
    /**
     * @var string
     */
    protected $config_path ='';

    /**
     * @var string
     */
    protected $config_filename ='';

    /**
     * @param string $p_pathAppConfig
     * @param string $p_filename Default 'app.conf.json'
     */
    public function __construct(string $p_pathConfig ='',
                                string $p_filename = 'app.conf.json'
                               ) {
        $this->setAttr_configPath($p_pathConfig);
        $this->setAttr_configFilename($p_filename);
    }

    public static function getInstance(string $p_pathConfig ='',
                                       string $p_configFilename ='app.conf.json') : JsonConfigReader {
        return new JsonConfigReader($p_pathConfig, $p_configFilename);
    }

    public function setAttr_configPath(string $p_configPath ='') {
        $this->config_path = $p_configPath;
    }

    public function getAttr_configPath() : string {
        return $this->config_path;
    }

    public function setAttr_configFilename(string $p_filename) : void {
        $this->config_filename = $p_filename;
    }

    public function getAttr_configFilename() : string {
        return $this->config_filename;
    }

    public function getFullFilename_configFile() : string {
        $configPath = $this->getAttr_configPath();
        $configFilename = $this->getAttr_configFilename();
        return sprintf('%s', $configPath.$configFilename);
    }

    public function jsonDecode(string $p_rawJSON) : array {
        return json_decode($p_rawJSON, true);
    }

    public function jsonEncode(array $p_arr) : string {
        return json_encode($p_arr);
    }

    /**
     * @throws Exception
     * @param string $p_rawJsonData
     * @param resource $pbr_arrResult
     * 
     * @return bool
     */
    public function isJsonValid(string $p_rawJsonData, &$pbr_arrResult) : bool {
        // Decode the JSON-data
        $pbr_arrResult = $this->jsonDecode($p_rawJsonData);
        // switch and check possible JSON-errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON-structure.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
    
        if ($error !== '') {
          // throw the Exception or exit // or whatever :)
          throw new Exception($error);
          return FALSE;
          exit(21);
        } else {
          return TRUE;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function load() : array {
      $configFile = $this->getFullFilename_configFile();        
      if (!$this->doesFileExists($configFile)) {
        throw new Exception(sprintf("The JSON configuration-file %s does NOT exists ...", $configFile));
      } else {
        if (!$this->isReadable($configFile)) {
          throw new Exception(sprintf("JSON config-file %s was un-readable ...", $configFile));
        } else {
          try {
            $rawJSON = $this->getFileContent($configFile);
            if ($this->isJsonValid($rawJSON, $pbr_arrConfigData)) {
              return $pbr_arrConfigData;
            } else {
              return array();
            }
          } catch (Exception $e) {
            // Re-throw the exception to catch at a higher level.
            throw new Exception($e->getMessage());
          }
        }
      }
    }
} // End class