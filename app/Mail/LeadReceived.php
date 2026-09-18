<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Enquiry notification.
 *
 * Plain text, not HTML. This is an internal working message that gets read on a phone
 * between other things, and it is far more useful to be able to copy the address and the
 * message out of it than to have it styled.
 */
final class LeadReceived extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array<string, mixed> $lead */
    public function __construct(public array $lead) {}

    public function envelope(): Envelope
    {
        $who  = $this->lead['name'] ?? 'Someone';
        $what = $this->lead['interest'] ?? null;

        // The subject carries the useful facts, because that is all you see in a list.
        $subject = $what
            ? "Enquiry: {$who} — {$what}"
            : "Enquiry: {$who}";

        return new Envelope(
            subject: $subject,
            // Reply goes straight to the enquirer rather than to the site address, so
            // hitting reply is the correct action instead of a mistake.
            replyTo: [new Address($this->lead['email'], $who)],
        );
    }

    public function content(): Content
    {
        return new Content(text: 'emails.lead');
    }
}
