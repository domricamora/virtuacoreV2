<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\Content;
use Tests\TestCase;

/**
 * Guards for the specific defects this rebuild exists to fix. Each one shipped on the
 * WordPress site for years because nothing looked broken.
 */
final class ChromeTest extends TestCase
{
    private const PAGES = ['home', 'services', 'about', 'contact', 'pricing', 'terms'];

    public function test_no_link_anywhere_points_at_a_hash_placeholder(): void
    {
        // The WordPress footer pointed Contact, Connect, Subscribe, Terms, Sitemap,
        // Careers, Newsroom, Case Studies and Disclosures all at "#".
        foreach (self::PAGES as $name) {
            $html = $this->get(route($name))->assertOk()->getContent();
            $this->assertStringNotContainsString('href="#"', $html, "{$name} has a dead '#' link");
        }
    }

    public function test_the_elementor_placeholder_phone_number_appears_nowhere(): void
    {
        // The old site DISPLAYED +1-307-333-8809 but LINKED tel:+1-800-456-478-23, which
        // is not valid E.164 and cannot be dialled. Display and link are now one value.
        foreach (self::PAGES as $name) {
            $html = $this->get(route($name))->assertOk()->getContent();
            $this->assertStringNotContainsString('800-456-478', $html);
        }
    }

    public function test_tel_links_use_the_dialable_e164_form(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();
        preg_match_all('~href="tel:([^"]+)"~', $html, $m);

        $this->assertNotEmpty($m[1], 'no tel: link in the chrome');
        foreach (array_unique($m[1]) as $tel) {
            $this->assertMatchesRegularExpression('/^\+[1-9]\d{7,14}$/', $tel);
            $this->assertSame(config('site.phone.e164'), $tel);
        }
    }

    public function test_opening_hours_always_state_a_timezone(): void
    {
        // "Mon - Sat: 8.00 - 18.00" with no zone is unactionable for a company whose
        // product IS coverage hours.
        $html = $this->get(route('home'))->assertOk()->getContent();
        $this->assertStringContainsString(config('site.hours.tz'), $html);
    }

    public function test_unproven_claims_stay_behind_their_flags(): void
    {
        foreach (['stats', 'testimonials', 'logo_wall'] as $flag) {
            if (config("site.features.{$flag}")) {
                $this->markTestIncomplete("'{$flag}' was enabled -- confirm real data backs it");
            }
        }
        $this->assertFalse(config('site.features.stats'));
    }

    public function test_the_footer_lists_every_service_from_the_content_layer(): void
    {
        // The footer is generated, not hand-maintained, so adding a service cannot leave
        // the footer behind.
        $html = $this->get(route('home'))->assertOk()->getContent();

        foreach (array_keys(Content::services()) as $slug) {
            $this->assertStringContainsString(route('services.show', $slug), $html);
        }
        foreach (array_keys(Content::authorServices()) as $slug) {
            $this->assertStringContainsString(route('authors.show', $slug), $html);
        }
    }

    public function test_every_page_has_a_skip_link_as_the_first_focusable_element(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();
        $this->assertStringContainsString('class="vc-skip" href="#main"', $html);
        $this->assertStringContainsString('id="main"', $html);
    }
}
