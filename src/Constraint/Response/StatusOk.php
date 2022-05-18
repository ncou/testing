<?php

declare(strict_types=1);

namespace Chiron\Testing\Constraint\Response;

/**
 * StatusOk
 *
 * @internal
 */
class StatusOk extends AbstractStatusCodeBase
{
    /**
     * @var array<int, int>|int
     */
    protected array|int $code = [200, 204];

    /**
     * Assertion message
     *
     * @return string
     */
    public function toString(): string
    {
        return sprintf('%d is between 200 and 204', $this->response->getStatusCode());
    }
}
