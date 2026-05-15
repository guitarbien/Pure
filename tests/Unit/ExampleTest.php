<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_php_version_meets_requirement(): void
    {
        $this->assertTrue(version_compare(PHP_VERSION, '8.4.0', '>='));
    }
}
