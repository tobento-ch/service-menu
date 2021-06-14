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
 * NullTag
 */
class NullTag extends Tag
{        
    /**
     * Create a new NullTag
     */
    public function __construct(){
        parent::__construct('');
    }

    public function render(): string
    {
        $content = $this->prepend.$this->content.$this->append;
        
        if (empty($content)) {
            return '';
        }
        
        return $this->open().$content.$this->close();
    }
    
    /**
     * Renders the opening tag.
     *
     * @return string
     */    
    public function open(): string
    {
        return '';
    }

    /**
     * Renders the closing tag.
     *
     * @return string
     */    
    public function close(): string
    {
        return '';
    }    
}