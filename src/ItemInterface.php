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

use Tobento\Service\Treeable\Treeable;
use Tobento\Service\Tag\Taggable;

/**
 * ItemInterface
 */
interface ItemInterface extends Treeable, Taggable
{
    /**
     * Set/Get the parent tag.
     *
     * @param null|TagInterface $tag
     * @return null|TagInterface
     */
    public function parentTag(null|TagInterface $tag = null): null|TagInterface;

    /**
     * Set/Get the item tag.
     *
     * @param null|TagInterface $tag
     * @return TagInterface
     */
    public function itemTag(null|TagInterface $tag = null): TagInterface;

    /**
     * Set if the item is active.
     *
     * @param bool $active
     * @return static $this
     */    
    public function active(bool $active = true): static;
        
    /**
     * If the item is active
     *
     * @return bool
     */    
    public function isActive(): bool;

    /**
     * Set if the item is disabled.
     *
     * @param bool $disabled
     * @return static $this
     */    
    public function disabled(bool $disabled = true): static;

    /**
     * It the item is disable.
     *
     * @return bool
     */    
    public function isDisabled(): bool;
    
    /**
     * Get the text.
     *
     * @return string
     */    
    public function text(): string;
    
    /**
     * Get the icon.
     *
     * @return null|string
     */
    public function getIcon(): null|string;
    
    /**
     * Get the evaluated contents of the item.
     *
     * @return string
     */    
    public function render(): string;
}