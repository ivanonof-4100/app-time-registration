<?php
/**
 * Smarty plugin
 *    File: modifier.base64_encode.php
 *    Type: modifier
 *    Name: base64_encode
 * @author: Ivan Mark Andersen <ivanonof@gmail.com>
 * Purpose: Todo base64-encoding.
 */
function smarty_modifier_base64_encode(string $p_str) : string {
    return base64_encode($p_str);
}