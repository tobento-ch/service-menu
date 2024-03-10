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

class MenuBadgesTest extends TestCase
{
    public function testItemBadge()
    {
        $menu = new Menu('header');
        $menu->item('foo')
            ->badge(text: 'new', attributes: ['title' => 'Title']);
        
        $this->assertSame(
            '<ul><li>foo<span title="Title" class="badge">new</span></li></ul>',
            $menu->render()
        );
    }
    
    public function testItemBadgeIf()
    {
        $menu = new Menu('header');
        $menu->item('foo')
            ->badgeIf(badge: true, text: 'new', attributes: ['title' => 'Title']);
        
        $menu->item('bar')
            ->badgeIf(badge: false, text: 'new bar', attributes: ['title' => 'Title Bar']);
        
        $this->assertSame(
            '<ul><li>foo<span title="Title" class="badge">new</span></li><li>bar</li></ul>',
            $menu->render()
        );
    }
    
    public function testLinkBadge()
    {
        $menu = new Menu('header');
        $menu->link('/foo', 'Foo')
            ->badge(text: 'new', attributes: ['title' => 'Title']);
        
        $this->assertSame(
            '<ul><li><a href="/foo">Foo<span title="Title" class="badge">new</span></a></li></ul>',
            $menu->render()
        );
    }
    
    public function testLinkBadgeIf()
    {
        $menu = new Menu('header');
        $menu->link('/foo', 'Foo')
            ->badgeIf(badge: true, text: 'new', attributes: ['title' => 'Title']);
        
        $menu->link('/bar', 'Bar')
            ->badgeIf(badge: false, text: 'new bar', attributes: ['title' => 'Title Bar']);
        
        $this->assertSame(
            '<ul><li><a href="/foo">Foo<span title="Title" class="badge">new</span></a></li><li><a href="/bar">Bar</a></li></ul>',
            $menu->render()
        );
    }
    
    public function testHtmlBadge()
    {
        $menu = new Menu('header');
        $menu->html('foo')
            ->badge(text: 'new', attributes: ['title' => 'Title']);
        
        $this->assertSame(
            '<ul><li>foo<span title="Title" class="badge">new</span></li></ul>',
            $menu->render()
        );
    }
    
    public function testHtmlBadgeIf()
    {
        $menu = new Menu('header');
        $menu->html('foo')
            ->badgeIf(badge: true, text: 'new', attributes: ['title' => 'Title']);
        
        $menu->html('bar')
            ->badgeIf(badge: false, text: 'new bar', attributes: ['title' => 'Title Bar']);
        
        $this->assertSame(
            '<ul><li>foo<span title="Title" class="badge">new</span></li><li>bar</li></ul>',
            $menu->render()
        );
    }
}