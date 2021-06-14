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
     * @param ItemInterface
     * @return static $this
     */
    public function add(ItemInterface $item): static;

    /**
     * Adding items
     *
     * @param array
     * @param null|callable function(MenuInterface $menu, $item): void {}
     * @return static $this
     */
    public function many(array $items, ?callable $callback = null): static;

    /**
     * Get an item
     *
     * @param string|int
     * @return null|ItemInterface
     */
    public function get(string|int $id): ?ItemInterface;
    
    /**
     * Sorts the items.
     *
     * @param callable
     * @return static $this
     */    
    public function sort(callable $callback): static;
        
    /**
     * Filter the items.
     * 
     * @param callable
     * @return static $this
     */
    public function filter(callable $callable): static;
    
    /**
     * Iterate over all items
     *
     * @param callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function each(callable $callable): static;
    
    /**
     * Callback for the given item id. Set it active for instance. 
     *
     * @param string|int The item id
     * @param callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function on(string|int $id, callable $callable): static;

    /**
     * Callback for the given item id and all it parents.
     *
     * @param string|int The item id
     * @param callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function onParents(string|int $id, callable $callable): static;
    
    /**
     * Add an item
     *
     * @param string The text
     * @return Item
     */
    public function item(string $text): Item;
        
    /**
     * Add a link item
     *
     * @param string The url
     * @param string The text
     * @return Link
     */
    public function link(string $url, string $text): Link;
    
    /**
     * Add a tag
     *
     * @param string The tag name
     * @return Tag
     */
    public function tag(string $name): Tag;
    
    /**
     * Set the active menu by id.
     *
     * @param string|int
     * @return static $this
     */
    public function active(string|int $id): static;
    
    /**
     * If to render subitems. If false, only active subitems are rendered.
     *
     * @param bool
     * @return static $this
     */
    public function subitems(bool $withSubitems = true): static;  

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
}