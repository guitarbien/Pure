<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\RoleBasedAccessControl\AuthenticatedUser;
use App\Submission\Presentation\SubmissionForm;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SubmissionFormTest extends TestCase
{
    private $tokenValidator;

    protected function setUp(): void
    {
        $this->tokenValidator = $this->createMock(StoredTokenValidator::class);
    }

    public function test_get_validation_errors_returns_empty_when_valid(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);

        $form = new SubmissionForm($this->tokenValidator, 'valid-token', 'Title', 'https://example.com');
        $errors = $form->getValidationErrors();

        $this->assertEmpty($errors);
    }

    public function test_get_validation_errors_reports_invalid_token(): void
    {
        $this->tokenValidator->method('validate')->willReturn(false);

        $form = new SubmissionForm($this->tokenValidator, 'bad-token', 'Title', 'https://example.com');
        $errors = $form->getValidationErrors();

        $this->assertContains('Invalid token', $errors);
    }

    public function test_get_validation_errors_reports_empty_title(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);

        $form = new SubmissionForm($this->tokenValidator, 'token', '', 'https://example.com');
        $errors = $form->getValidationErrors();

        $this->assertContains('Title must be between 1 and 200 characters', $errors);
    }

    public function test_get_validation_errors_reports_title_too_long(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $longTitle = str_repeat('a', 201);

        $form = new SubmissionForm($this->tokenValidator, 'token', $longTitle, 'https://example.com');
        $errors = $form->getValidationErrors();

        $this->assertContains('Title must be between 1 and 200 characters', $errors);
    }

    public function test_get_validation_errors_reports_empty_url(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);

        $form = new SubmissionForm($this->tokenValidator, 'token', 'Title', '');
        $errors = $form->getValidationErrors();

        $this->assertContains('URL must be between 1 and 200 characters', $errors);
    }

    public function test_get_validation_errors_reports_url_too_long(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $longUrl = 'https://' . str_repeat('a', 200);

        $form = new SubmissionForm($this->tokenValidator, 'token', 'Title', $longUrl);
        $errors = $form->getValidationErrors();

        $this->assertContains('URL must be between 1 and 200 characters', $errors);
    }

    public function test_get_validation_errors_reports_multiple_errors(): void
    {
        $this->tokenValidator->method('validate')->willReturn(false);

        $form = new SubmissionForm($this->tokenValidator, 'bad', '', '');
        $errors = $form->getValidationErrors();

        $this->assertCount(3, $errors);
    }

    public function test_to_command_creates_submit_link(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $author = $this->createMock(AuthenticatedUser::class);
        $authorId = Uuid::uuid4();
        $author->method('getId')->willReturn($authorId);

        $form = new SubmissionForm($this->tokenValidator, 'token', 'Test Title', 'https://test.com');
        $command = $form->toCommand($author);

        $this->assertSame('Test Title', $command->getTitle());
        $this->assertSame('https://test.com', $command->getUrl());
        $this->assertSame($authorId->toString(), $command->getAuthorId()->toString());
    }
}
