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

use Tobento\Service\Tag\Tag as ServiceTag;
use Tobento\Service\Tag\AttributesInterface;
use Tobento\Service\Tag\Attributes;
use Stringable;

/**
 * Tag
 */
class Tag extends ServiceTag implements TagInterface
{
    use TagInteractions;

    /**
     * Create a new Tag.
     *
     * @param string $name The tag name such as 'li'.
     * @param string|Stringable $html The tag html content.
     * @param null|AttributesInterface $attributes
     * @param null|int $level The level depth of the tag.
     * @param bool $renderEmptyTag
     */
    public function __construct(
        protected string $name,
        string|Stringable $html = '',
        null|AttributesInterface $attributes = null,
        protected null|int $level = null,
        protected bool $renderEmptyTag = false
    ){
        $this->html = (string)$html;
        $this->attributes = $attributes ?: new Attributes();
    }
}