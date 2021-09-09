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
 * Link
 */
class Link extends Item implements Taggable
{
    use HasTag;
    
    /**
     * @var Tag
     */    
    protected Tag $tag;    
        
    /**
     * Create a new Link
     *
     * @param string|Stringable $url
     * @param string|Stringable $text
     * @param null|string|int $id
     */
    public function __construct(
        protected string|Stringable $url,
        string|Stringable $text,
        null|string|int $id = null
    ){        
        parent::__construct($text, $id);
        
        $this->setTag(new Tag('a', Str::esc($text)));
    }

    /**
     * Get the url.
     *
     * @return string
     */    
    public function url(): string
    {
        return $this->url;
    }    
    
    /**
     * Get the evaluated contents of the item.
     *
     * @return string
     */    
    public function render(): string
    {
        if (!empty($this->url)) {
           $this->tag->attr('href', $this->url); 
        }
        
        $this->itemTag = null;
        
        return $this->tag->render();
    }        
}