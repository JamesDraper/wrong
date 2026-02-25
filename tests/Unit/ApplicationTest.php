<?php
declare(strict_types=1);

namespace Tests\Unit;

use Wrong\ContextResponseFactory;
use Wrong\RequestContextFactory;
use Wrong\Application;
use Wrong\Pipeline;
use Wrong\Context;

use Tests\TestCase;

use PHPUnit\Framework\Attributes\Test;

use Mockery;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ApplicationTest extends TestCase
{
    #[Test]
    public function it_works(): void
    {
        $context = Mockery::mock(Context::class);

        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(Response::class);

        $requestContextFactory = Mockery::mock(RequestContextFactory::class);
        $pipeline = Mockery::mock(Pipeline::class);
        $contextResponseFactory = Mockery::mock(ContextResponseFactory::class);

        $requestContextFactory
            ->shouldReceive('makeContextFromRequest')
            ->with($request)
            ->once()
            ->andReturn($context)
            ->globally()
            ->ordered();

        $pipeline
            ->shouldReceive('run')
            ->with($context)
            ->once()
            ->globally()
            ->ordered();

        $contextResponseFactory
            ->shouldReceive('makeResponseFromContext')
            ->with($context)
            ->once()
            ->andReturn($response)
            ->globally()
            ->ordered();

        $application = new Application(
            $requestContextFactory,
            $pipeline,
            $contextResponseFactory
        );

        $this->assertSame($response, $application->run($request));
    }
}
