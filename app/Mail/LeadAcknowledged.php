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
 * Acknowledgement to the person who wrote in.
 *
 * It exists to answer one question: did that go through? Without it the only feedback is
 * a line of text on a page they are about to navigate away from.
 *
 * It does NOT pretend to be a personal reply. It says what will happen and when, repeats
 * what they sent so they have a record, and stops. An auto-reply written to sound like a
 * human is obvious and makes the real reply feel late.
 */
final class LeadAcknowledged extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array<string, mixed> $lead */
    public function __construct(public array $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We have your enquiry — '.config('site.name'),
            // Replies come back to the team, not into a no-reply void.
            replyTo: [new Address(config('site.email'), config('site.name'))],
        );
    }

    public function content(): Content
    {
        return new Content(text: 'emails.lead-ack');
    }
}
