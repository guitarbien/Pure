<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\RoleBasedAccessControl\Role;

use App\Framework\RoleBasedAccessControl\Permission\SubmitLink;
use App\Framework\RoleBasedAccessControl\Role\Author;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function test_author_has_submit_link_permission(): void
    {
        $author = new Author();
        $this->assertTrue($author->hasPermission(new SubmitLink()));
    }

    public function test_author_does_not_have_unknown_permission(): void
    {
        $author = new Author();

        $unknownPermission = new class extends \App\Framework\RoleBasedAccessControl\Permission {};

        $this->assertFalse($author->hasPermission($unknownPermission));
    }
}
