<?php

declare(strict_types=1);

namespace Tests\Unit\Framework\Rendering;

use App\Framework\Rendering\TemplateDirectory;
use PHPUnit\Framework\TestCase;

class TemplateDirectoryTest extends TestCase
{
    public function test_toString_appends_templates_to_root(): void
    {
        $dir = new TemplateDirectory('/var/www/app');
        $this->assertSame('/var/www/app/templates', $dir->toString());
    }

    public function test_toString_works_with_trailing_slash_root(): void
    {
        // 使用者可能多打一個 /，確認行為一致
        $dir = new TemplateDirectory('/var/www/app/');
        $this->assertSame('/var/www/app//templates', $dir->toString());
        // domain 不做 normalise，這是預期行為（呼叫端的責任）
    }

    public function test_toString_works_with_empty_root(): void
    {
        $dir = new TemplateDirectory('');
        $this->assertSame('/templates', $dir->toString());
    }
}
