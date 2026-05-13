<?php

declare(strict_types=1);

namespace Tests\Unit\FrontPage\Infrastructure;

use App\FrontPage\Infrastructure\MockSubmissionQuery;
use PHPUnit\Framework\TestCase;

class MockSubmissionQueryTest extends TestCase
{
    public function test_execute_returns_default_submissions(): void
    {
        $query = new MockSubmissionQuery();
        $submissions = $query->execute();

        $this->assertCount(3, $submissions);
    }

    public function test_execute_returns_duckduckgo(): void
    {
        $query = new MockSubmissionQuery();
        $submissions = $query->execute();

        $this->assertSame('https://duckduckgo.com', $submissions[0]->getUrl());
        $this->assertSame('DuckDuckGo', $submissions[0]->getTitle());
    }

    public function test_execute_returns_google(): void
    {
        $query = new MockSubmissionQuery();
        $submissions = $query->execute();

        $this->assertSame('https://google.com', $submissions[1]->getUrl());
        $this->assertSame('Google', $submissions[1]->getTitle());
    }

    public function test_execute_returns_bing(): void
    {
        $query = new MockSubmissionQuery();
        $submissions = $query->execute();

        $this->assertSame('https://bing.com', $submissions[2]->getUrl());
        $this->assertSame('Bing', $submissions[2]->getTitle());
    }
}
