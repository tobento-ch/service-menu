# Menu Service

With the Menu Service you can build menus easily.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
    - [Simple Example](#simple-example)
- [Documentation](#documentation)
	- [Menu](#menu)
        - [Creating menu items](#creating-menu-items)
        - [Creating subitems](#creating-subitems)
        - [Sorting items](#sorting-items)
        - [Iterating items](#iterating-items)
        - [On specific item](#on-specific-item)
        - [On parent items](#on-parent-items)
        - [Active item](#active-item)
        - [Get item(s)](#get-items)
        - [Tags](#tags)
        - [Escaping](#escaping)
    - [Menus](#menus)
    - [Examples](#examples)
- [Credits](#credits)
___

# Getting started

Add the latest version of the Menu service project running this command.

```
composer require tobento/service-menu
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

## Simple Example

Here is a simple example of how to use the Menu service.

```php
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Item;
use Tobento\Service\Menu\Link;

$menu = (new Menu('footer'))
    ->add(new Item('about us'))
    ->add(new Link('/contact', 'contact'))
    ->add(new Item('team', null, 'about us'));

$menu = new Menu('footer');
$menu->item('about us');
$menu->link('/contact', 'contact');
$menu->item('team')->parent('about us');
```

Render the menu:

```php
<?= $menu->render() ?>

// or just
<?= $menu ?>
```

Both menus from above will produce the following output.

```html
<ul>
    <li>about us
        <ul>
            <li>team</li>
        </ul>
    </li>
    <li><a href="/contact">contact</a></li>
</ul>
```
# Documentation

## Menu

### Creating menu items

Creating items with the add() method.

```php
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Item;
use Tobento\Service\Menu\Link;

$menu = (new Menu('footer'))
    ->add(new Item('about us'))
    ->add(new Link('/contact', 'contact'));
```

Creating items with the build in item() and link() method.

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$item = $menu->item('about us');
$linkItem = $menu->link('/contact', 'contact');
```

Creating items with the many() method.

```php
use Tobento\Service\Menu\Menu;

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
```

### Creating subitems

Creating subitems is done by defining the parent item by its id.

```php
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Item;
use Tobento\Service\Menu\Link;

$menu = (new Menu('footer'))
    ->add(new Item('about us'))
    ->add(new Item('team', parent: 'about us'));

$menu = new Menu('footer');
$menu->item('about us');
$menu->item('team')->parent('about us');

// or by defining an id
$menu = (new Menu('footer'))
    ->add(new Item('about us', id: 'about'))
    ->add(new Item('team', parent: 'about'));
    
$menu = new Menu('footer');
$menu->item('about us')->id('about');
$menu->item('team')->parent('about');
```

### Sorting items

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team');
$menu->item('about');

$menu->sort(fn ($a, $b) => $a->text() <=> $b->text());
```

### Iterating items

By using the filter method:

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team');
$menu->item('about');

$menu->filter(fn($i) => $i->text() === 'team');
```

By using the each method having access to data tree and tags:

```php
use Tobento\Service\Menu\Menu;

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
```

### On specific item

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team');
$menu->item('about');

$menu->on('team', function($item, $menu) {
        
    $item->itemTag()->class('foo');
    $item->parentTag()->class('bar');
    
    return $item;
});
```

```html
<ul class="bar">
    <li class="foo">team</li>
    <li>about</li>
</ul>
```

### On parent items

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team')->parent('about');
$menu->item('about');
$menu->item('contact');

$menu->onParents('team', function($item, $menu) {
    
    $item->itemTag()->class('foo');
    
    return $item;
});
```

```html
<ul>
    <li class="foo">about
        <ul>
            <li class="foo">team</li>
        </ul>
    </li>
    <li>contact</li>
</ul>
```

### Active item

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team')->parent('about');
$menu->item('about');
$menu->item('contact');
$menu->item('form')->parent('contact');

// set the form item and all its parent items active.
$menu->active('form');
```

```html
<ul>
    <li>about
        <ul>
            <li>team</li>
        </ul>
    </li>
    <li>contact
        <ul>
            <li>form</li>
        </ul>
    </li>
</ul>
```

Render only active tree items:

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team')->parent('about');
$menu->item('about');
$menu->item('contact');
$menu->item('form')->parent('contact');

// set the form item active.
$menu->active('form');

// do not render any inactive tree items.
$menu->subitems(false);
```

```html
<ul>
    <li>about</li>
    <li>contact
        <ul>
            <li>form</li>
        </ul>
    </li>
</ul>
```

Set the items active on the item itself:

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team')->parent('about');
$menu->item('about');
$menu->item('contact')->active();
$menu->item('form')->parent('contact')->active();

// do no render any inactive tree items.
$menu->subitems(false);
```

```html
<ul>
    <li>about</li>
    <li>contact
        <ul>
            <li>form</li>
        </ul>
    </li>
</ul>
```

### Get item(s)

Note: Items tree data and tags are not available yet, except item tag.

Get single item:

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team');

$menu->get('team')->itemTag()->class('active');
```

Get all items:

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team');

$items = $menu->all();
```

### Tags

With tags you can manage the menu tags being rendered.

#### Menu Tags

```php
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Tag;
use Tobento\Service\Menu\NullTag;

$menu = new Menu('footer');

// add class foo to every ul tag.
$menu->tag('ul')->class('foo');

// add class foo only to every ul tag with depth level 1.
$menu->tag('ul')->level(1)->class('foo');

// add class foo to every li tag.
$menu->tag('li')->class('foo');

// add class foo and bar only to every li tag with depth level 2.
$menu->tag('li')->level(2)->class('foo')->class('bar');

// add any attribute with the attr() method.
$menu->tag('li')->attr('data-foo', '1');

// change every ul tag to ol tag.
$menu->tag('ul')->handle(fn() => new Tag('ol'));

// change every ul tag to div tag.
$menu->tag('ul')->handle(fn() => new Tag('div'));

// change every li tag to a null tag and prepend and append a character.
$menu->tag('li')->handle(fn() => (new NullTag())->prepend('[')->append(']'));

// get tag attributes.
$attributes = $menu->tag('li')->attributes();

// check if there are any attributes.
$empty = $attributes->empty();

// check if there is a specific attribute.
$hasClassAttr = $attributes->has('class');

// get any attribute.
$classAttr = $attributes->get('class');

// get any attribute.
$classAttr = $attributes->get('class');

// get all attributes.
$allAttributes = $attributes->all();

// set an attribute.
$attributes->set('data-foo', '1');

// add an attribute (merges).
$attributes->add('data-foo', '1');

// merge an attribute.
$attributes->merge('data-foo', '1');
```

#### Item Tags

```php
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Taggable;

$menu = new Menu('footer');
$item = $menu->link('/contact', 'contact');

// The item tag.
$item->itemTag()->class('bar');

// link items implements the Taggable interface, meaning it has a own tag. The a tag.
$item->tag()->class('foo');

// The parent tag is not yet available.
var_dump($item->parentTag()); // NULL
// Use each(), on(), onParents() methods if you need to manage the parent tag.
```

### Escaping

Be careful as no data gets escaped in any way.
So do it by your own if you need to do so.

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item(htmlspecialchars('contact', ENT_QUOTES, 'UTF-8'));
```

## Menus

```php
use Tobento\Service\Menu\Menus;
use Tobento\Service\Menu\Menu;

// Create menus.
$menus = new Menus();

// Create menus with a custom menu factory.
$menus = new Menus(new CustomMenuFactory());

// Add a menu.
$menus->add(new Menu('main'));

// Get the main menu. If it does not exist, it returns null.
$menus->get('main');

// Get the main menu. If it does not exist, it creates a new one.
$menus->menu('main')
      ->item('about')
      ->order(1000);
```

## Examples

### Add active class to active item only

```php
use Tobento\Service\Menu\Menu;

$menu = new Menu('footer');
$menu->item('team')->parent('about');
$menu->item('about');
$menu->item('contact');
$menu->item('form')->parent('contact');

$menu->on('form', function($item, $menu) {
    $item->itemTag()->class('active');
    $item->parentTag()->class('active');
    return $item;
});
```
```html
<ul>
    <li>about
        <ul>
            <li>team</li>
        </ul>
    </li>
    <li>contact
        <ul class="active">
            <li class="active">form</li>
        </ul>
    </li>
</ul>
```

### Add active class to active items

```php
use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\Taggable;

$menu = new Menu('footer');
$menu->item('team')->parent('about');
$menu->item('about');
$menu->item('contact');
$menu->item('form')->parent('contact');

$menu->onParents('form', function($item, $menu) {
        
    $item->itemTag()->class('active');
    
    if ($item->getTreeLevel() > 0) {
        $item->parentTag()->class('active');
    }
    
    if ($item instanceof Taggable) {
        $item->tag()->class('active');
    }
    
    return $item;
});

// do only render active tree.
$menu->active('form')
     ->subitems(false);
```
```html
<ul>
    <li>about</li>
    <li class="active">
        <a class="active" href="/contact">contact</a>
        <ul class="active">
            <li class="active">form</li>
        </ul>
    </li>
</ul>
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)