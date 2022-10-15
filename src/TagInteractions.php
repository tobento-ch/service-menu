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
 * TagInteractions
 */
trait TagInteractions
{
    /**
     * @var null|callable The tag handler
     */
    protected $handler = null;
    
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
    public function getHandler(): null|callable
    {
       return $this->handler;
    }
}