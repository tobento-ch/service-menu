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

use Stringable;

/**
 * Html
 */
class Html extends Item
{
    /**
     * Create a new Html.
     *
     * @param string|Stringable $html
     * @param null|string|int $id
     */
    public function __construct(
        protected string|Stringable $html,
        null|string|int $id = null
    ){
        parent::__construct(strip_tags((string)$html), $id);
    }
    
    /**
     * Get the evaluated contents of the item.
     *
     * @return string
     */
    public function render(): string
    {
        $this->itemTag = null;
        
        return (string)$this->html;
    }
}