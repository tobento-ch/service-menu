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
 * Menus
 */
class Menus implements MenusInterface
{
    /**
     * @var MenuFactoryInterface
     */    
    protected MenuFactoryInterface $menuFactory;
    
    /**
     * @var array ['name' => MenuInterface, ...]
     */    
    protected array $menus = [];

    /**
     * Create a new Menus collection
     *
     * @param null|MenuFactoryInterface
     */
    public function __construct(
        ?MenuFactoryInterface $menuFactory = null
    ){
        $this->menuFactory = $menuFactory ?: new MenuFactory();
    }
    
    /**
     * Add a menu
     *
     * @param MenuInterface
     * @return static $this
     */
    public function add(MenuInterface $menu): static
    {
        $this->menus[$menu->name()] = $menu;
        return $this;
    }

    /**
     * Get the menu or create it
     *
     * @param string The menu name
     * @return MenuInterface
     */
    public function menu(string $name): MenuInterface
    {
        return $this->menus[$name] ??= $this->menuFactory->createMenu($name);
    }
    
    /**
     * Get a menu
     *
     * @param string The menu name
     * @return null|MenuInterface
     */
    public function get(string $name): ?MenuInterface
    {
        return $this->menus[$name] ?? null;
    }    
}