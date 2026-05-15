<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Application;

use App\Submission\Application\SubmitLink;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SubmitLinkTest extends TestCase
{
    public function test_constructor_stores_author_id(): void
    {
        $authorId = Uuid::uuid4();
        $command = new SubmitLink($authorId, 'https://example.com', 'Title');

        $this->assertSame($authorId->toString(), $command->getAuthorId()->toString());
    }

    public function test_constructor_stores_url(): void
    {
        $authorId = Uuid::uuid4();
        $command = new SubmitLink($authorId, 'https://example.com', 'Title');

        $this->assertSame('https://example.com', $command->getUrl());
    }

    public function test_constructor_stores_title(): void
    {
        $authorId = Uuid::uuid4();
        $command = new SubmitLink($authorId, 'https://example.com', 'My Title');

        $this->assertSame('My Title', $command->getTitle());
    }
}
