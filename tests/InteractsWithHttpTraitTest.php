<?php

declare(strict_types=1);

namespace Chiron\Test\Testing;

use Chiron\Container\Container;
use Chiron\Dev\Tools\TestSuite\AbstractTestCase;
use Chiron\Event\EventDispatcher;
use Chiron\Event\ListenerProvider;
use Chiron\Http\Http;
use Chiron\Testing\Traits\InteractsWithHttpTrait;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;

//https://github.com/cakephp/cakephp/blob/5.x/tests/TestCase/TestSuite/IntegrationTestTraitTest.php

class InteractsWithHttpTraitTest extends AbstractTestCase
{
    use InteractsWithHttpTrait;

    /**
     * assertResponseOk
    assertResponseSuccess
    assertResponseError
    assertResponseFailure
    assertResponseCode

    assertRedirect ????
    assertRedirectEquals ????

    assertRedirectContains
    assertRedirectNotContains
    assertNoRedirect
    assertHeader
    assertHeaderContains
    assertHeaderNotContains
    assertContentType
    assertResponseEquals
    assertResponseNotEquals
    assertResponseContains
    assertResponseNotContains
    assertResponseRegExp
    assertResponseNotRegExp
    assertResponseNotEmpty
    assertResponseEmpty
     */
    public function testRequestSuccess(): void
    {
        $request = new ServerRequest('GET', '/');
        $this->runRequest($request);

        $this->assertResponseOk();
    }

    protected function http(): Http
    {
        $container = new Container();

        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $container->singleton(EventDispatcherInterface::class, $dispatcher);
        $container->singleton(ResponseFactoryInterface::class, Psr17Factory::class);

        return new Http($container, $dispatcher);
    }
}
