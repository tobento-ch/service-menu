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

/**
 * Taggable
 */
interface Taggable
{
    /**
     * Set the tag
     *
     * @param Tag
     * @return void
     */    
    public function setTag(Tag $tag): void;
        
    /**
     * Get the tag
     *
     * @return Tag
     */    
    public function tag(): Tag;
}