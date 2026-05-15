<?php

declare(strict_types=1);

namespace Tests\Unit\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\User\Application\EmailTakenQuery;
use App\User\Presentation\RegisterUserForm;
use App\User\Presentation\RegisterUserFormFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RegisterUserFormFactoryTest extends TestCase
{
    public function test_create_form_request_extracts_form_data(): void
    {
        $request = new Request([
            'token' => 'csrf-token',
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $validator = $this->createMock(StoredTokenValidator::class);
        $emailTakenQuery = $this->createMock(EmailTakenQuery::class);
        $factory = new RegisterUserFormFactory($validator, $emailTakenQuery);

        $form = $factory->createFormRequest($request);

        $this->assertInstanceOf(RegisterUserForm::class, $form);
    }

    public function test_create_form_request_handles_missing_fields(): void
    {
        $request = new Request([]);

        $validator = $this->createMock(StoredTokenValidator::class);
        $emailTakenQuery = $this->createMock(EmailTakenQuery::class);
        $factory = new RegisterUserFormFactory($validator, $emailTakenQuery);

        $form = $factory->createFormRequest($request);

        $this->assertInstanceOf(RegisterUserForm::class, $form);
    }
}
