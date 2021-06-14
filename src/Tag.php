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
     * @param string The tag name such as 'li'
     * @param string The tag content
     * @param null|Attributes
     * @param null|int The level depth of the tag
     */
    public function __construct(
        string $name,
        string $content = '',
        ?Attributes $attributes = null,
        ?int $level = null
    ){
        $this->name = $name;
        $this->content = $content;
        $this->attributes = $attributes ?: new Attributes();
        $this->level = $level;
    }
    
    /**
     * Set the content of the tag.
     *
     * @param string
     * @return static $this
     */    
    public function content(string $content): static
    {
        $this->content .= $content;
        return $this;
    }

    /**
     * Prepend content.
     *
     * @param string
     * @return static $this
     */    
    public function prepend(string $content): static
    {
        $this->prepend .= $content;
        return $this;
    }
    
    /**
     * Append content.
     *
     * @param string
     * @return static $this
     */    
    public function append(string $content): static
    {
        $this->append .= $content;
        return $this;
    }
 
    /**
     * Set the level depth of the tag.
     *
     * @param int
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
        if ($this->attributes->empty()) {
            return "<{$this->name}>";
        }

        return "<{$this->name} {$this->attributes}>";
    }

    /**
     * Renders the closing tag.
     *
     * @return string
     */    
    public function close(): string
    {
        return "</{$this->name}>";
    }

    /**
     * Set an attribute.
     *
     * @param string
     * @param mixed
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
     * @param string
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
     * @param callable
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