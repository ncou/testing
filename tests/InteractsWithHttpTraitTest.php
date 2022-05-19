<?php

declare(strict_types=1);

namespace Chiron\Test\Testing;

use Chiron\Container\Container;
use Chiron\Dev\Tools\TestSuite\AbstractTestCase;
use Chiron\Event\EventDispatcher;
use Chiron\Event\ListenerProvider;
use Chiron\Http\Http;
use Chiron\Test\Testing\Fixtures\HttpHandler;
use Chiron\Testing\Traits\InteractsWithHttpTrait;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;

//https://github.com/cakephp/cakephp/blob/5.x/tests/TestCase/TestSuite/IntegrationTestTraitTest.php

class InteractsWithHttpTraitTest extends AbstractTestCase
{
    use InteractsWithHttpTrait;

    /**
     * Test the responseOk status assertion
     */
    public function testAssertResponseStatusCodes(): void
    {
        $this->response = new Response();

        $this->response = $this->response->withStatus(200);
        $this->assertResponseOk();

        $this->response = $this->response->withStatus(201);
        $this->assertResponseOk();

        $this->response = $this->response->withStatus(204);
        $this->assertResponseOk();

        $this->response = $this->response->withStatus(202);
        $this->assertResponseSuccess();

        $this->response = $this->response->withStatus(302);
        $this->assertResponseSuccess();

        $this->response = $this->response->withStatus(400);
        $this->assertResponseError();

        $this->response = $this->response->withStatus(417);
        $this->assertResponseError();

        $this->response = $this->response->withStatus(500);
        $this->assertResponseFailure();

        $this->response = $this->response->withStatus(505);
        $this->assertResponseFailure();

        $this->response = $this->response->withStatus(301);
        $this->assertResponseCode(301);
    }

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
    public function testHttpSuccess(): void
    {
        $request = new ServerRequest('GET', '/success');
        $this->runRequest($request);

        $this->assertResponseOk();
        $this->assertResponseSuccess();
        $this->assertResponseCode(200);
    }

    public function testHttpError(): void
    {
        $request = new ServerRequest('GET', '/forbidden');
        $this->runRequest($request);

        $this->assertResponseError();
        $this->assertResponseCode(403);
    }

    public function testHttpFailure(): void
    {
        $request = new ServerRequest('GET', '/unavailable');
        $this->runRequest($request);

        $this->assertResponseFailure();
        $this->assertResponseCode(503);
    }

    public function testHttpHeaders(): void
    {
        $request = new ServerRequest('GET', '/headers');
        $this->runRequest($request);

        $this->assertHeader('X-Custom-Header', 'foobar');
        $this->assertHeaderContains('X-Custom-Header', 'foo');
        $this->assertHeaderNotContains('X-Custom-Header', 'baz');
    }

    public function testHttpContentType(): void
    {
        $request = new ServerRequest('GET', '/content-type');
        $this->runRequest($request);

        $this->assertContentType('text/html');
    }

    public function testHttpResponseWithoutBody(): void
    {
        $request = new ServerRequest('GET', '/without-body');
        $this->runRequest($request);

        $this->assertResponseCode(204);
        $this->assertResponseEmpty();
        $this->assertResponseEquals('');
    }

    public function testHttpResponseWithBody(): void
    {
        $request = new ServerRequest('GET', '/with-body');
        $this->runRequest($request);

        $this->assertResponseCode(200);
        $this->assertResponseNotEmpty();
        $this->assertResponseEquals('foobar');
        $this->assertResponseNotEquals('barbaz');
        $this->assertResponseContains('bar');
        $this->assertResponseNotContains('baz');
        $this->assertResponseRegExp('/^foo/');
        $this->assertResponseNotRegExp('/^bar/');
    }

    /**
     * tests failure messages for assertions
     *
     * @param string $assertion Assertion method
     * @param string $message Expected failure message
     * @param string $url URL to test
     * @param mixed ...$rest
     *
     * @dataProvider assertionFailureMessagesProvider
     */
    /*
    // TODO : code à finir de coder pour tester les messages d'erreur des différents Constraint qui ont été codées pour PHPunit !!!!
    public function testAssertionFailureMessages($assertion, $message, $url, ...$rest): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        Security::setSalt($this->key);

        $this->get($url);

        call_user_func_array([$this, $assertion], $rest);
    }*/

