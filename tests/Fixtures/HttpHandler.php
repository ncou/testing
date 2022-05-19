<?php

declare(strict_types=1);

namespace Chiron\Test\Testing\Fixtures;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpHandler implements RequestHandlerInterface
{
    private ResponseFactoryInterface $factory;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->factory->createResponse();

        switch ((string) $request->getUri()) {
            case '/success':
                $response = $response->withStatus(200);

                break;
            case '/forbidden':
                $response = $response->withStatus(403);

                break;
            case '/unavailable':
                $response = $response->withStatus(503);

                break;
            case '/headers':
                $response = $response->withHeader('X-Custom-Header', 'foobar');

                break;
            case '/content-type':
                $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');

                break;
            case '/without-body':
                $response = $response->withStatus(204);

                break;
            case '/with-body':
                $response->getBody()->write('foobar');

                break;
        }

        return $response;
    }
}
