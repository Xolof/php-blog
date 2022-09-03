<?php declare(strict_types=1);

namespace PhpBlog\Xolof;

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testReturnsHelloWorld(): void
    {
        $this->assertEquals(
            "hello world",
            Router::get()
        );
    }
}

