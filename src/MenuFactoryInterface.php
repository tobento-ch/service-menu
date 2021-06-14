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
 * MenuFactoryInterface
 */
interface MenuFactoryInterface
{        
    /**
     * Create a new menu
     *
     * @param string The menu name.
     * @return MenuInterface
     */
    public function createMenu(string $name): MenuInterface;
}