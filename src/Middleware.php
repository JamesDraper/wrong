<?php
declare(strict_types=1);

namespace Wrong;

interface Middleware
{
    /**
     * @param callable(Context): void $next
     */
    public function run(Context $context, callable $next): void;
}
