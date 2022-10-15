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

use Tobento\Service\Tag\TagInterface as ServiceTagInterface;

/**
 * TagInterface
 */
interface TagInterface extends ServiceTagInterface
{
    /**
     * Set the level depth of the tag.
     *
     * @param int $level
     * @return static $this
     */
    public function level(int $level): static;
    
    /**
     * Handle Tag
     *
     * @param callable $callable
     * @return static $this
     */
    public function handle(callable $callable): static;

    /**
     * Get the handler if any
     *
     * @return null|callable
     */
    public function getHandler(): null|callable;
}