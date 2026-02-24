<?php
declare(strict_types=1);

namespace Wrong;

use Psr\Http\Message\ResponseInterface as Response;

interface ContextResponseFactory
{
    public function makeResponseFromContext(Context $context): Response;
}
