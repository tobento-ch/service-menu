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
 * MenuInterface
 */
interface MenuInterface
{
    /**
     * Get the menu name
     *
     * @return string
     */
    public function name(): string;
    
    /**
     * Add an item
     *
     * @param ItemInterface $item
     * @return static $this
     */
    public function add(ItemInterface $item): static;

    /**
     * Adding items
     *
     * @param array $items
     * @param null|callable $callback function(MenuInterface $menu, $item): void {}
     * @return static $this
     */
    public function many(array $items, ?callable $callback = null): static;

    /**
     * Get an item
     *
     * @param string|int $id
     * @return null|ItemInterface
     */
    public function get(string|int $id): ?ItemInterface;
    
    /**
     * Sorts the items.
     *
     * @param callable $callback
     * @return static $this
     */    
    public function sort(callable $callback): static;
        
    /**
     * Filter the items.
     * 
     * @param callable $callable
     * @return static $this
     */
    public function filter(callable $callable): static;
    
    /**
     * Iterate over all items
     *
     * @param callable $callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function each(callable $callable): static;
    
    /**
     * Callback for the given item id. Set it active for instance. 
     *
     * @param string|int $id The item id
     * @param callable $callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function on(string|int $id, callable $callable): static;

    /**
     * Callback for the given item id and all it parents.
     *
     * @param string|int $id The item id
     * @param callable $callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function onParents(string|int $id, callable $callable): static;
    
    /**
     * Add an item
     *
     * @param string|Stringable $text The text
     * @return Item
     */
    public function item(string|Stringable $text): Item;
        
    /**
     * Add a link item
     *
     * @param string|Stringable $url The url
     * @param string|Stringable $text The text
     * @return Link
     */
    public function link(string|Stringable $url, string|Stringable $text): Link;
    
    /**
     * Add a html item
     *
     * @param string|Stringable $html
     * @return Html
     */
    public function html(string|Stringable $html): Html;
    
    /**
     * Add a tag
     *
     * @param string $name The tag name
     * @return TagInterface
     */
    public function tag(string $name): TagInterface;
    
    /**
     * Set the active menu by id.
     *
     * @param string|int $id
     * @return static $this
     */
    public function active(string|int $id): static;
    
    /**
     * If to render subitems. If false, only active subitems are rendered.
     *
     * @param bool $withSubitems
     * @return static $this
     */
    public function subitems(bool $withSubitems = true): static;
    
    /**
     * Set if to render only icons.
     *
     * @param bool $onlyIcons
     * @return static $this
     */
    public function onlyIcons(bool $onlyIcons = true): static;
    
    /**
     * Returns true if to render only icons, otherwise false.
     *
     * @return bool
     */
    public function getOnlyIcons(): bool;
    
    /**
     * Set the icon position.
     *
     * @param string $position
     * @return static $this
     */
    public function iconPosition(string $position): static;
    
    /**
     * Returns the icon position.
     *
     * @return string
     */
    public function getIconPosition(): string;

    /**
     * Get the evaluated contents of the menu.
     *
     * @return string
     */    
    public function render(): string;

    /**
     * Create the menu tree
     *
     * @return array The created tree.
     */
    public function create(): array;

    /**
     * Get all items
     *
     * @return array The items
     */
    public function all(): array;
    
    /**
     * Returns true if menu has items, otherwise false.
     *
     * @return bool
     */
    public function hasItems(): bool;
}