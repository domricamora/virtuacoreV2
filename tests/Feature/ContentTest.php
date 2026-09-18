<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\Content;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Pest was the plan, but pest-plugin-laravel v5 is the first release supporting Laravel 13
 * and it requires PHP 8.4, while production runs 8.3.33. Testing on a different PHP minor
 * than you deploy to is a worse trade than losing the syntax sugar, so this is PHPUnit.
 *
 * Extends the framework TestCase rather than PHPUnit's: Content::get() resolves paths
 * through resource_path(), which needs a booted container.
 */
final class ContentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Content::flush();
    }

    public static function sets(): array
    {
        return [
            'services'        => ['services', 5],
            'author services' => ['author-services', 6],
            'process'         => ['process', 4],
            'objections'      => ['objections', 4],
        ];
    }

    #[DataProvider('sets')]
    public function test_loads_every_content_set(string $set, int $expected): void
    {
        $this->assertCount($expected, Content::get($set));
    }

    public function test_refuses_a_set_name_that_could_escape_the_content_directory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Content::get('../../../config/database');
    }

    public function test_every_service_has_the_keys_templates_rely_on(): void
    {
        foreach (Content::allServices() as $slug => $svc) {
            foreach (['nav', 'name', 'h1', 'deck', 'title', 'meta', 'icon', 'faqs'] as $key) {
                $this->assertArrayHasKey($key, $svc, "'{$slug}' is missing '{$key}'");
            }
            $this->assertNotEmpty(
                $svc['faqs'],
                "'{$slug}' has no FAQs, so its FAQPage schema would render empty"
            );
        }
    }

    public function test_the_two_service_lines_stay_disjoint(): void
    {
        // allServices() merges with +, which silently keeps the left operand on a key
        // clash. A slug present in both lines would vanish from one without any error.
        $clash = array_intersect_key(Content::services(), Content::authorServices());
        $this->assertSame([], $clash, 'slug in both lines: '.implode(', ', array_keys($clash)));
    }

    public function test_looks_up_one_service_and_returns_null_for_an_unknown_slug(): void
    {
        $this->assertIsArray(Content::service('sales-development'));
        $this->assertNull(Content::service('no-such-service'));
    }

    public function test_faqs_are_question_answer_pairs(): void
    {
        foreach (Content::allServices() as $slug => $svc) {
            foreach ($svc['faqs'] as $i => $faq) {
                $this->assertCount(2, $faq, "{$slug} FAQ #{$i} is not a [question, answer] pair");
                $this->assertNotEmpty(trim((string) $faq[0]), "{$slug} FAQ #{$i} has an empty question");
                $this->assertNotEmpty(trim((string) $faq[1]), "{$slug} FAQ #{$i} has an empty answer");
            }
        }
    }
}
