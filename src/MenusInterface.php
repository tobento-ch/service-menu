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
interface MenusInterface
{
    /**
     * Add a menu
     *
     * @param MenuInterface $menu
     * @return static $this
     */
    public function add(MenuInterface $menu): static;

    /**
     * Get the menu or create it
     *
     * @param string $name The menu name
     * @return MenuInterface
     */
    public function menu(string $name): MenuInterface;
    
    /**
     * Get a menu
     *
     * @param string $name The menu name
     * @return null|MenuInterface
     */
    public function get(string $name): ?MenuInterface;
}