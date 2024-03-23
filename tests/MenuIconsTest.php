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
use Tobento\Service\Menu\MenuFactoryInterface;
use Tobento\Service\Menu\MenuIconsFactory;
use Tobento\Service\Menu\Menu;
use Tobento\Service\Icon\Icons;
use Tobento\Service\Icon\StackIcons;
use Tobento\Service\Icon\InMemoryHtmlIcons;
use Tobento\Service\Icon\IconFactory;

class MenuIconsTest extends TestCase
{
    protected function createIconsMenuFactory(): MenuFactoryInterface
    {
        $icons = new StackIcons(
            new InMemoryHtmlIcons(
                icons: [
                    'foo' => 'FooIcon',
                    'bar' => 'BarIcon',
                ],
                iconFactory: new IconFactory(),
            ),
            new Icons(new IconFactory()),    
        );
        
        return new MenuIconsFactory(icons: $icons);
    }
    
    public function testIconsAreNotRenderedAtDefault()
    {
        $menu = new Menu('footer');
        $menu->item('foo')->icon('foo');
        $menu->link('/bar', 'bar')->icon('bar');
        $menu->html('baz')->icon('baz');
        
        $this->assertSame(
            '<ul><li>foo</li><li><a href="/bar">bar</a></li><li>baz</li></ul>',
            $menu->render()
        );
    }
    
    public function testIconsRendered()
    {
        $menu = $this->createIconsMenuFactory()->createMenu(name: 'header');
        $menu->item('foo')->icon('foo');
        $menu->link('/bar', 'bar')->icon('bar');
        $menu->html('baz')->icon('baz');
        
        $this->assertSame(
            '<ul><li><span class="icon icon-foo">FooIcon<span class="icon-label">foo</span></span></li><li><a href="/bar"><span class="icon icon-bar">BarIcon<span class="icon-label">bar</span></span></a></li><li><span class="icon icon-baz"><span class="icon-label">baz</span></span></li></ul>',
            $menu->render()
        );
    }
    
    public function testIconsRenderedPositionedLeft()
    {
        $menu = $this->createIconsMenuFactory()->createMenu(name: 'header');
        $menu->item('foo')->icon('foo');
        
        $menu->iconPosition('left');
        
        $this->assertSame(
            '<ul><li><span class="icon icon-foo">FooIcon<span class="icon-label">foo</span></span></li></ul>',
            $menu->render()
        );
    }
    
    public function testIconsRenderedPositionedRight()
    {
        $menu = $this->createIconsMenuFactory()->createMenu(name: 'header');
        $menu->item('foo')->icon('foo');
        
        $menu->iconPosition('right');
        
        $this->assertSame(
            '<ul><li><span class="icon icon-foo"><span class="icon-label">foo</span>FooIcon</span></li></ul>',
            $menu->render()
        );
    }
    
    public function testIconsAreNotRenderedIfNotSpecified()
    {
        $menu = $this->createIconsMenuFactory()->createMenu(name: 'header');
        $menu->item('foo');
        
        $this->assertSame(
            '<ul><li>foo</li></ul>',
            $menu->render()
        );
    }
    
    public function testOnlyIconsAreRendered()
    {
        $menu = $this->createIconsMenuFactory()->createMenu(name: 'header');
        $menu->item('foo')->icon('foo');
        $menu->link('/bar', 'bar')->icon('bar');
        $menu->html('baz')->icon('baz');
        $menu->link('/noicon', 'noicon');
        
        $menu->onlyIcons();
        
        $this->assertSame(
            '<ul><li><span class="icon icon-foo">FooIcon</span></li><li><a href="/bar"><span class="icon icon-bar">BarIcon</span></a></li><li><span class="icon icon-baz"></span></li><li><a href="/noicon">noicon</a></li></ul>',
            $menu->render()
        );
    }
}