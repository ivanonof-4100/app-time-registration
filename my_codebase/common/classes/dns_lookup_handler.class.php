<?php
namespace Common\Classes;

/**
 * Filename     : dns_lookup_handler.class.php
 * Language     : PHP v5.x
 * Date created : 22/12-2013, IMA
 * Last modified: 22/12-2013, IMA
 * Developers   : @author IMA Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2013 by Ivan Mark Andersen
 * 
 * Description:
 *  Class for handling DNS-lookups using PHP and platform in-depended functions.
 */
class DNSLookupHandler
{
   public function __construct() {
   }
   
   public function __destruct() {
   }

   public static function getValidDNSTypes() {
      // DNS_A, DNS_CNAME, DNS_HINFO, DNS_MX, DNS_NS, DNS_PTR, DNS_SOA, DNS_TXT, DNS_AAAA, DNS_SRV, DNS_NAPTR, DNS_A6, DNS_ALL, DNS_ANY
      return array('A', 'CNAME', 'HINFO', 'MX', 'NS', 'PTR', 'SOA', 'TXT', 'AAAA', 'SRV', 'NAPTR', 'A6', 'ALL', 'ANY');
   }

   /**
    * @param string $p_domainHost
    * @param string $p_typeOfDNSLookup Default 'A'
    * 
    * @return string|bool IP-address of the host if it was found, if not found 
    */
   public static function lookupIPAddress($p_domainHost, $p_typeOfDNSLookup ='A') {
      if (empty($p_domainHost)) {
        trigger_error('The given HOST-domain was empty ...', E_USER_ERROR);
      } else {
        // Get valid DNS-types.
        $arrValidTypesOfDNSRecords = self::getValidDNSTypes();

        // Check that requested DNS-type is defined or allowed.
        $requestedDNSType = mb_convert_case($p_typeOfDNSLookup, MB_CASE_UPPER, 'UTF-8');
        if (!defined('DNS_' . $requestedDNSType) || !in_array($requestedDNSType, $arrValidTypesOfDNSRecords)) {
          trigger_error('Lookup of invalid DNS-type was requested ('.$requestedDNSType .')', E_USER_ERROR);
        } else {
          // Perform the lookup of the domain-hostname.
          $recordType = constant('DNS_' . $requestedDNSType);
          $arrDNSRecord = dns_get_record($p_domainHost, $recordType);

          if (array_key_exists(0, $arrDNSRecord)) {
            // IP-address of the host was found in a DNS-record via a name-server. 
            return $arrDNSRecord[0]['ip'];
          } else {
            return FALSE;
          }
        }
      }
   }
} // End class