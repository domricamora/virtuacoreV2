<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\LeadReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class LeadMailTest extends TestCase
{
    private function payload(array $over = []): array
    {
        return array_merge([
            'name' => 'Dana Whitfield', 'email' => 'dana@example.com',
            'intent' => 'consult', 'interest' => 'sales-development',
            'message' => 'Two days a week of inbox and pipeline work.',
        ], $over);
    }

    public function test_an_enquiry_notifies_both_addresses(): void
    {
        Mail::fake();
        $this->post(route('lead.store'), $this->payload())->assertRedirect();

        Mail::assertSent(LeadReceived::class, function ($mail) {
            return $mail->hasTo('admin@virtuacore.net') && $mail->hasTo('info@virtuacore.net');
        });
    }

    public function test_reply_goes_to_the_enquirer_not_the_site(): void
    {
        // Hitting reply must reach the person who wrote in. Replying to the site address
        // is a silent dead end that looks like it worked.
        Mail::fake();
        $this->post(route('lead.store'), $this->payload());

        Mail::assertSent(LeadReceived::class, fn ($m) => $m->hasReplyTo('dana@example.com'));
    }

    public function test_the_subject_carries_the_useful_facts(): void
    {
        Mail::fake();
        $this->post(route('lead.store'), $this->payload());

        Mail::assertSent(LeadReceived::class, function ($m) {
            $s = $m->envelope()->subject;
            return str_contains($s, 'Dana Whitfield') && str_contains($s, 'sales-development');
        });
    }

    public function test_a_broken_mail_transport_never_breaks_the_visitor(): void
    {
        // Mail is the part most likely to fail on shared hosting. Point the mailer at a
        // transport that cannot work and assert the visitor still gets a success response:
        // the enquiry is already on disk by then, and they did nothing wrong.
        config(['mail.default' => 'smtp', 'mail.mailers.smtp' => [
            'transport' => 'smtp', 'host' => '127.0.0.1', 'port' => 1, 'timeout' => 1,
        ]]);

        $this->post(route('lead.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('lead_status');
    }

    public function test_an_enquiry_with_no_recipients_configured_still_succeeds(): void
    {
        // Misconfiguration must degrade to "nobody was notified", never to a 500 that
        // loses the enquiry and tells the visitor their message failed.
        Mail::fake();
        config(['site.lead_recipients' => []]);

        $this->post(route('lead.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('lead_status');

        Mail::assertNothingSent();
    }

    public function test_the_honeypot_submission_sends_nothing(): void
    {
        Mail::fake();
        $this->post(route('lead.store'), $this->payload(['vc_hp' => 'bot']))
            ->assertSessionHasErrors('vc_hp');

        Mail::assertNothingSent();
    }
}
