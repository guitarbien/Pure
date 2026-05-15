<?php

declare(strict_types=1);

namespace Tests\Unit\Submission\Domain;

use App\Submission\Domain\AuthorId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AuthorIdTest extends TestCase
{
    public function test_fromUuid_creates_author_id_from_uuid(): void
    {
        $uuid = Uuid::uuid4();
        $authorId = AuthorId::fromUuid($uuid);

        $this->assertInstanceOf(AuthorId::class, $authorId);
    }

    public function test_toString_returns_uuid_string(): void
    {
        $uuid = Uuid::uuid4();
        $authorId = AuthorId::fromUuid($uuid);

        $this->assertSame($uuid->toString(), $authorId->toString());
    }

    public function test_different_uuids_produce_different_author_ids(): void
    {
        $uuidA = Uuid::uuid4();
        $uuidB = Uuid::uuid4();

        $authorIdA = AuthorId::fromUuid($uuidA);
        $authorIdB = AuthorId::fromUuid($uuidB);

        $this->assertNotSame($authorIdA->toString(), $authorIdB->toString());
    }
}
