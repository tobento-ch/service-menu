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

use Tobento\Service\Tag\NullTag as ServiceNullTag;
use Stringable;

/**
 * NullTag
 */
class NullTag extends ServiceNullTag implements TagInterface
{
    use TagInteractions;
}