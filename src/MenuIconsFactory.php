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

use Tobento\Service\Icon\IconsInterface;

/**
 * MenuIconsFactory
 */
class MenuIconsFactory implements MenuFactoryInterface
{
    /**
     * Create a new MenuIconsFactory.
     *
     * @param IconsInterface $icons
     */
    public function __construct(
        protected IconsInterface $icons
    ) {}
    
    /**
     * Create a new menu
     *
     * @param string $name The menu name.
     * @return MenuInterface
     */
    public function createMenu(string $name): MenuInterface
    {        
        $menu = new Menu($name);
        
        $icons = $this->icons;
        
        $menu->each(static function(ItemInterface $item, MenuInterface $menu) use ($icons): ItemInterface {
            if (!$item->getIcon()) {
                return $item;
            }
            
            if ($menu->getOnlyIcons()) {
                $icon = $icons->get($item->getIcon());
            } else {
                $position = $menu->getIconPosition() === 'right' ? 'left': 'right';

                $icon = $icons->get($item->getIcon())
                    ->label(text: $item->text(), position: $position);
            }

            $tag = $item->tag()->withHtml($icon->render());
            return $item->setTag($tag);
        });
        
        return $menu;
    }
}