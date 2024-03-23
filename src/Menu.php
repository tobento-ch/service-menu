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

use Tobento\Service\Tag\Taggable;
use Tobento\Service\Treeable\Tree;
use Stringable;

/**
 * Menu
 */
class Menu implements MenuInterface
{    
    /**
     * @var array [ItemInterface, ...]
     */    
    protected array $items = [];

    /**
     * @var null|ItemInterface
     */    
    protected ?ItemInterface $item = null;    

    /**
     * @var array
     */    
    protected array $tags = [];

    /**
     * @var array
     */    
    protected array $each = []; 
    
    /**
     * @var array
     */    
    protected array $on = [];

    /**
     * @var array
     */    
    protected array $onParents = [];   

    /**
     * @var array Tree callables
     */    
    protected array $tree = []; 
    
    /**
     * @var bool
     */    
    protected bool $withSubitems = true;
    
    /**
     * @var bool
     */    
    protected bool $onlyIcons = false;
    
    /**
     * @var string
     */    
    protected string $iconPosition = 'left';

    /**
     * Create a new menu
     *
     * @param string $name A menu name
     */
    public function __construct(
        protected string $name
    ) {}
        
    /**
     * Add an item
     *
     * @param ItemInterface $item
     * @return static $this
     */
    public function add(ItemInterface $item): static
    {
        if (! $item->isDisabled()) {
            $this->items[$item->getTreeId()] = $item;
        }    
        
        return $this;
    }

    /**
     * Adding items
     *
     * @param array $items
     * @param null|callable $callback function(MenuInterface $menu, $item): void {}
     * @return static $this
     */
    public function many(array $items, ?callable $callback = null): static
    {
        foreach($items as $item) {
            if ($callback) {
                call_user_func_array($callback, [$this, $item]);
            } else {
                if ($item instanceof ItemInterface) {
                    $this->add($item);
                }
            }
        }

        return $this;
    }

    /**
     * Get an item
     *
     * @param string|int $id
     * @return null|ItemInterface
     */
    public function get(string|int $id): ?ItemInterface
    {
        $this->addLastItem();
        
        return $this->items[$id] ?? null;
    }

    /**
     * Sorts the items.
     *
     * @param callable $callback
     * @return static $this
     */    
    public function sort(callable $callback): static
    {
        $this->items = $this->all();
        
        uasort($this->items, $callback);
        return $this;
    }
        
    /**
     * Filter the items.
     * 
     * @param callable $callable
     * @return static $this
     */
    public function filter(callable $callable): static
    {        
        $this->items = array_filter($this->all(), $callable);
        return $this;
    }
    
    /**
     * Iterate over all items
     *
     * @param callable $callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function each(callable $callable): static
    {
        $this->each[] = $callable;
        return $this;
    }
    
    /**
     * Callback for the given item id. Set it active for instance. 
     *
     * @param string|int $id The item id
     * @param callable $callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function on(string|int $id, callable $callable): static
    {
        $this->on[$id][] = $callable;
        return $this;
    }

    /**
     * Callback for the given item id and all it parents.
     *
     * @param string|int $id The item id
     * @param callable $callable function(ItemInterface $item, MenuInterface $menu): void {}
     * @return static $this
     */
    public function onParents(string|int $id, callable $callable): static
    {
        $this->onParents[$id][] = $callable;
        return $this;
    }
    
    /**
     * Add an item
     *
     * @param string|Stringable $text The text
     * @return Item
     */
    public function item(string|Stringable $text): Item
    {
        $this->addLastItem();
        
        return $this->item = new Item($text);
    }
        
    /**
     * Add a link item
     *
     * @param string|Stringable $url The url
     * @param string|Stringable $text The text
     * @return Link
     */
    public function link(string|Stringable $url, string|Stringable $text): Link
    {
        $this->addLastItem();
        
        return $this->item = new Link($url, $text);
    }
    
    /**
     * Add a html item
     *
     * @param string|Stringable $html
     * @return Html
     */
    public function html(string|Stringable $html): Html
    {
        $this->addLastItem();
        
        return $this->item = new Html($html);
    }
    
    /**
     * Add a tag
     *
     * @param string $name The tag name
     * @return TagInterface
     */
    public function tag(string $name): TagInterface
    {
        return $this->tags[$name][] = new Tag($name);
    }
    
    /**
     * Set the active menu by id.
     *
     * @param string|int $id
     * @return static $this
     */
    public function active(string|int $id): static
    {
        $this->tree[$id] = function(Tree $tree) use ($id): void {
            $tree->parents($id, function(ItemInterface $item): ItemInterface {
                return $item->active();
            });            
        };
        
        return $this;
    }
    
