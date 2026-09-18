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

    public function test_each_page_uses_the_clip_assigned_to_it(): void
    {
        // Footage is per page now, chosen to match what the page is about. A page showing
        // the wrong clip is not a crash -- it just quietly misrepresents the section, so
        // the mapping is asserted rather than trusted.
        $expected = [
            'home'         => 'hero',
            'services'     => 'services',
            'how-it-works' => 'how-it-works',
            'pricing'      => 'pricing',
            'about'        => 'about',
            'contact'      => 'contact',
            'for-authors'  => 'authors',
        ];

        foreach ($expected as $route => $clip) {
            $html = $this->get(route($route))->assertOk()->getContent();

            preg_match_all('~data-src="[^"]*/([a-z0-9-]+)-(?:720|1080)\.mp4"~', $html, $m);
            $used = array_values(array_unique($m[1]));

            $this->assertSame([$clip], $used, "{$route} should use the '{$clip}' clip");
        }
    }

    public function test_every_referenced_clip_and_poster_actually_exists(): void
    {
        // A missing file renders a <video> that silently never plays, leaving the poster
        // up forever -- which looks exactly like the loading gate working correctly.
        foreach (self::marketingPages() as [$name]) {
            $html = $this->get(route($name))->assertOk()->getContent();

            preg_match_all('~(?:data-src|poster)="[^"]*/([^"/]+\.(?:mp4|webp))"~', $html, $m);
            $this->assertNotEmpty($m[1], "{$name} references no hero media");

            foreach (array_unique($m[1]) as $file) {
                $dir  = str_ends_with($file, '.mp4') ? 'video' : 'images';
                $path = public_path($dir.'/'.$file);
                $this->assertFileExists($path, "{$name} references missing media: {$file}");
            }
        }
    }
}
