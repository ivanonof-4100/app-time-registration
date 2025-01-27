<?php
namespace Common\Classes\Helper;

/**
 * Filename     : mime_type.class.php
 * Language     : PHP v7.4
 * Date created : 23/01-2025, Ivan
 * Last modified: 24/01-2025, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2025 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This helper-class looks-up the right MIME-type for the right purpose.
 *
 * MIME-types:
 * https://developer.mozilla.org/en-US/docs/Web/HTTP/MIME_types/Common_types
 */
class MimeType {
    const DEFAULT_MIME_FILE = '/etc/nginx/mime.types';

    // Supported objects
    const OBJ_TYPE_DATA ='data';
    const OBJ_TYPE_PAGE ='page';
    const OBJ_TYPE_IMAGE ='image';
    const OBJ_TYPE_VIDEO ='video';
    const OBJ_TYPE_FILE ='file';

    // Supported variants
    const DATA_VARIANT_JSON ='json';
    const DATA_VARIANT_JSONLD ='jsonld';
    const PAGE_VARIANT_HTML5 ='html5';
    const PAGE_VARIANT_XHTML ='xhtml';
    const IMAGE_VARIANT_SVG ='svg';
    const IMAGE_VARIANT_JPG ='jpg';
    const IMAGE_VARIANT_JPEG ='jpeg';
    const IMAGE_VARIANT_PNG ='png';
    const IMAGE_VARIANT_APNG ='apng';
    const IMAGE_VARIANT_WEBP ='webp';
    const IMAGE_VARIANT_AVIF ='avif';
    const IMAGE_VARIANT_TIFF ='tiff';
    const VIDEO_VARIANT_MPEG ='mpeg';
    const VIDEO_VARIANT_MP4 ='mp4';
    const VIDEO_VARIANT_OGV ='ogv';
    const VIDEO_VARIANT_WEBM ='webm';
    const VIDEO_VARIANT_AVI ='avi';
    const FILE_VARIANT_JS ='js';
    const FILE_VARIANT_TXT ='txt';
    const FILE_VARIANT_RTF ='rtf';
    const FILE_VARIANT_PDF ='pdf';
    const FILE_VARIANT_CSV ='csv';
    const FILE_VARIANT_CSS ='css';
    const FILE_VARIANT_PHP ='php';

    public function __construct() {
    }

    public function __destruct() {
    }

    /**
     * @return MimeType
     */
    public static function getInstance() : MimeType {
        return new MimeType();
    }

    /**
     * Gets the correct MIME-type for the specifyed object and variant.
     * @param string $p_objectType
     * @param string $p_variantName
     * @return string
     */
    public static function getMimeType_forObject(string $p_objectType =self::OBJ_TYPE_PAGE, string $p_variantName =self::PAGE_VARIANT_HTML5) : string {
        switch ($p_objectType) {
            case self::OBJ_TYPE_DATA: {
                // Data
                if ($p_variantName == self::DATA_VARIANT_JSON) {
                    return 'application/json';
                } elseif ($p_variantName == self::DATA_VARIANT_JSONLD) {
                    return 'application/ld+json';
                }
                break; 
            }
            case self::OBJ_TYPE_PAGE: {
                // Page
                if ($p_variantName == self::PAGE_VARIANT_HTML5) {
                    return 'text/html';
                } elseif ($p_variantName == self::PAGE_VARIANT_XHTML) {
                    return 'application/xhtml+xml';
                }
                break;
            } case self::OBJ_TYPE_IMAGE: {
                // Image
                if ($p_variantName == self::IMAGE_VARIANT_SVG) {
                    return 'image/svg+xml';
                } elseif (($p_variantName == self::IMAGE_VARIANT_JPG) || ($p_variantName == self::IMAGE_VARIANT_JPEG)) {
                    return 'image/jpeg';
                } elseif ($p_variantName == self::IMAGE_VARIANT_PNG) {
                    return 'image/png';
                } elseif ($p_variantName == self::IMAGE_VARIANT_APNG) {
                    return 'image/apng';
                } elseif ($p_variantName == self::IMAGE_VARIANT_WEBP) {
                    return 'image/webp';
                } elseif ($p_variantName == self::IMAGE_VARIANT_AVIF) {
                    return 'image/avif';
                } elseif ($p_variantName == self::IMAGE_VARIANT_TIFF) {
                    return 'image/tiff';
                }
                break;
            } case self::OBJ_TYPE_VIDEO: {
                if ($p_variantName == self::VIDEO_VARIANT_MPEG) {
                    return 'video/mpeg';
                } elseif ($p_variantName == self::VIDEO_VARIANT_MP4) {
                    return 'video/mp4';
                } elseif ($p_variantName == self::VIDEO_VARIANT_OGV) {
                    return 'video/ogg';
                } elseif ($p_variantName == self::VIDEO_VARIANT_WEBM) {
                    return 'video/webm';
                } elseif ($p_variantName == self::VIDEO_VARIANT_AVI) {
                    return 'video/x-msvideo';
                }
                break;
            } case self::OBJ_TYPE_FILE: {
                if ($p_variantName == self::FILE_VARIANT_JS) {
                    return 'text/javascript';
                } elseif ($p_variantName == self::FILE_VARIANT_TXT) {
                    return 'text/plain';
                } elseif ($p_variantName == self::FILE_VARIANT_PDF) {
                    return 'application/pdf';
                } elseif ($p_variantName == self::FILE_VARIANT_RTF) {
                    return 'application/rtf';
                } elseif ($p_variantName == self::FILE_VARIANT_CSV) {
                    return 'text/csv';
                } elseif ($p_variantName == self::FILE_VARIANT_CSS) {
                    return 'text/css';
                } elseif ($p_variantName == self::FILE_VARIANT_PHP) {
                    return 'application/x-httpd-php';
                }
                break;
            }
        }
    }

    /**
     * Returns an associative-array containing all MIME-types based on the Apache mime.types file.
     * @return array
    */
    public static function getMIMETypes($p_fileMIMETypes = self::DEFAULT_MIME_FILE) {
        $regex = "/([\w\+\-\.\/]+)\t+([\w\s]+)/i";
        $lines = file($p_fileMIMETypes, FILE_IGNORE_NEW_LINES);
        foreach($lines as $line) {
            if (substr($line, 0, 1) == '#') continue; // skip comments
            if (!preg_match($regex, $line, $matches)) continue; // skip mime types w/o any extensions
                $mime = $matches[1];
                $extensions = explode(" ", $matches[2]);
                foreach($extensions as $ext) {
                    $mimeArray[trim($ext)] = $mime;
                } // Each match
        } // Each MIME-line
        return $mimeArray;
    }

 /**
  * @return string|boolean Returns a string of the MIME-type, if not found boolean FALSE.
  */
 public static function getMIMEType_ofFile(string $p_fileName) : string {
    $arrFilenameParts = pathinfo($p_fileName);
    $arrMIMETypes = self::getMIMETypes(self::DEFAULT_MIME_FILE);
    if (array_key_exists($arrFilenameParts['extension'], $arrMIMETypes)) {
      return $arrMIMETypes[$arrFilenameParts['extension']];
    } else {
      return FALSE;
    }
 }
}