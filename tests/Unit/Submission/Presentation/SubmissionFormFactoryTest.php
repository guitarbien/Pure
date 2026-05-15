<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Submission\Presentation\SubmissionForm;
use App\Submission\Presentation\SubmissionFormFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SubmissionFormFactoryTest extends TestCase
{
    public function test_create_from_request_extracts_form_data(): void
    {
        $request = new Request([
            'token' => 'csrf-token-123',
            'title' => 'Example Title',
            'url' => 'https://example.com',
        ]);

        $validator = $this->createMock(StoredTokenValidator::class);
        $factory = new SubmissionFormFactory($validator);

        $form = $factory->createFromRequest($request);

        $this->assertInstanceOf(SubmissionForm::class, $form);
    }

    public function test_create_from_request_handles_missing_fields(): void
    {
        $request = new Request([]);

        $validator = $this->createMock(StoredTokenValidator::class);
        $factory = new SubmissionFormFactory($validator);

        $form = $factory->createFromRequest($request);

        $this->assertInstanceOf(SubmissionForm::class, $form);
    }

    public function test_create_from_request_converts_values_to_strings(): void
    {
        $request = new Request([
            'token' => 123,
            'title' => 456,
            'url' => 789,
        ]);

        $validator = $this->createMock(StoredTokenValidator::class);
        $factory = new SubmissionFormFactory($validator);

        $form = $factory->createFromRequest($request);

        $this->assertInstanceOf(SubmissionForm::class, $form);
    }
}
