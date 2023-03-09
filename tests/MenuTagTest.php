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
use Tobento\Service\Menu\Tag;
use Tobento\Service\Menu\NullTag;

/**
 * MenuTagTest tests
 */
class MenuTagTest extends TestCase
{    
    public function testTagUlChangeToOl()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn() => new Tag('ol'));
        
        $this->assertEquals(
            '<ol><li>about<ol><li>team<ol><li>phones</li></ol></li></ol></li><li>contact<ol><li>form</li></ol></li></ol>',
            $menu->render()
        );      
    }
    
    public function testTagUlChangeToOlOnlyLevelSecond()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->level(2)->handle(fn() => new Tag('ol'));
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ol><li>phones</li></ol></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }
    
    public function testTagUlChangeToOlWithAttributes()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn() => new Tag('ol'));
        
        // adds foo class to every ul tag.
        $menu->tag('ul')->class('foo');
        
        $this->assertEquals(
            '<ol class="foo"><li>about<ol class="foo"><li>team<ol class="foo"><li>phones</li></ol></li></ol></li><li>contact<ol class="foo"><li>form</li></ol></li></ol>',
            $menu->render()
        );      
    }
    
    public function testTagUlChangeToOlWithSpecificLevelAttributes()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn(Tag $t) => (new Tag('ol'))->level($t->getLevel()));
        
        // adds foo class to every ul tag with level 1.
        $menu->tag('ul')->level(1)->class('foo');
        
        $this->assertEquals(
            '<ol><li>about<ol class="foo"><li>team<ol><li>phones</li></ol></li></ol></li><li>contact<ol class="foo"><li>form</li></ol></li></ol>',
            $menu->render()
        );      
    }    
    
    public function testTagUlAndLiToNullTag()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn() => new NullTag());
        $menu->tag('li')->handle(fn() => new NullTag());
        
        $this->assertEquals(
            'aboutteamphonescontactform',
            $menu->render()
        );      
    }    
    
    public function testTagUlAddClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every ul tag.
        $menu->tag('ul')->class('foo');
        
        $this->assertEquals(
            '<ul class="foo"><li>about<ul class="foo"><li>team<ul class="foo"><li>phones</li></ul></li></ul></li><li>contact<ul class="foo"><li>form</li></ul></li></ul>',
            $menu->render()
        );        
    }
    
    public function testTagUlAddMultipleClass()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        // adds foo class to every ul tag.
        $menu->tag('ul')->class('foo')->class('bar');
        
        $this->assertEquals(
            '<ul class="foo bar"><li>team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagUlAddAttr()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        // adds foo class to every ul tag.
        $menu->tag('ul')->attr('data-foo', '1');
        
        $this->assertEquals(
            '<ul data-foo="1"><li>team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagUlAddSameAttrTwiceMergesValuesToJsonString()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('ul')->attr('data-foo', '1');
        $menu->tag('ul')->attr('data-foo', '1');
        
        $this->assertEquals(
            '<ul data-foo=\'[&quot;1&quot;,&quot;1&quot;]\'><li>team</li></ul>',
            $menu->render()
        );        
    }
 
    public function testTagUlAddMultipleAttr()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('ul')->attr('data-foo', '1')->attr('data-bar', '1');
        
        $this->assertEquals(
            '<ul data-foo="1" data-bar="1"><li>team</li></ul>',
            $menu->render()
        );        
    }
    
    public function testTagUlAddAttrWithNullValue()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('ul')->attr('disabled', null);
        
        $this->assertEquals(
            '<ul disabled><li>team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagUlAddAttrWithNoValue()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('ul')->attr('disabled');
        
        $this->assertEquals(
            '<ul disabled><li>team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagUlWithLevelFirst()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every ul tag with level 1.
        $menu->tag('ul')->level(1)->class('foo');
        
        $this->assertEquals(
            '<ul><li>about<ul class="foo"><li>team<ul><li>phones</li></ul></li></ul></li><li>contact<ul class="foo"><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }
    
    public function testTagUlWithLevelSecond()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every ul tag with level 2.
        $menu->tag('ul')->level(2)->class('foo');
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ul class="foo"><li>phones</li></ul></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagUlWithLevelInvalid()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every ul tag with level 3.
        $menu->tag('ul')->level(3)->class('foo');
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ul><li>phones</li></ul></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagUlHandleWithClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn($t) => $t->class('foo'));
        
        $this->assertEquals(
            '<ul class="foo"><li>about<ul class="foo"><li>team<ul class="foo"><li>phones</li></ul></li></ul></li><li>contact<ul class="foo"><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagUlHandleWithAppend()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn($t) => $t->append(' foo'));
        
        $this->assertEquals(
            '<ul> foo<li>about<ul> foo<li>team<ul> foo<li>phones</li></ul></li></ul></li><li>contact<ul> foo<li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }
    
    public function testTagUlHandleWithPrepend()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->handle(fn($t) => $t->prepend('foo '));
        
        $this->assertEquals(
            '<ul>foo <li>about<ul>foo <li>team<ul>foo <li>phones</li></ul></li></ul></li><li>contact<ul>foo <li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagUlLevelAndHandleWithClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('ul')->level(2)->handle(fn($t) => $t->class('foo'));
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ul class="foo"><li>phones</li></ul></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagLiAddClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every li tag.
        $menu->tag('li')->class('foo');
        
        $this->assertEquals(
            '<ul><li class="foo">about<ul><li class="foo">team<ul><li class="foo">phones</li></ul></li></ul></li><li class="foo">contact<ul><li class="foo">form</li></ul></li></ul>',
            $menu->render()
        );        
    }
    
    public function testTagLiAddMultipleClass()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        // adds foo and bar class to every li tag.
        $menu->tag('li')->class('foo')->class('bar');
        
        $this->assertEquals(
            '<ul><li class="foo bar">team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagLiAddAttr()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        // adds foo class to every li tag.
        $menu->tag('li')->attr('data-foo', '1');
        
        $this->assertEquals(
            '<ul><li data-foo="1">team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagLiAddSameAttrTwiceMergesValuesToJsonString()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('li')->attr('data-foo', '1');
        $menu->tag('li')->attr('data-foo', '1');
        
        $this->assertEquals(
            '<ul><li data-foo=\'[&quot;1&quot;,&quot;1&quot;]\'>team</li></ul>',
            $menu->render()
        );        
    }
 
    public function testTagLiAddMultipleAttr()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('li')->attr('data-foo', '1')->attr('data-bar', '1');
        
        $this->assertEquals(
            '<ul><li data-foo="1" data-bar="1">team</li></ul>',
            $menu->render()
        );        
    }
    
    public function testTagLiAddAttrWithNullValue()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('li')->attr('disabled', null);
        
        $this->assertEquals(
            '<ul><li disabled>team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagLiAddAttrWithNoValue()
    {
        $menu = new Menu('footer');
        $menu->item('team');
        
        $menu->tag('li')->attr('disabled');
        
        $this->assertEquals(
            '<ul><li disabled>team</li></ul>',
            $menu->render()
        );        
    }

    public function testTagLiWithLevelFirst()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every li tag with level 1.
        $menu->tag('li')->level(1)->class('foo');
        
        $this->assertEquals(
            '<ul><li>about<ul><li class="foo">team<ul><li>phones</li></ul></li></ul></li><li>contact<ul><li class="foo">form</li></ul></li></ul>',
            $menu->render()
        );      
    }
    
    public function testTagLiWithLevelSecond()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every li tag with level 2.
        $menu->tag('li')->level(2)->class('foo');
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ul><li class="foo">phones</li></ul></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagLiWithLevelInvalid()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');
        
        // adds foo class to every li tag with level 3.
        $menu->tag('li')->level(3)->class('foo');
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ul><li>phones</li></ul></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagLiHandleWithClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('li')->handle(fn($t) => $t->class('foo'));
        
        $this->assertEquals(
            '<ul><li class="foo">about<ul><li class="foo">team<ul><li class="foo">phones</li></ul></li></ul></li><li class="foo">contact<ul><li class="foo">form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagLiHandleWithAppend()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('li')->handle(fn($t) => $t->append(' foo'));
        
        $this->assertEquals(
            '<ul><li>about foo<ul><li>team foo<ul><li>phones foo</li></ul></li></ul></li><li>contact foo<ul><li>form foo</li></ul></li></ul>',
            $menu->render()
        );      
    }
    
    public function testTagLiHandleWithPrepend()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('li')->handle(fn($t) => $t->prepend('foo '));
        
        $this->assertEquals(
            '<ul><li>foo about<ul><li>foo team<ul><li>foo phones</li></ul></li></ul></li><li>foo contact<ul><li>foo form</li></ul></li></ul>',
            $menu->render()
        );      
    }

    public function testTagLiLevelAndHandleWithClass()
    {
        $menu = new Menu('footer');
        $menu->item('team')->parent('about');
        $menu->item('phones')->parent('team');
        $menu->item('about');
        $menu->item('contact');
        $menu->item('form')->parent('contact');

        $menu->tag('li')->level(2)->handle(fn($t) => $t->class('foo'));
        
        $this->assertEquals(
            '<ul><li>about<ul><li>team<ul><li class="foo">phones</li></ul></li></ul></li><li>contact<ul><li>form</li></ul></li></ul>',
            $menu->render()
        );      
    }    
}