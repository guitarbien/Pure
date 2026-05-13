<?php

declare(strict_types=1);

namespace Tests\Unit\FrontPage\Application;

use App\FrontPage\Application\Submission;
use PHPUnit\Framework\TestCase;

class SubmissionTest extends TestCase
{
    public function test_constructor_stores_url(): void
    {
        $submission = new Submission('https://example.com', 'Example', 'John');

        $this->assertSame('https://example.com', $submission->getUrl());
    }

    public function test_constructor_stores_title(): void
    {
        $submission = new Submission('https://example.com', 'My Title', 'John');

        $this->assertSame('My Title', $submission->getTitle());
    }

    public function test_constructor_stores_author(): void
    {
        $submission = new Submission('https://example.com', 'Title', 'Alice');

        $this->assertSame('Alice', $submission->getAuthor());
    }
}
