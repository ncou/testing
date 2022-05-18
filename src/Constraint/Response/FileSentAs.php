<?php

declare(strict_types=1);

namespace Chiron\Testing\Constraint\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * FileSentAs
 *
 * @internal
 */
class FileSentAs extends AbstractResponseBase
{
    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * Checks assertion
     *
     * @param mixed $other Expected type
     *
     * @return bool
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function matches($other): bool
    {
        /** @psalm-suppress PossiblyNullReference */
        return $this->response->getFile()->getPathName() === $other;
    }

    /**
     * Assertion message
     *
     * @return string
     */
    public function toString(): string
    {
        return 'file was sent';
    }
}
