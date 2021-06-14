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
 * HasTag
 */
trait HasTag
{
    /**
     * @var Tag
     */    
    protected Tag $tag; 

    /**
     * Set the tag
     *
     * @param Tag
     * @return void
     */    
    public function setTag(Tag $tag): void
    {
        $this->tag = $tag;
    }
        
    /**
     * Get the tag
     *
     * @return Tag
     */    
    public function tag(): Tag
    {
        return $this->tag;
    }   
}