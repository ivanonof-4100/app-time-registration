<?php
/**
 * Smarty plugin
 *   @file: modifier.base64_decode.php
 *    Type: modifier
 *    Name: base64_decode
 * @author: Ivan Mark Andersen <ivanonof@gmail.com>
 * Purpose: Todo base64-decoding.
 */
function smarty_modifier_base64_decode(string $p_str) : string {
    return base64_decode($p_str);
}