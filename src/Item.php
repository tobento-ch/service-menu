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
use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\HasTag;
use Stringable;

/**
 * Item
 */
class Item implements ItemInterface
{
    use TreeableAware;
    use HasTag;

    /**
     * @var bool
     */
    protected bool $disabled = false;
    
    /**
     * @var int
     */
    protected int $order = 0;
    
    /**
     * @var null|string
     */
    protected null|string $icon = null;
    
    /**
     * @var null|TagInterface
     */
    protected null|TagInterface $badge = null;
    
    /**
     * @var null|TagInterface
     */
    protected null|TagInterface $parentTag = null;
 
    /**
     * @var null|TagInterface
     */
    protected null|TagInterface $itemTag = null;
    
    /**
     * Create a new Item
     *
     * @param string|Stringable $text
     * @param null|string|int $id
     * @param null|string|int $parent
     * @param bool $active
     */
    public function __construct(
        protected string|Stringable $text,
        protected null|string|int $id = null,
        protected null|string|int $parent = null,
        protected bool $active = false
    ) {
        $this->setTag(new NullTag(Str::esc($text)));
    }

    /**
     * Get the text.
     *
     * @return string
     */    
    public function text(): string
    {
        return (string)$this->text;
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
     * Set an icon.
     *
     * @param null|string $name
     * @return static $this
     */
    public function icon(null|string $name): static
    {
        $this->icon = $name;
        return $this;
    }
    
    /**
     * Get the icon.
     *
     * @return null|string
     */
    public function getIcon(): null|string
    {
        return $this->icon;
    }
    
    /**
     * Set an badge.
     *
     * @param null|string $text
     * @param array $attributes
     * @return static $this
     */
    public function badge(null|string $text, array $attributes = []): static
    {
        if (is_null($text)) {
            $this->badge = null;
            return $this;
        }
        
        $attributes = new Attributes($attributes);
        $attributes->add('class', 'badge');
        
        $badge = new Tag(
            name: 'span',
            html: Str::esc($text),
            attributes: $attributes,
        );
        
        $this->badge = $badge;
        return $this;
    }
    
    /**
     * Set an badge only if the badge parameter is true.
     *
     * @param bool $badge
     * @param null|string $text
     * @param array $attributes
     * @return static $this
     */
    public function badgeIf(bool $badge, null|string $text, array $attributes = []): static
    {
        return $badge ? $this->badge($text, $attributes) : $this;
    }
    
    /**
     * Get the badge.
     *
     * @return null|TagInterface
     */    
    public function getBadge(): null|TagInterface
    {
        return $this->badge;
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
     * @param null|TagInterface $tag
     * @return null|TagInterface
     */
    public function parentTag(null|TagInterface $tag = null): null|TagInterface
    {
        if (!is_null($tag)) {
            $this->parentTag = $tag;
        }
        
        return $this->parentTag;
    }

    /**
     * Set/Get the item tag.
     *
     * @param null|TagInterface $tag
     * @return TagInterface
     */
    public function itemTag(null|TagInterface $tag = null): TagInterface
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
        if ($this->getBadge()) {
            $this->tag->append(html: $this->getBadge());
        }
        
        $this->itemTag = null;
        
        return $this->tag->render();
    }
    
    /**
     * Get the tree id
     *
     * @return string|int
     */
    public function getTreeId(): string|int
    {
        return $this->id ?: $this->text();
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