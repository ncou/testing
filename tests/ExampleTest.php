<?php

declare(strict_types=1);

namespace Chiron\Test\Testing;

use Mockery\MockInterface;

class ExampleTest extends TestCase
{
    public function testGreet(): void
    {
        $this->assertEquals(true, true);
    }
}
