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
use Tobento\Service\Menu\Item;
use Tobento\Service\Menu\Link;
use Tobento\Service\Menu\LinkToFirstChild;
use Tobento\Service\Menu\Html;

/**
 * MenuLinkToFirstChildTest tests
 */
class MenuLinkToFirstChildTest extends TestCase
{
    public function testUsesFirstChildLinkIfExists()
    {
        $menu = new Menu('main');
        $menu->add(new LinkToFirstChild(menu: $menu, text: 'Settings', id: 'settings'));
        $menu->item('about us');
        $menu->link('/foo', 'foo');
        $menu->item('bar')->parent('settings');
        $menu->link('/trans', 'trans')->parent('settings');
        $menu->link('/locales', 'locales')->parent('settings');
        
        $this->assertEquals(
            '<ul><li><a href="/trans">Settings</a><ul><li>bar</li><li><a href="/trans">trans</a></li><li><a href="/locales">locales</a></li></ul></li><li>about us</li><li><a href="/foo">foo</a></li></ul>',
            $menu->render()
        );
    }
    
    public function testUsesFirstChildLinkIfExistsUsingIdMethod()
    {
        $menu = new Menu('main');
        $menu->add((new LinkToFirstChild($menu, 'Settings'))->id('settings'));
        $menu->item('about us');
        $menu->link('/foo', 'foo');
        $menu->item('bar')->parent('settings');
        $menu->link('/trans', 'trans')->parent('settings');
        $menu->link('/locales', 'locales')->parent('settings');
        
        $this->assertEquals(
            '<ul><li><a href="/trans">Settings</a><ul><li>bar</li><li><a href="/trans">trans</a></li><li><a href="/locales">locales</a></li></ul></li><li>about us</li><li><a href="/foo">foo</a></li></ul>',
            $menu->render()
        );
    }
    
    public function testDoesNotRenderLinkIfNoChildExistsAtAll()
    {
        $menu = new Menu('main');
        $menu->add((new LinkToFirstChild($menu, 'Settings'))->id('settings'));
        $menu->item('about us');
        $menu->link('/trans', 'trans');
        
        $this->assertEquals(
            '<ul><li>about us</li><li><a href="/trans">trans</a></li></ul>',
            $menu->render()
        );
    }
    
    public function testWithEmptyChildLinkUrlShouldRenderItem()
    {
        $menu = new Menu('main');
        $menu->add((new LinkToFirstChild($menu, 'Settings'))->id('settings'));
        $menu->link('', 'locales')->parent('settings');
        
        $this->assertEquals(
            '<ul><li><a>Settings</a><ul><li><a>locales</a></li></ul></li></ul>',
            $menu->render()
        );
    }
    
    public function testWithBadge()
    {
        $menu = new Menu('main');
        $menu->add(
            (new LinkToFirstChild($menu, 'Settings'))
                ->id('settings')
                ->badge(text: '10', attributes: ['title' => '10 new'])
        );
        $menu->link('/locales', 'locales')->parent('settings');
        
        $this->assertEquals(
            '<ul><li><a href="/locales">Settings<span title="10 new" class="badge">10</span></a><ul><li><a href="/locales">locales</a></li></ul></li></ul>',
            $menu->render()
        );
    }
}