    /**
     * If to render subitems. If false, only active subitems are rendered.
     *
     * @param bool $withSubitems
     * @return static $this
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function subitems(bool $withSubitems = true): static
    {    
        if (!$withSubitems) {
            $this->tree['__withSubitems'] = function(Tree $tree): void {

                $tree->each(function(ItemInterface $item): null|ItemInterface {

                    if ($item->getTreeParentItem() && !$item->getTreeParentItem()->isActive()) {
                        return null;
                    }

                    return $item;
                });
            };
        } else {
            unset($this->tree['__withSubitems']);
        }
        
        return $this;
    }   

    /**
     * Get the evaluated contents of the menu.
     *
     * @return string
     * @psalm-suppress UndefinedInterfaceMethod
     */    
    public function render(): string
    {        
        // Handle on callables
        $this->each(function(ItemInterface $item, MenuInterface $menu): null|ItemInterface {
            if (isset($this->on[$item->getTreeId()])) {
                foreach($this->on[$item->getTreeId()] as $callable) {
                    if (is_null($item = call_user_func_array($callable, [$item, $menu]))) {
                        continue;
                    }
                }
            }
            
            if (isset($this->onParents[$item->getTreeId()])) {
                foreach($this->onParents[$item->getTreeId()] as $callable) {
                    
                    $traverseParent = function(ItemInterface $item, MenuInterface $menu) use ($callable, &$traverseParent): void {
                        
                        call_user_func_array($callable, [$item, $menu]);
                        
                        if ($item->getTreeParentItem()) {
                            $traverseParent($item->getTreeParentItem(), $menu);
                        }
                    };
                    
                    $traverseParent($item, $menu);
                }
            }
            
            return $item;
        });
        
        // Traverse items.
        $traverse = function(array $items, int $level) use (&$traverse): string {
            
            $tag = (new Tag('ul'))->level($level);            
            $tag = $this->handleTag($tag);
            
            foreach ($items as $item) {
            
                $item->parentTag($tag);
                $itemTag = $this->handleTag($item->itemTag()->level($level));
                
                if ($item instanceof Taggable) {
                    $item->setTag($this->handleTag($item->tag()->level($item->getTreeLevel())));
                }
                
                foreach($this->each as $callable) {
                    if (is_null($item = call_user_func_array($callable, [$item, $this]))) {
                        continue 2;
                    }
                }
                
                if (!empty($item->getTreeChildren())) {
                    $itemTag->append($traverse($item->getTreeChildren(), $level+1));
                }
                
                $itemTag->prepend($item->render());
                $tag->append($itemTag->render());
            }
            
            return $tag->render();
        };
        
        return $traverse($this->create(), 0);
    }

    /**
     * Create the menu tree
     *
     * @return array The created tree.
     */
    public function create(): array
    {
        $tree = new Tree($this->all());
        
        foreach($this->tree as $callable) {
            call_user_func_array($callable, [$tree]);
        }
            
        return $tree->create();
    }

    /**
     * Get all items
     *
     * @return array The items
     */
    public function all(): array
    {
        $this->addLastItem();
        
        return $this->items;
    }
    
    /**
     * Returns true if menu has items, otherwise false.
     *
     * @return bool
     */
    public function hasItems(): bool
    {
        return !empty($this->all());
    }
    
    /**
     * Get the menu name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
    
    /**
     * Set if to render only icons.
     *
     * @param bool $onlyIcons
     * @return static $this
     */
    public function onlyIcons(bool $onlyIcons = true): static
    {
        $this->onlyIcons = $onlyIcons;
        return $this;
    }
    
    /**
     * Returns true if to render only icons, otherwise false.
     *
     * @return bool
     */
    public function getOnlyIcons(): bool
    {
        return $this->onlyIcons;
    }
    
    /**
     * Set the icon position.
     *
     * @param string $position
     * @return static $this
     */
    public function iconPosition(string $position): static
    {
        $this->iconPosition = $position;
        return $this;
    }
    
    /**
     * Returns the icon position.
     *
     * @return string
     */
    public function getIconPosition(): string
    {
        return $this->iconPosition;
    }
        
    /**
     * Returns the string representation of the menu.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Adds the last item
     *
     * @return void
     */
    protected function addLastItem(): void
    {
        if ($this->item) {
            $this->add($this->item);
            $this->item = null;
        }        
    }
    
    /**
     * Handle a tag
     *
     * @param TagInterface $tag
     * @return TagInterface
     */
    protected function handleTag(TagInterface $tag): TagInterface
    {
        if (! array_key_exists($tag->getName(), $this->tags)) {
            return $tag;
        }
        
        foreach($this->tags[$tag->getName()] as $menuTag) {
            if (is_null($menuTag->getLevel())) {
                $tag = $this->handleTags($menuTag, $tag);            
            } elseif ($menuTag->getLevel() === $tag->getLevel()) {
                $tag = $this->handleTags($menuTag, $tag);
            }
        }
        
        return $tag;
    }
    
    /**
     * Handle a tags
     *
     * @param TagInterface $menuTag The menu tag
     * @param TagInterface $tag The tree tag
     * @return TagInterface
     */
    protected function handleTags(TagInterface $menuTag, TagInterface $tag): TagInterface
    {
        if ($menuTag->getHandler()) {
            $tag = call_user_func($menuTag->getHandler(), $tag);
        }
        
        $tag->attributes()->merge($menuTag->attributes()->all());
            
        return $tag;
    }        
}