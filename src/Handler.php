<?php
declare(strict_types=1);

namespace Wrong;

interface Handler
{
    public function run(Context $context): void;
}
