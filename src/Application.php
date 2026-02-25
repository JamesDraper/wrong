<?php
declare(strict_types=1);

namespace Wrong;

use Wrong\ContextResponseFactory;
use Wrong\RequestContextFactory;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class Application
{
    public function __construct(
        private readonly RequestContextFactory $requestContextFactory,
        private readonly Pipeline $pipeline,
        private readonly ContextResponseFactory $contextResponseFactory,
    ) {
    }

    public function run(Request $request): Response
    {
        $context = $this
            ->requestContextFactory
            ->makeContextFromRequest($request);

        $this->pipeline->run($context);

        return $this
            ->contextResponseFactory
            ->makeResponseFromContext($context);
    }
}
