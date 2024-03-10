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
use Tobento\Service\Menu\Html;

/**
 * MenuTest tests
 */
class MenuTest extends TestCase
{
    public function testMenuAddMethod()
    {
        $menu = (new Menu('footer'))
            ->add(new Item('about us'))
            ->add(new Link('/contact', 'contact'))
            ->add(new Item('team', null, 'about us'))
            ->add(new Html('<span>foo</span>'));
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li><li><a href="/contact">contact</a></li><li><span>foo</span></li></ul>',
            $menu->render()
        );        
    }
    
    public function testMenuManyMethod()
    {
        $items = [
            [
                'name' => 'about',
            ],
            [
                'name' => 'contact',
            ],
            [
                'name' => 'team',
                'parent' => 'about',
            ],    
        ];

        $menu = (new Menu('footer'))->many($items, function($menu, $item) {

            $menu->link('/'.$item['name'], $item['name'])
                 ->parent($item['parent'] ?? null);
        });
        
        $this->assertEquals(
            '<ul><li><a href="/about">about</a><ul><li><a href="/team">team</a></li></ul></li><li><a href="/contact">contact</a></li></ul>',
            $menu->render()
        );        
    }    
    
    public function testMenuItemsMethod()
    {
        $menu = new Menu('footer');
        $menu->item('about us');
        $menu->link('/contact', 'contact');
        $menu->html('team')->parent('about us');
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li><li><a href="/contact">contact</a></li></ul>',
            $menu->render()
        );        
    }
    
    public function testMenuItemMethodWithParent()
    {
        $menu = new Menu('footer');
        $menu->item('about us');
        $menu->item('team')->parent('about us');
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li></ul>',
            $menu->render()
        );        
    }

    public function testMenuItemMethodWithInvalidParent()
    {
        $menu = new Menu('footer');
        $menu->item('about us');
        $menu->item('team')->parent('about');
        
        $this->assertEquals(
            '<ul><li>about us</li></ul>',
            $menu->render()
        );        
    }

    public function testMenuItemMethodWithId()
    {
        $menu = new Menu('footer');
        $menu->item('about us')->id('about');
        $menu->item('team')->parent('about');
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li></ul>',
            $menu->render()
        );        
    }  
    
    public function testMenuItemMethodWithDisableItem()
    {
        $menu = new Menu('footer');
        $menu->item('about')->disabled();
        $menu->item('team')->parent('about');
        
        $this->assertEquals(
            '',
            $menu->render()
        );        
    }

    public function testMenuItemMethodWithDisableParentItem()
    {
        $menu = new Menu('footer');
        $menu->item('about');
        $menu->item('team')->parent('about')->disabled();
        
        $this->assertEquals(
            '<ul><li>about</li></ul>',
            $menu->render()
        );        
    }

    public function testMenuLinkMethod()
    {
        $menu = new Menu('footer');
        $menu->link('/about', 'about us');
        $menu->link('/team', 'team')->parent('about us');
        
        $this->assertEquals(
            '<ul><li><a href="/about">about us</a><ul><li><a href="/team">team</a></li></ul></li></ul>',
            $menu->render()
        );        
    }
    
    public function testMenuLinkMethodWithEmptyUrlShouldNotGenereateHref()
    {
        $menu = new Menu('footer');
        $menu->link('', 'about us');
        $menu->link('/team', 'team')->parent('about us');
        
        $this->assertEquals(
            '<ul><li><a>about us</a><ul><li><a href="/team">team</a></li></ul></li></ul>',
            $menu->render()
        );        
    }

    public function testMenuSortMethod()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        $menu->item('about');

        $menu->sort(fn ($a, $b) => $a->text() <=> $b->text());
        
        $this->assertEquals(
            '<ul><li>about</li><li>team</li></ul>',
            $menu->render()
        );        
    }

    public function testMenuFilterMethod()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        $menu->item('about');

        $menu->filter(fn($i) => $i->text() === 'team');
        
        $this->assertEquals(
            '<ul><li>team</li></ul>',
            $menu->render()
        );        
    }
    
    public function testMenuEachMethod()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        $menu->item('about');

        $menu->each(function($item, $menu) {

            $parentTag = $item->parentTag();
            $itemTag = $item->itemTag();
            $treeLevel = $item->getTreeLevel();
            $treeId = $item->getTreeId();
            $treeParent = $item->getTreeParent();
            $treeParentItem = $item->getTreeParentItem();
            $treeChildren = $item->getTreeChildren();

            return $item;
        });
        
        $this->assertEquals(
            '<ul><li>team</li><li>about</li></ul>',
            $menu->render()
        );        
    }
    
    public function testOnMethod()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        $menu->item('about');
       
        $menu->on('team', function($item, $menu) {

            $item->itemTag()->class('foo');
            $item->parentTag()->class('bar');

            return $item;
        });
        
        $this->assertEquals(
            '<ul class="bar"><li class="foo">team</li><li>about</li></ul>',
            $menu->render()
        );        
    }
    
    public function testOnParentsMethod()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        $menu->item('about');
       
        $menu->onParents('team', function($item, $menu) {

            $item->itemTag()->class('foo');

            return $item;
        });
        
        $this->assertEquals(
            '<ul><li class="foo">team</li><li>about</li></ul>',
            $menu->render()
        );        
    }
    
    public function testOnParentsMethodWithChildren()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('about');
       
        $menu->onParents('team', function($item, $menu) {

            $item->itemTag()->class('foo');

            return $item;
        });
        
        $this->assertEquals(
            '<ul><li class="foo">about<ul><li class="foo">team</li></ul></li></ul>',
            $menu->render()
        );        
    }
    
    public function testActiveMethodWithSubitemsDisabled()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        // set the form item active.
        $menu->active('form');

        // do no render any inactive tree items.
        $menu->subitems(false);
        
        $this->assertEquals(
            '<ul><li>about</li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );        
    }
    
    public function testGetMethodSameItem()
    {
        $menu = new Menu('footer');
        $item = $menu->item('team')->parent('about');
        $menu->item('about');
        
        $this->assertEquals(
            $item,
            $menu->get('team')
        );        
    }
    
    public function testGetMethodWithSetItemTagActiveClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('about');

        $menu->get('team')->itemTag()->class('active');
        
        $this->assertEquals(
            '<ul><li>about<ul><li class="active">team</li></ul></li></ul>',
            $menu->render()
        );        
    }

    public function testAllMethod()
    {
        $menu = new Menu('footer');
        $team = $menu->item('team')->parent('about');
        $about = $menu->item('about');
        
        $this->assertEquals(
            [
                'team' => $team,
                'about' => $about,
            ],
            $menu->all()
        );        
    }
    
    public function testHasItemsMethod()
    {
        $menu = new Menu('footer');
        
        $this->assertFalse($menu->hasItems());
        
        $about = $menu->item('about');
        
        $this->assertTrue($menu->hasItems());
    }

    public function testMenuRenderMultipleTimes()
    {
        $menu = (new Menu('footer'))
            ->add(new Item('about us'))
            ->add(new Link('/contact', 'contact'))
            ->add(new Item('team', null, 'about us'));
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li><li><a href="/contact">contact</a></li></ul>',
            $menu->render()
        ); 
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li><li><a href="/contact">contact</a></li></ul>',
            $menu->render()
        );
        
        $this->assertEquals(
            '<ul><li>about us<ul><li>team</li></ul></li><li><a href="/contact">contact</a></li></ul>',
            $menu->render()
        ); 
    }    
}