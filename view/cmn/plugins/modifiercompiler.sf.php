<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty string_format modifier plugin
 * Type:     modifier<br>
 * Name:     sf<br>
 * Purpose:  format strings via sprintf
 *
 * @link   http://www.smarty.net/manual/en/language.modifier.string.format.php string_format (Smarty online manual)
 * @author Uwe Tews
 *
 * @param array $params parameters
 *
 * @return string with compiled code
 */
function smarty_modifiercompiler_sf($params)
{
    return 'sprintf(' . $params[ 1 ] . ',' . $params[ 0 ] . ')';
}
