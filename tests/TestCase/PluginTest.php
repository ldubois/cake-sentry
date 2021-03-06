<?php

namespace Connehito\CakeSentry\Test\TestCase;

use Cake\Error\Middleware\ErrorHandlerMiddleware as CakeErrorHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\TestSuite\TestCase;
use Connehito\CakeSentry\Error\Middleware\ErrorHandlerMiddleware;
use Connehito\CakeSentry\Plugin;

final class PluginTest extends TestCase
{
    /**
     * Test middleware hook to set SentryErrorHandlerMiddleware.
     *
     * @return void
     */
    public function testMiddleware()
    {
        $middleware = new MiddlewareQueue();
        (new Plugin())->middleware($middleware);

        $middleware->seek(0);
        $this->assertInstanceOf(
            ErrorHandlerMiddleware::class,
            $middleware->current(),
            'Middleware is not inserted to queue'
        );
    }

    /**
     * Test middleware hook to set SentryErrorHandlerMiddleware inner.
     *
     * @return void
     */
    public function testMiddlewareToSetInner()
    {
        $middleware = new MiddlewareQueue([
            new class {},
            new class {},
            new class {},
        ]);
        (new Plugin())->middleware($middleware);

        $middleware->seek($middleware->count() - 1);
        $this->assertInstanceOf(
            ErrorHandlerMiddleware::class,
            $middleware->current(),
            'Middleware is not inserted to queue last'
        );
    }

    /**
     * Test middleware hook to set SentryErrorHandlerMiddleware inner of ErrorHandler.
     *
     * @return void
     */
    public function testMiddlewareToSetInnerOfErrorHandler()
    {
        $middleware = new MiddlewareQueue([
            new class {},
            new class {},
            new CakeErrorHandlerMiddleware(),
            new class {},
            new class {},
        ]);
        (new Plugin())->middleware($middleware);

        $middleware->seek(3);
        $this->assertInstanceOf(
            ErrorHandlerMiddleware::class,
            $middleware->current(),
            'Middleware is not inserted to queue after default error handler'
        );
    }
}
