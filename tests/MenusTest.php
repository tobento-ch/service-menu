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

namespace Tobento\Service\Menu\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Menus;
use Tobento\Service\Menu\MenuFactory;

/**
 * MenusTest tests
 */
class MenusTest extends TestCase
{
    public function testMenusCreateNew()
    {
        $menus = new Menus();
        
        $this->assertInstanceOf(Menus::class, $menus);
        
        $menus = new Menus(new MenuFactory());
        
        $this->assertInstanceOf(Menus::class, $menus);      
    }
    
    public function testMenusAddAndGetMenu()
    {
        $menus = new Menus();
        
        $menu = new Menu('main');
        
        $menus->add($menu);
        
        $this->assertSame($menu, $menus->get('main'));
    }
    
    public function testMenusMenuMethod()
    {
        $menus = new Menus();
                
        $menu = $menus->menu('main');
        
        $this->assertSame($menu, $menus->get('main'));
    }
    
    public function testMenusGetMethodReturnsNullIfMenuDoesNotExist()
    {
        $menus = new Menus();
        
        $this->assertSame(null, $menus->get('main'));
    }    
}