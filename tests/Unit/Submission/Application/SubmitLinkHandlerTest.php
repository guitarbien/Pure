<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Application;

use App\Submission\Application\SubmitLink;
use App\Submission\Application\SubmitLinkHandler;
use App\Submission\Domain\Submission;
use App\Submission\Domain\SubmissionRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SubmitLinkHandlerTest extends TestCase
{
    public function test_handle_creates_and_adds_submission(): void
    {
        $repo = $this->createMock(SubmissionRepository::class);
        $repo->expects($this->once())
            ->method('add')
            ->with($this->isInstanceOf(Submission::class));

        $handler = new SubmitLinkHandler($repo);
        $command = new SubmitLink(Uuid::uuid4(), 'https://example.com', 'My Link');

        $handler->handle($command);
    }

    public function test_handle_passes_correct_url_and_title(): void
    {
        $capturedSubmission = null;
        $repo = $this->createMock(SubmissionRepository::class);
        $repo->method('add')->willReturnCallback(function (Submission $s) use (&$capturedSubmission) {
            $capturedSubmission = $s;
        });

        $handler = new SubmitLinkHandler($repo);
        $authorId = Uuid::uuid4();
        $handler->handle(new SubmitLink($authorId, 'https://phpunit.de', 'PHPUnit'));

        $this->assertSame('https://phpunit.de', $capturedSubmission->getUrl());
        $this->assertSame('PHPUnit', $capturedSubmission->getTitle());
        $this->assertSame($authorId->toString(), $capturedSubmission->getAuthorId()->toString());
    }
}
