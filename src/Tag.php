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
 * Tag
 */
class Tag
{
    /**
     * @var string
     */    
    protected string $name;

    /**
     * @var string
     */    
    protected string $content = '';

    /**
     * @var string
     */    
    protected string $prepend = '';

    /**
     * @var string
     */    
    protected string $append = '';    

    /**
     * @var Attributes
     */    
    protected Attributes $attributes;
    
    /**
     * @var null|int The level depth of the tag
     */    
    protected ?int $level = null;

    
    /**
     * @var null|callable The tag handler
     */    
    protected $handler = null;       
        
    /**
     * Create a new Tag
     *
     * @param string $name The tag name such as 'li'
     * @param string $html The tag html content
     * @param null|Attributes $attributes
     * @param null|int $level The level depth of the tag
     */
    public function __construct(
        string $name,
        string $html = '',
        ?Attributes $attributes = null,
        ?int $level = null
    ){
        $this->name = $name;
        $this->content($html);
        $this->attributes = $attributes ?: new Attributes();
        $this->level = $level;
    }
    
    /**
     * Set the html content of the tag.
     *
     * @param string $html
     * @return static $this
     */    
    public function content(string $html): static
    {
        $this->content .= $html;
        return $this;
    }

    /**
     * Prepend html content.
     *
     * @param string $html
     * @return static $this
     */    
    public function prepend(string $html): static
    {
        $this->prepend .= $html;
        return $this;
    }
    
    /**
     * Append html content.
     *
     * @param string $html
     * @return static $this
     */    
    public function append(string $html): static
    {
        $this->append .= $html;
        return $this;
    }
 
    /**
     * Set the level depth of the tag.
     *
     * @param int $level
     * @return static $this
     */    
    public function level(int $level): static
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get the level depth of the tag.
     *
     * @return null|int
     */    
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * Get the tag name.
     *
     * @return string
     */    
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Get the evaluated contents of the tag.
     *
     * @return string
     */    
    public function render(): string
    {
        $content = $this->prepend.$this->content.$this->append;
            
        if (empty($content)) {
            return '';
        }
        
        return $this->open().$content.$this->close();
    }
    
    /**
     * Renders the opening tag.
     *
     * @return string
     */    
    public function open(): string
    {
        $name = Str::esc($this->name);
        
        if ($this->attributes->empty()) {
            return "<{$name}>";
        }

        return "<{$name} {$this->attributes}>";
    }

    /**
     * Renders the closing tag.
     *
     * @return string
     */    
    public function close(): string
    {
        $name = Str::esc($this->name);
        
        return "</{$name}>";
    }

    /**
     * Set an attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return static $this
     */    
    public function attr(string $name, mixed $value): static
    {
        $this->attributes()->set($name, $value);
        return $this;
    }
    
    /**
     * Add an class to the attributes.
     *
     * @param string $value
     * @return static $this
     */    
    public function class(string $value): static
    {
        $this->attributes()->add('class', $value);
        return $this;
    }  
    
    /**
     * Get the attributes
     *
     * @return Attributes
     */    
    public function attributes(): Attributes
    {
        return $this->attributes;
    }
    
    /**
     * Handle Tag
     *
     * @param callable $callable
     * @return static $this
     */    
    public function handle(callable $callable): static
    {
        $this->handler = $callable;
        return $this;
    }

    /**
     * Get the handler if any
     *
     * @return null|callable
     */    
    public function getHandler(): ?callable
    {
       return $this->handler;
    }                
}