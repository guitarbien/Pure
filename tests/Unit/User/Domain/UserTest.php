<?php

declare(strict_types=1);

namespace Tests\Unit\User\Domain;

use App\User\Domain\User;
use App\User\Domain\UserWasLoggedIn;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class UserTest extends TestCase
{
    // --- register() ---

    public function test_register_returns_user_instance(): void
    {
        $user = User::register('user@example.com', 'secret');
        $this->assertInstanceOf(User::class, $user);
    }

    public function test_register_stores_email(): void
    {
        $user = User::register('user@example.com', 'secret');
        $this->assertSame('user@example.com', $user->getEmail());
    }

    public function test_register_hashes_password(): void
    {
        $user = User::register('user@example.com', 'plaintext');
        $this->assertNotSame('plaintext', $user->getPasswordHash());
        $this->assertTrue(password_verify('plaintext', $user->getPasswordHash()));
    }

    public function test_register_assigns_uuid_id(): void
    {
        $user = User::register('user@example.com', 'pass');
        $this->assertInstanceOf(UuidInterface::class, $user->getId());
    }

    public function test_register_assigns_unique_ids(): void
    {
        $a = User::register('a@example.com', 'pass');
        $b = User::register('b@example.com', 'pass');
        $this->assertFalse($a->getId()->equals($b->getId()));
    }

    public function test_register_sets_zero_failed_login_attempts(): void
    {
        $user = User::register('user@example.com', 'pass');
        $this->assertSame(0, $user->getFailedLoginAttempts());
    }

    public function test_register_sets_no_last_failed_login_attempt(): void
    {
        $user = User::register('user@example.com', 'pass');
        $this->assertNull($user->getLastFailedLoginAttempt());
    }

    public function test_register_sets_creation_date_to_now(): void
    {
        $before = new DateTimeImmutable();
        $user = User::register('user@example.com', 'pass');
        $after = new DateTimeImmutable();

        $this->assertGreaterThanOrEqual($before->getTimestamp(), $user->getCreationDate()->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $user->getCreationDate()->getTimestamp());
    }

    public function test_register_starts_with_no_recorded_events(): void
    {
        $user = User::register('user@example.com', 'pass');
        $this->assertEmpty($user->getRecordedEvents());
    }

    // --- logIn() with correct password ---

    public function test_login_with_correct_password_records_UserWasLoggedIn_event(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('correct');

        $events = $user->getRecordedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserWasLoggedIn::class, $events[0]);
    }

    public function test_login_with_correct_password_resets_failed_attempts(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('wrong');   // increment once
        $user->logIn('correct'); // should reset

        $this->assertSame(0, $user->getFailedLoginAttempts());
    }

    public function test_login_with_correct_password_clears_last_failed_attempt(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('wrong');   // sets lastFailedLoginAttempt
        $user->logIn('correct'); // should clear it

        $this->assertNull($user->getLastFailedLoginAttempt());
    }

    // --- logIn() with wrong password ---

    public function test_login_with_wrong_password_does_not_record_event(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('wrong');

        $this->assertEmpty($user->getRecordedEvents());
    }

    public function test_login_with_wrong_password_increments_failed_attempts(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('wrong');
        $user->logIn('wrong');

        $this->assertSame(2, $user->getFailedLoginAttempts());
    }

    public function test_login_with_wrong_password_sets_last_failed_attempt_timestamp(): void
    {
        $before = new DateTimeImmutable();
        $user = User::register('user@example.com', 'correct');
        $user->logIn('wrong');
        $after = new DateTimeImmutable();

        $last = $user->getLastFailedLoginAttempt();
        $this->assertNotNull($last);
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $last->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $last->getTimestamp());
    }

    // --- clearRecordedEvents() ---

    public function test_clearRecordedEvents_empties_event_list(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('correct');
        $this->assertNotEmpty($user->getRecordedEvents());

        $user->clearRecordedEvents();
        $this->assertEmpty($user->getRecordedEvents());
    }

    // --- 大雄式邊界條件 ---

    public function test_login_with_emoji_password_fails_gracefully(): void
    {
        $user = User::register('user@example.com', 'correct');
        $user->logIn('🔥🔑💀'); // 奇怪的密碼輸入，不應該丟 exception

        $this->assertSame(1, $user->getFailedLoginAttempts());
    }

    public function test_register_with_unicode_email_is_stored_as_is(): void
    {
        $email = '用戶@範例.com';
        $user = User::register($email, 'pass');
        $this->assertSame($email, $user->getEmail());
    }

    public function test_register_with_xss_attempt_in_email_is_stored_as_is(): void
    {
        $email = '<script>alert(1)</script>@evil.com';
        $user = User::register($email, 'pass');
        // domain 不做 escape，那是 Presentation 的責任
        $this->assertSame($email, $user->getEmail());
    }
}
