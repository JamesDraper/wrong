<?php
declare(strict_types=1);

namespace Wrong;

use Wrong\Context;

use Psr\Http\Message\ResponseInterface as Response;

interface ContextResponseFactory
{
    public function makeResponseFromContext(Context $context): Response;
}
