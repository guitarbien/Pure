<?php

declare(strict_types=1);

namespace Tests\Unit\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\User\Application\EmailTakenQuery;
use App\User\Presentation\RegisterUserForm;
use PHPUnit\Framework\TestCase;

class RegisterUserFormTest extends TestCase
{
    private $tokenValidator;
    private $emailTakenQuery;

    protected function setUp(): void
    {
        $this->tokenValidator = $this->createMock(StoredTokenValidator::class);
        $this->emailTakenQuery = $this->createMock(EmailTakenQuery::class);
    }

    public function test_get_validation_errors_returns_empty_when_valid(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', 'password123');
        $errors = $form->getValidationErrors();

        $this->assertEmpty($errors);
    }

    public function test_get_validation_errors_reports_invalid_token(): void
    {
        $this->tokenValidator->method('validate')->willReturn(false);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'bad-token', 'user@example.com', 'password123');
        $errors = $form->getValidationErrors();

        $this->assertContains('Invalid token', $errors);
    }

    public function test_get_validation_errors_reports_invalid_email(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'not-an-email', 'password123');
        $errors = $form->getValidationErrors();

        $this->assertContains('Incorrect email format', $errors);
    }

    public function test_get_validation_errors_reports_short_password(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', 'short');
        $errors = $form->getValidationErrors();

        $this->assertContains('Password must be at least 8 characters', $errors);
    }

    public function test_get_validation_errors_reports_email_already_taken(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->with('user@example.com')->willReturn(true);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', 'password123');
        $errors = $form->getValidationErrors();

        $this->assertContains('This email is already being used', $errors);
    }

    public function test_get_validation_errors_reports_multiple_errors(): void
    {
        $this->tokenValidator->method('validate')->willReturn(false);
        $this->emailTakenQuery->method('execute')->willReturn(true);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'bad', 'invalid-email', 'short');
        $errors = $form->getValidationErrors();

        $this->assertCount(4, $errors);
    }

    public function test_to_command_creates_register_user_command(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', 'password123');
        $command = $form->toCommand();

        $this->assertSame('user@example.com', $command->getEmail());
        $this->assertSame('password123', $command->getPassword());
    }

    // --- 邊界條件：空值 ---

    public function test_get_validation_errors_reports_empty_email(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', '', 'password123');
        $errors = $form->getValidationErrors();

        $this->assertContains('Incorrect email format', $errors);
    }

    public function test_get_validation_errors_reports_empty_password(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', '');
        $errors = $form->getValidationErrors();

        $this->assertContains('Password must be at least 8 characters', $errors);
    }

    // --- 邊界條件：超長輸入 ---

    public function test_get_validation_errors_accepts_very_long_email(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $longEmail = str_repeat('a', 60) . '@example.com';
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', $longEmail, 'password123');
        $errors = $form->getValidationErrors();

        // 應該接受（不應該因為長度而拒絕，除非有明確的長度限制）
        $this->assertEmpty($errors);
    }

    public function test_get_validation_errors_accepts_very_long_password(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $longPassword = str_repeat('a', 500);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', $longPassword);
        $errors = $form->getValidationErrors();

        $this->assertEmpty($errors);
    }

    // --- 邊界條件：恰好邊界值 ---

    public function test_get_validation_errors_accepts_exactly_8_character_password(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', '12345678');
        $errors = $form->getValidationErrors();

        $this->assertEmpty($errors);
    }

    public function test_get_validation_errors_rejects_7_character_password(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', '1234567');
        $errors = $form->getValidationErrors();

        $this->assertContains('Password must be at least 8 characters', $errors);
    }

    // --- 邊界條件：特殊字符 ---

    public function test_get_validation_errors_accepts_password_with_special_chars(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', 'p@ssw0rd!#$%');
        $errors = $form->getValidationErrors();

        $this->assertEmpty($errors);
    }

    public function test_get_validation_errors_accepts_password_with_emoji(): void
    {
        $this->tokenValidator->method('validate')->willReturn(true);
        $this->emailTakenQuery->method('execute')->willReturn(false);

        $form = new RegisterUserForm($this->tokenValidator, $this->emailTakenQuery, 'token', 'user@example.com', '🔥pass🔒🔑');
        $errors = $form->getValidationErrors();

        // 應該接受（密碼可以包含任何字符，長度檢查應該按字節或字符計算）
        $this->assertEmpty($errors);
    }
}
