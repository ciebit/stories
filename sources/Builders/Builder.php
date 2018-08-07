<?php
declare(strict_types=1);

namespace Ciebit\Stories\Builders;

use Ciebit\Stories\Story;

interface Builder
{
    public function build(): Story;
}
