<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Content;
use Tests\TestCase;

/**
 * Every page renders real content, not chrome around an empty middle.
 *
 * A byte floor is a blunt check, but it catches the failure that status codes miss: a
 * template whose loop silently yields nothing still returns 200 with a correct <title>,
 * and the source build shipped exactly that bug -- eleven service pages rendering their
 * 404 branch with identical byte counts, all reporting PASS.
 */
final class PagesTest extends TestCase
{
    public function test_no_page_renders_as_a_stub(): void
    {
        foreach (['home','services','for-authors','how-it-works','pricing','about','contact','privacy','terms'] as $name) {
            $html = $this->get(route($name))->assertOk()->getContent();
            $this->assertStringNotContainsString('still being ported', $html, "{$name} is still a stub");
        }
    }

    public function test_every_page_has_substance_beyond_the_chrome(): void
    {
        // Nav + footer + head alone come to roughly 16 KB. Anything near that floor means
        // the page body produced nothing.
        foreach (['services','for-authors','how-it-works','pricing','about','contact','privacy','terms'] as $name) {
            $size = strlen($this->get(route($name))->assertOk()->getContent());
            $this->assertGreaterThan(19000, $size, "{$name} rendered only {$size} bytes -- body is likely empty");
        }
    }

    public function test_service_detail_pages_render_their_own_content(): void
    {
        foreach (Content::services() as $slug => $svc) {
            $html = $this->get(route('services.show', $slug))->assertOk()->getContent();

            $this->assertStringContainsString(e($svc['h1']), $html, "{$slug} lost its h1");
            $this->assertStringContainsString(e($svc['answer']), $html, "{$slug} lost its answer block");

            foreach ($svc['tools'] as $tool) {
                $this->assertStringContainsString(e($tool), $html, "{$slug} lost tool {$tool}");
            }
            foreach (array_keys($svc['roles']) as $role) {
                $this->assertStringContainsString(e($role), $html, "{$slug} lost role {$role}");
            }
        }
    }

    public function test_author_pages_render_only_the_blocks_their_data_declares(): void
    {
        // Shapes differ: most have `includes`, social-media-kits has `tiers`,
        // film-submissions adds `requires`. A block must not appear without its data.
        foreach (Content::authorServices() as $slug => $svc) {
            $html = $this->get(route('authors.show', $slug))->assertOk()->getContent();

            $this->assertSame(
                isset($svc['includes']),
                str_contains($html, 'What you get'),
                "{$slug}: 'What you get' heading does not match presence of `includes`"
            );
            $this->assertSame(
                isset($svc['tiers']),
                str_contains($html, 'Two levels'),
                "{$slug}: 'Two levels' heading does not match presence of `tiers`"
            );
            $this->assertSame(
                isset($svc['requires']),
                str_contains($html, 'What we need from you first'),
                "{$slug}: 'requires' heading does not match presence of `requires`"
            );
        }
    }

    public function test_pricing_publishes_no_rate_it_cannot_stand_behind(): void
    {
        $html = $this->get(route('pricing'))->assertOk()->getContent();

        foreach (Content::pricing()['models'] as $m) {
            $this->assertNull($m['rate'], "{$m['name']} has a rate -- confirm it is publishable before enabling");
        }
        $this->assertStringContainsString('Quoted after scoping', $html);
        $this->assertDoesNotMatchRegularExpression('/[$£€]\s?\d/', $html, 'a currency figure appeared on pricing');
    }

    public function test_the_author_line_states_what_it_does_not_guarantee(): void
    {
        // The most defensible content on that page. It must not be quietly dropped.
        $html = $this->get(route('for-authors'))->assertOk()->getContent();
        $this->assertStringContainsString('What none of this guarantees', $html);
        $this->assertStringContainsString('they do not control', $html);
    }

    public function test_the_privacy_policy_matches_what_the_site_actually_collects(): void
    {
        // A policy describing analytics that are switched off is as wrong as one omitting
        // collection that happens.
        $html = $this->get(route('privacy'))->assertOk()->getContent();

        if (config('site.features.analytics')) {
            $this->assertStringContainsString('Do Not Track', $html);
        } else {
            $this->assertStringNotContainsString('we record a pageview', $html);
            $this->assertStringContainsString('we do not record anything about your visit', $html);
        }
    }
}
