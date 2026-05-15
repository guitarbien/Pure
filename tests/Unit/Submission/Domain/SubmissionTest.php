<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Domain;

use App\Submission\Domain\AuthorId;
use App\Submission\Domain\Submission;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SubmissionTest extends TestCase
{
    public function test_submit_returns_submission_instance(): void
    {
        $authorId = Uuid::uuid4();
        $submission = Submission::submit($authorId, 'https://example.com', 'Example');

        $this->assertInstanceOf(Submission::class, $submission);
    }

    public function test_submit_stores_url(): void
    {
        $authorId = Uuid::uuid4();
        $submission = Submission::submit($authorId, 'https://example.com', 'Title');

        $this->assertSame('https://example.com', $submission->getUrl());
    }

    public function test_submit_stores_title(): void
    {
        $authorId = Uuid::uuid4();
        $submission = Submission::submit($authorId, 'https://example.com', 'My Title');

        $this->assertSame('My Title', $submission->getTitle());
    }

    public function test_submit_stores_author_id(): void
    {
        $authorId = Uuid::uuid4();
        $submission = Submission::submit($authorId, 'https://example.com', 'Title');

        $this->assertInstanceOf(AuthorId::class, $submission->getAuthorId());
        $this->assertSame($authorId->toString(), $submission->getAuthorId()->toString());
    }

    public function test_submit_assigns_unique_id(): void
    {
        $authorId = Uuid::uuid4();
        $a = Submission::submit($authorId, 'https://a.com', 'A');
        $b = Submission::submit($authorId, 'https://b.com', 'B');

        $this->assertFalse($a->getId()->equals($b->getId()));
    }

    public function test_submit_sets_creation_date_to_now(): void
    {
        $before = new \DateTimeImmutable();
        $submission = Submission::submit(Uuid::uuid4(), 'https://example.com', 'T');
        $after = new \DateTimeImmutable();

        $this->assertGreaterThanOrEqual($before->getTimestamp(), $submission->getCreationDate()->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $submission->getCreationDate()->getTimestamp());
    }

    #[DataProvider('blankStringProvider')]
    public function test_submit_allows_blank_url_and_title(string $url, string $title): void
    {
        // domain does NOT validate — validation is caller's responsibility
        $submission = Submission::submit(Uuid::uuid4(), $url, $title);
        $this->assertSame($url, $submission->getUrl());
        $this->assertSame($title, $submission->getTitle());
    }

    public static function blankStringProvider(): array
    {
        return [
            'empty url'    => ['', 'Title'],
            'empty title'  => ['https://example.com', ''],
            'both empty'   => ['', ''],
        ];
    }
}
