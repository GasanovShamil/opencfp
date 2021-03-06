<?php

declare(strict_types=1);

/**
 * Copyright (c) 2013-2018 OpenCFP.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/opencfp/opencfp
 */

namespace OpenCFP\Domain\Speaker;

class NotAllowedException extends \Exception
{
    public static function notAllowedToView(string $property): self
    {
        return new self(\sprintf('Not allowed to view %s. Hidden property', $property));
    }
}
