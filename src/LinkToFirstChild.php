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
 * Link to first child item.
 */
class LinkToFirstChild extends Item
{
    /**
     * Create a new LinkToFirstChild
     *
     * @param MenuInterface $menu
     * @param string|Stringable $text
     * @param null|string|int $id
     */
    public function __construct(
        protected MenuInterface $menu,
        string|Stringable $text,
        null|string|int $id = null
    ){        
        parent::__construct($text, $id);
        
        $this->setTag(new Tag('a', Str::esc($text)));
    }
    
    /**
     * Get the evaluated contents of the item.
     *
     * @return string
     */    
    public function render(): string
    {
        $link = $this->getFirstChildLink();

        if (empty($link)) {
            $this->reset();
            return '';
        }
        
        if (!empty($link->url())) {
            $this->tag->attr('href', $link->url()); 
        }
        
        if ($this->getBadge()) {
            $this->tag->append(html: $this->getBadge());
        }
        
        $this->reset();
        
        return $this->tag->render();
    }
    
    /**
     * Returns the first child link item or null if none.
     *
     * @return null|Link
     */    
    protected function getFirstChildLink(): null|Link
    {
        $childItems = array_filter(
            $this->menu->all(),
            fn ($item) => $item->getTreeParent() === $this->getTreeId() && $item instanceof Link
        );
        
        $key = array_key_first($childItems);
        
        return is_null($key) ? null : $childItems[$key];
    }
}