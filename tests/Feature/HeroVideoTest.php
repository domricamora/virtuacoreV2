<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class HeroVideoTest extends TestCase
{
    /** Marketing pages carry the hero. Legal pages deliberately do not. */
    public static function marketingPages(): array
    {
        return [
            'home'         => ['home'],
            'services'     => ['services'],
            'for authors'  => ['for-authors'],
            'how it works' => ['how-it-works'],
            'pricing'      => ['pricing'],
            'about'        => ['about'],
            'contact'      => ['contact'],
        ];
    }

    #[DataProvider('marketingPages')]
    public function test_marketing_page_has_a_gated_hero_video(string $name): void
    {
        $html = $this->get(route($name))->assertOk()->getContent();

        $this->assertStringContainsString('data-hero-video', $html, "{$name} has no hero video");
        $this->assertStringContainsString('vc-hero__scrim', $html, "{$name} has no scrim -- text over footage will fail contrast");
        $this->assertStringContainsString('data-hero-toggle', $html, "{$name} has no pause control (WCAG 2.2.2)");
        $this->assertStringContainsString('preload="none"', $html);
    }

    #[DataProvider('marketingPages')]
    public function test_no_hero_source_bypasses_the_loading_gate(string $name): void
    {
        // A plain src on a <source> makes every visitor fetch 1.9 MB regardless of motion
        // preference, Save-Data or connection. The sources must stay in data-src until
        // initHero() decides the session qualifies.
        $html = $this->get(route($name))->assertOk()->getContent();

        $this->assertDoesNotMatchRegularExpression(
            '~<source\s[^>]*(?<!-)\bsrc=~',
            $html,
            "{$name} has a hero <source> with a live src -- the loading gate is bypassed"
        );
        $this->assertStringContainsString('data-src=', $html);
    }

    public function test_legal_pages_carry_no_video(): void
    {
        // A background video on a privacy policy is weight with no purpose, and these are
        // the pages someone reads when they are already unsure about being tracked.
        foreach (['privacy', 'terms'] as $name) {
            $html = $this->get(route($name))->assertOk()->getContent();
            $this->assertStringNotContainsString('data-hero-video', $html, "{$name} should not have a hero video");
        }
    }

    public function test_one_clip_is_reused_rather_than_one_per_page(): void
    {
        // Reusing the encoded clip means the second page costs nothing from cache; a
        // per-page clip would cost another 1.9 MB each time. Assert the set of distinct
        // files across every page is exactly the two encodes that exist.
        $files = [];

        foreach (self::marketingPages() as [$name]) {
            $html = $this->get(route($name))->assertOk()->getContent();
            preg_match_all('~data-src="[^"]*/(hero-[^"/]+\.mp4)"~', $html, $m);

            $this->assertNotEmpty($m[1], "{$name} references no hero video file");
            $files = array_merge($files, $m[1]);
        }

        $distinct = array_values(array_unique($files));
        sort($distinct);

        $this->assertSame(
            ['hero-1080.mp4', 'hero-720.mp4'],
            $distinct,
            'more than one clip is in use: '.implode(', ', $distinct)
        );
    }
}
