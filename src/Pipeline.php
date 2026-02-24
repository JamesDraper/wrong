<?php
declare(strict_types=1);

namespace Wrong;

use function array_reverse;
use function array_reduce;

class Pipeline
{
    /**
     * @var callable
     */
    private $pipeline;

    public function __construct(Handler $handler, Middleware ...$middlewares)
    {
        $this->pipeline = array_reduce(
            array_reverse($middlewares),
            function (callable $next, Middleware $middleware): callable {
                return $this->wrapMiddleware($middleware, $next);
            },
            $this->wrapHandler($handler),
        );
    }

    public function run(Context $context): void
    {
        ($this->pipeline)($context);
    }

    private function wrapMiddleware(Middleware $middleware, callable $next): callable
    {
        return function (Context $context) use ($middleware, $next): void {
            $middleware->run($context, $next);
        };
    }

    private function wrapHandler(Handler $handler): callable
    {
        return function (Context $context) use ($handler): void {
            $handler->run($context);
        };
    }
}
