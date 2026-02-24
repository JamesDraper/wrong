<?php
declare(strict_types=1);

namespace Tests\Unit;

use Wrong\Context;

use Tests\TestCase;

use PHPUnit\Framework\Attributes\Test;

final class ContextTest extends TestCase
{
    private Context $context;

    #[Test]
    public function it_sets_and_gets_keys(): void
    {
        $this->context->set('one', 'two');

        $this->assertSame('two', $this->context->get('one'));
    }

    #[Test]
    public function it_can_get_and_set_multiple_keys(): void
    {
        $this
            ->context
            ->set('one', 'two')
            ->set('three', 'four');

        $this->assertSame('two', $this->context->get('one'));
        $this->assertSame('four', $this->context->get('three'));
    }

    #[Test]
    public function set_returns_this(): void
    {
        $context = $this->context->set('one', 'two');

        $this->assertSame($this->context, $context);
    }

    #[Test]
    public function get_returns_null_if_key_not_set(): void
    {
        $this->assertNull($this->context->get('one'));
    }

    #[Test]
    public function it_unsets_keys(): void
    {
        $this->context->set('one', 'two');

        $this->context->unset('one');

        $this->assertNull($this->context->get('one'));
    }

    #[Test]
    public function it_unsets_non_existent_key(): void
    {
        $this->context->unset('non_existent_key');

        $this->assertNull($this->context->get('non_existent_key'));
    }

    #[Test]
    public function unset_returns_this(): void
    {
        $context = $this->context->unset('one');

        $this->assertSame($this->context, $context);
    }

    #[Test]
    public function it_can_be_converted_to_array(): void
    {
        $this
            ->context
            ->set('one', 'two')
            ->set('three', 'four');

        $this->assertSame([
            'one' => 'two',
            'three' => 'four',
        ], $this->context->toArray());
    }

    #[Test]
    public function to_array_returns_copy(): void
    {
        $this->context->set('one', 'two');

        $array = $this->context->toArray();

        $array['one'] = 'three';

        $this->assertSame('two', $this->context->get('one'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = new Context;
    }
}
