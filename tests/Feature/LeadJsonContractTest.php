<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The contract between the form's JavaScript and the endpoint.
 *
 * This broke in production in a way that looked like a network fault: the client sent
 * X-Requested-With: 'fetch', which Laravel's expectsJson() does not recognise, so the
 * server answered 302 to an HTML page, res.json() threw, and the visitor was told
 * "We could not reach the server."
 */
final class LeadJsonContractTest extends TestCase
{
    /** Exactly the headers resources/js/main.js sends. */
    private const HEADERS = [
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ];

    private function payload(array $over = []): array
    {
        return array_merge([
            'name' => 'Rosalind Adeyemi', 'email' => 'rosalind@example.com',
            'intent' => 'contact',
        ], $over);
    }

    public function test_a_valid_submission_answers_json_not_a_redirect(): void
    {
        Mail::fake();

        $this->postJson(route('lead.store'), $this->payload(), self::HEADERS)
            ->assertOk()
            ->assertJson(['ok' => true])
            ->assertJsonStructure(['ok', 'message']);
    }

    public function test_a_validation_failure_names_the_field_the_form_can_highlight(): void
    {
        // The client marks one input invalid and prints one message beside it, so it needs
        // {field, error} -- Laravel's default {message, errors} renders nothing.
        $this->postJson(route('lead.store'), $this->payload(['email' => 'not-an-email']), self::HEADERS)
            ->assertStatus(422)
            ->assertJson(['ok' => false, 'field' => 'email'])
            ->assertJsonStructure(['ok', 'field', 'error']);
    }

    public function test_a_missing_name_is_reported_against_the_name_field(): void
    {
        $this->postJson(route('lead.store'), $this->payload(['name' => '']), self::HEADERS)
            ->assertStatus(422)
            ->assertJson(['ok' => false, 'field' => 'name']);
    }

    public function test_the_javascript_sends_headers_laravel_recognises(): void
    {
        // Guards the exact regression: 'fetch' is not a value expectsJson() knows.
        $js = file_get_contents(resource_path('js/main.js'));

        $this->assertStringContainsString("'Accept': 'application/json'", $js);
        $this->assertStringContainsString("'X-Requested-With': 'XMLHttpRequest'", $js);
        $this->assertStringNotContainsString("'X-Requested-With': 'fetch'", $js);
    }

    public function test_a_non_json_submission_still_works_without_javascript(): void
    {
        // The form has a real action and method, so it must work with JS disabled.
        Mail::fake();

        $this->post(route('lead.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('lead_status');
    }
}
