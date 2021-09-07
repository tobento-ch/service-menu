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

use Tobento\Service\Treeable\TreeableAware;

/**
 * Item
 */
class Item implements ItemInterface
{
    use TreeableAware;

    /**
     * @var bool
     */    
    protected bool $disabled = false;
    
    /**
     * @var int
     */    
    protected int $order = 0;
    
    /**
     * @var null|Tag
     */    
    protected ?Tag $parentTag = null;
 
    /**
     * @var null|Tag
     */
    protected ?Tag $itemTag = null;
    
    /**
     * Create a new Item
     *
     * @param string $text
     * @param null|string|int $id
     * @param null|string|int $parent
     * @param bool $active
     */
    public function __construct(
        protected string $text,
        protected null|string|int $id = null,
        protected null|string|int $parent = null,
        protected bool $active = false
    ) {}

    /**
     * Get the text.
     *
     * @return string
     */    
    public function text(): string
    {
        return $this->text;
    }
    
    /**
     * Set the id.
     *
     * @param string|int $id
     * @return static $this
     */    
    public function id(string|int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the parent.
     *
     * @param null|string|int $parent
     * @return static $this
     */    
    public function parent(null|string|int $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Set if the item is active.
     *
     * @param bool $active
     * @return static $this
     */    
    public function active(bool $active = true): static
    {
        $this->active = $active;
        return $this;
    }
    
    /**
     * It the item is active.
     *
     * @return bool
     */    
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Set if the item is disabled.
     *
     * @param bool $disabled
     * @return static $this
     */    
    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * It the item is disable.
     *
     * @return bool
     */    
    public function isDisabled(): bool
    {
        return $this->disabled;
    }
    
    /**
     * Set the sort order
     *
     * @param int $order
     * @return static $this
     */    
    public function order(int $order): static
    {        
        $this->order = $order;
        return $this;
    }

    /**
     * Get the order.
     *
     * @return int
     */    
    public function getOrder(): int
    {
        return $this->order;
    }    
    
    /**
     * Set/Get the parent tag.
     *
     * @param null|Tag $tag
     * @return null|Tag
     */    
    public function parentTag(?Tag $tag = null): ?Tag
    {
        if (!is_null($tag)) {
            $this->parentTag = $tag;
        }
        
        return $this->parentTag;
    }

    /**
     * Set/Get the item tag.
     *
     * @param null|Tag $tag
     * @return Tag
     */    
    public function itemTag(?Tag $tag = null): Tag
    {
        if (!is_null($tag)) {
            $this->itemTag = $tag;
        }
        
        if (is_null($this->itemTag)) {
            $this->itemTag = new Tag('li');
        }        
        
        return $this->itemTag;
    }    
    
    /**
     * Get the evaluated contents of the item.
     *
     * @return string
     */    
    public function render(): string
    {
        $this->itemTag = null;
        
        return Str::esc($this->text);
    }
    
    /**
     * Get the tree id
     *
     * @return string|int
     */
    public function getTreeId(): string|int
    {
        return $this->id ?: $this->text;
    }
    
    /**
     * Get the tree parent
     *
     * @return null|string|int
     */
    public function getTreeParent(): null|string|int
    {
        return $this->parent;
    }
}