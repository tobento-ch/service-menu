<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Menu;

use Stringable;

/**
 * Str
 */
class Str
{    
    /**
     * Escapes string with htmlspecialchars.
     * 
     * @param string|Stringable $string
     * @param int $flags
     * @param string $encoding
     * @param bool $double_encode
     * @return string
     */
    public static function esc(
        string|Stringable $string,
        int $flags = ENT_QUOTES,
        string $encoding = 'UTF-8',
        bool $double_encode = true
    ): string {
        
        if ($string instanceof Stringable) {
            $string = $string->__toString();
        }
        
        return htmlspecialchars($string, $flags, $encoding, $double_encode);
    }    
}