    /**
     * data provider for assertion failure messages
     *
     * @return array<string, string, string, mixed>
     */
    /*
    public function assertionFailureMessagesProvider(): array
    {
        //$templateDir = TEST_APP . 'templates' . DS;

        return [
            'assertContentType'              => ['assertContentType', 'Failed asserting that \'test\' is set as the Content-Type (`text/html`).', '/posts/index', 'test'],
            'assertContentTypeVerbose'       => ['assertContentType', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'test'],
            //'assertCookie' => ['assertCookie', 'Failed asserting that \'test\' is in cookie \'remember_me\'.', '/posts/index', 'test', 'remember_me'],
            //'assertCookieVerbose' => ['assertCookie', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'test', 'remember_me'],
            //'assertCookieEncrypted' => ['assertCookieEncrypted', 'Failed asserting that \'test\' is encrypted in cookie \'secrets\'.', '/posts/secretCookie', 'test', 'secrets'],
            //'assertCookieEncryptedVerbose' => ['assertCookieEncrypted', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'test', 'NameOfCookie'],
            //'assertCookieNotSet' => ['assertCookieNotSet', 'Failed asserting that \'remember_me\' cookie is not set.', '/posts/index', 'remember_me'],
            //'assertFileResponse' => ['assertFileResponse', 'Failed asserting that \'test\' file was sent.', '/posts/file', 'test'],
           // 'assertFileResponseVerbose' => ['assertFileResponse', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'test'],
            'assertHeader'                   => ['assertHeader', 'Failed asserting that \'test\' equals content in header \'X-Cake\' (`custom header`).', '/posts/header', 'X-Cake', 'test'],
            'assertHeaderContains'           => ['assertHeaderContains', 'Failed asserting that \'test\' is in header \'X-Cake\' (`custom header`)', '/posts/header', 'X-Cake', 'test'],
            'assertHeaderNotContains'        => ['assertHeaderNotContains', 'Failed asserting that \'custom header\' is not in header \'X-Cake\' (`custom header`)', '/posts/header', 'X-Cake', 'custom header'],
            'assertHeaderContainsVerbose'    => ['assertHeaderContains', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'X-Cake', 'test'],
            'assertHeaderNotContainsVerbose' => ['assertHeaderNotContains', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'X-Cake', 'test'],
            //'assertLayout' => ['assertLayout', 'Failed asserting that \'custom_layout\' equals layout file `' . $templateDir . 'layout' . DS . 'default.php`.', '/posts/index', 'custom_layout'],
            //'assertLayoutVerbose' => ['assertLayout', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'custom_layout'],
            //'assertRedirect' => ['assertRedirect', 'Failed asserting that \'http://localhost/\' equals content in header \'Location\' (`http://localhost/posts`).', '/posts/flashNoRender', '/'],
            //'assertRedirectVerbose' => ['assertRedirect', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', '/'],
            //'assertRedirectContains' => ['assertRedirectContains', 'Failed asserting that \'/posts/somewhere-else\' is in header \'Location\' (`http://localhost/posts`).', '/posts/flashNoRender', '/posts/somewhere-else'],
            //'assertRedirectContainsVerbose' => ['assertRedirectContains', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', '/posts/somewhere-else'],
            //'assertRedirectNotContainsVerbose' => ['assertRedirectNotContains', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', '/posts/somewhere-else'],
            'assertResponseCode'             => ['assertResponseCode', 'Failed asserting that `302` matches response status code `200`.', '/posts/index', 302],
            'assertResponseContains'         => ['assertResponseContains', 'Failed asserting that \'test\' is in response body.', '/posts/index', 'test'],
            'assertResponseEmpty'            => ['assertResponseEmpty', 'Failed asserting that response body is empty.', '/posts/index'],
            'assertResponseEquals'           => ['assertResponseEquals', 'Failed asserting that \'test\' matches response body.', '/posts/index', 'test'],
            'assertResponseEqualsVerbose'    => ['assertResponseEquals', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'test'],
            'assertResponseError'            => ['assertResponseError', 'Failed asserting that 200 is between 400 and 429.', '/posts/index'],
            'assertResponseFailure'          => ['assertResponseFailure', 'Failed asserting that 200 is between 500 and 505.', '/posts/index'],
            'assertResponseNotContains'      => ['assertResponseNotContains', 'Failed asserting that \'index\' is not in response body.', '/posts/index', 'index'],
            'assertResponseNotEmpty'         => ['assertResponseNotEmpty', 'Failed asserting that response body is not empty.', '/posts/empty_response'],
            'assertResponseNotEquals'        => ['assertResponseNotEquals', 'Failed asserting that \'posts index\' does not match response body.', '/posts/index/error', 'posts index'],
            'assertResponseNotRegExp'        => ['assertResponseNotRegExp', 'Failed asserting that `/index/` PCRE pattern not found in response body.', '/posts/index/error', '/index/'],
            'assertResponseNotRegExpVerbose' => ['assertResponseNotRegExp', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', '/index/'],
            'assertResponseOk'               => ['assertResponseOk', 'Failed asserting that 404 is between 200 and 204.', '/posts/missing', '/index/'],
            'assertResponseRegExp'           => ['assertResponseRegExp', 'Failed asserting that `/test/` PCRE pattern found in response body.', '/posts/index/error', '/test/'],
            'assertResponseSuccess'          => ['assertResponseSuccess', 'Failed asserting that 404 is between 200 and 308.', '/posts/missing'],
            'assertResponseSuccessVerbose'   => ['assertResponseSuccess', 'Possibly related to Cake\Controller\Exception\MissingActionException: "Action PostsController::missing() could not be found, or is not accessible."', '/posts/missing'],

            //'assertSession' => ['assertSession', 'Failed asserting that \'test\' is in session path \'Missing.path\'.', '/posts/index', 'test', 'Missing.path'],
            //'assertSessionHasKey' => ['assertSessionHasKey', 'Failed asserting that \'Missing.path\' is a path present in the session.', '/posts/index', 'Missing.path'],
            //'assertSessionNotHasKey' => ['assertSessionNotHasKey', 'Failed asserting that \'Flash.flash\' is not a path present in the session.', '/posts/index', 'Flash.flash'],

            //'assertTemplate' => ['assertTemplate', 'Failed asserting that \'custom_template\' equals template file `' . $templateDir . 'Posts' . DS . 'index.php`.', '/posts/index', 'custom_template'],
            //'assertTemplateVerbose' => ['assertTemplate', 'Possibly related to Cake\Routing\Exception\MissingRouteException: "A route matching "/notfound" could not be found."', '/notfound', 'custom_template'],
            //'assertFlashMessage' => ['assertFlashMessage', 'Failed asserting that \'missing\' is in \'flash\' message.', '/posts/index', 'missing'],
            //'assertFlashMessageWithKey' => ['assertFlashMessage', 'Failed asserting that \'missing\' is in \'auth\' message.', '/posts/index', 'missing', 'auth'],
            //'assertFlashMessageAt' => ['assertFlashMessageAt', 'Failed asserting that \'missing\' is in \'flash\' message #0.', '/posts/index', 0, 'missing'],
            //'assertFlashMessageAtWithKey' => ['assertFlashMessageAt', 'Failed asserting that \'missing\' is in \'auth\' message #0.', '/posts/index', 0, 'missing', 'auth'],
            //'assertFlashElement' => ['assertFlashElement', 'Failed asserting that \'missing\' is in \'flash\' element.', '/posts/index', 'missing'],
            //'assertFlashElementWithKey' => ['assertFlashElement', 'Failed asserting that \'missing\' is in \'auth\' element.', '/posts/index', 'missing', 'auth'],
            //'assertFlashElementAt' => ['assertFlashElementAt', 'Failed asserting that \'missing\' is in \'flash\' element #0.', '/posts/index', 0, 'missing'],
            //'assertFlashElementAtWithKey' => ['assertFlashElementAt', 'Failed asserting that \'missing\' is in \'auth\' element #0.', '/posts/index', 0, 'missing', 'auth'],
        ];
    }*/

    /**
     * Prepare the Http engine to handle the request.
     */
    protected function http(): Http
    {
        $container = new Container();

        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);
        $factory = new Psr17Factory();

        $container->singleton(EventDispatcherInterface::class, $dispatcher);
        $container->singleton(ResponseFactoryInterface::class, $factory);

        $http = new Http($container, $dispatcher);
        $http->setHandler(new HttpHandler($factory));

        return $http;
    }
}
