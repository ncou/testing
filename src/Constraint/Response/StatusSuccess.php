<?php

declare(strict_types=1);

namespace Chiron\Testing\Constraint\Response;

/**
 * StatusSuccess
 *
 * @internal
 */
class StatusSuccess extends StatusCodeBase
{
    /**
     * @var array<int, int>|int
     */
    protected array|int $code = [200, 308];

    /**
     * Assertion message
     *
     * @return string
     */
    public function toString(): string
    {
        return sprintf('%d is between 200 and 308', $this->response->getStatusCode());
    }
}
