<?php
declare(strict_types=1);

namespace Tests;

use Wrong\Middleware;
use Wrong\Pipeline;
use Wrong\Context;
use Wrong\Handler;

use PHPUnit\Framework\Attributes\Test;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery;

use ArrayObject;

final class PipelineTest extends MockeryTestCase
{
    #[Test]
    public function it_executes_middlewares_and_handler_in_order(): void
    {
        /**
         * @var ArrayObject<int, string> $trace
         */
        $trace = new ArrayObject;

        $context = Mockery::mock(Context::class);

        $context
            ->allows('get')
            ->with('trace')
            ->andReturn($trace);

        $middlewareA = new class implements Middleware {
            public function run(Context $context, callable $next): void
            {
                /**
                 * @var ArrayObject<int, string> $trace
                 */
                $trace = $context->get('trace');

                $trace->append('middleware-1-before');

                $next($context);

                $trace->append('middleware-1-after');
            }
        };

        $middlewareB = new class implements Middleware {
            public function run(Context $context, callable $next): void
            {
                /**
                 * @var ArrayObject<int, string> $trace
                 */
                $trace = $context->get('trace');

                $trace->append('middleware-2-before');

                $next($context);

                $trace->append('middleware-2-after');
            }
        };

        $handler = new class implements Handler {
            public function run(Context $context): void
            {
                /**
                 * @var ArrayObject<int, string> $trace
                 */
                $trace = $context->get('trace');

                $trace->append('handler');
            }
        };

        $pipeline = new Pipeline($handler, $middlewareA, $middlewareB);

        $pipeline->run($context);

        $this->assertSame([
            'middleware-1-before',
            'middleware-2-before',
            'handler',
            'middleware-2-after',
            'middleware-1-after',
        ], $trace->getArrayCopy());
    }

    #[Test]
    public function middleware_can_short_circuit(): void
    {
        /**
         * @var ArrayObject<int, string> $trace
         */
        $trace = new ArrayObject;

        $context = Mockery::mock(Context::class);

        $context
            ->allows('get')
            ->with('trace')
            ->andReturn($trace);

        $middleware = new class implements Middleware {
            public function run(Context $context, callable $next): void
            {
                /**
                 * @var ArrayObject<int, string> $trace
                 */
                $trace = $context->get('trace');

                $trace->append('middleware');
            }
        };

        $handler = new class implements Handler {
            public function run(Context $context): void
            {
                /**
                 * @var ArrayObject<int, string> $trace
                 */
                $trace = $context->get('trace');

                $trace->append('handler');
            }
        };

        $pipeline = new Pipeline($handler, $middleware);

        $pipeline->run($context);

        $this->assertSame(['middleware'], $trace->getArrayCopy());
    }
}
