<?php
declare(strict_types=1);

namespace Tests;

use DG\BypassFinals;

use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class TestCase extends MockeryTestCase
{
    private const BYPASS_FINALS_CACHE_DIR = __DIR__ . '/../.bypass-finals-cache/';

    protected function setUp(): void
    {
        parent::setUp();

        $this->bypassFinals();
    }

    private function bypassFinals(): void
    {
        if (!file_exists(self::BYPASS_FINALS_CACHE_DIR)) {
            mkdir(self::BYPASS_FINALS_CACHE_DIR, 0755);
        }

        BypassFinals::denyPaths(['*/vendor/*']);

        BypassFinals::setCacheDirectory(self::BYPASS_FINALS_CACHE_DIR);

        BypassFinals::enable(
            bypassReadOnly: false,
            bypassFinal: true,
        );
    }
}
