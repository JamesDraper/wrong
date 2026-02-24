<?php
declare(strict_types=1);

namespace Wrong;

use Wrong\Context;

use Psr\Http\Message\ServerRequestInterface as Request;

interface RequestContextFactory
{
    public function makeContextFromRequest(Request $request): Context;
}
