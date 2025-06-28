<?php

namespace Prism\Prism\Contracts;

use Prism\Prism\Enums\Provider;

abstract class ProviderRequestMapper
{
    abstract public function toPayload(): array;

    abstract protected function provider(): string|Provider;
}
