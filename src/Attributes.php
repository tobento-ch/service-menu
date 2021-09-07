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
 * Attributes
 */
class Attributes
{        
    /**
     * Create a new Attributes
     *
     * @param array $attributes
     */
    public function __construct(
        protected array $attributes = []
    ) {}
    
    /**
     * If attributes empty.
     *
     * @return bool
     */
    public function empty(): bool
    {    
        return empty($this->attributes);        
    }

    /**
     * If an attribute exists.
     *
     * @param string $name The name.
     * @return bool True if exist, else false.
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Get an attribute
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {        
        return $this->attributes[$name] ?? null;
    }
    
    /**
     * Set an attribute
     *
     * @param string $name
     * @param mixed $value
     * @return static $this
     */
    public function set(string $name, mixed $value): static
    {        
        $this->attributes[$name] = $value;
        return $this;
    }
    
    /**
     * Add an attribute
     *
     * @param string $name
     * @param mixed $value
     * @return static $this
     */
    public function add(string $name, mixed $value): static
    {        
        if ($this->has($name)) {
            $this->set($name, array_merge(
                $this->ensureArray($this->get($name)),
                $this->ensureArray($value)
            ));
            
            return $this;
        }
        
        $this->set($name, $value);
        
        return $this;
    }
    
    /**
     * Merge attributes
     *
     * @param array $attributes The attributes to merge
     * @return static $this
     */
    public function merge(array $attributes): static
    {
        foreach($attributes as $name => $value) {
            $this->add($name, $value);
        }
        
        return $this;
    }  
    
    /**
     * Get all attributes
     *
     * @return array
     */
    public function all(): array
    {
        return $this->attributes;
    } 
    
    /**
     * Returns the string representation of the attributes.
     *
     * @return string
     */
    public function __toString(): string
    {        
        if ($this->empty()) {
            return '';
        }

        $attributes = [];

        foreach($this->attributes as $name => $value) {
            
            if (is_int($name)) {
                $attributes[] = Str::esc($value);
                continue;
            }
            
            if (is_null($value) || $value === '') {
                $attributes[] = Str::esc($name);
                continue;
            }
            
            if (is_array($value)) {
                
                if ($name === 'class') {
                    $attributes[] = Str::esc($name).'="'.Str::esc(implode(' ', array_unique($value))).'"';
                } else {                    
                    $attributes[] = Str::esc($name)."='".Str::esc(json_encode($value))."'";
                }
                
            } else {
                $attributes[] = Str::esc($name).'="'.Str::esc($value).'"';
            }
        }

        return implode(' ', $attributes);
    }

    /**
     * Ensure array
     * 
     * @param mixed $value The value
     * @return array
     */         
    protected function ensureArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }
        
        return [$value];
    }    
}