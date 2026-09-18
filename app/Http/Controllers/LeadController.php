<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Mail\LeadAcknowledged;
use App\Mail\LeadReceived;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Lead capture. Every form on the site posts here.
 *
 * Order matters and is deliberate: the lead is written to its own log channel BEFORE any
 * notification is attempted. Mail is the part most likely to fail on shared hosting, and
 * the failure mode has to be "nobody was emailed" rather than "the enquiry is gone".
 *
 * The visitor is never shown a mail failure either. They filled the form in correctly;
 * whether our mail transport worked is not their problem and not something they can act on.
 */
final class LeadController extends Controller
{
    public function store(LeadRequest $request): RedirectResponse|JsonResponse
    {
        $lead = $request->safe()->except('vc_hp');

        // Raw IPs are never stored. This is enough to tell two submissions apart without
        // being able to identify either sender.
        $lead['ip_hash'] = hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));
        $lead['at']      = now()->toIso8601String();

        Log::channel('leads')->info('lead', $lead);

        $this->notify($lead);

        $message = 'Thank you. We will reply within one business day.';

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'message' => $message])
            : back()->with('lead_status', $message);
    }

    /**
     * Send the notification, and swallow any transport failure.
     *
     * @param array<string, mixed> $lead
     */
    private function notify(array $lead): void
    {
        $recipients = array_filter((array) config('site.lead_recipients'));

        if ($recipients === []) {
            Log::warning('lead.notify: no recipients configured; enquiry is in the leads log only');

            return;
        }

        try {
            Mail::to($recipients)->send(new LeadReceived($lead));
        } catch (Throwable $e) {
            // Logged at error level so it is visible, but never re-thrown: the enquiry is
            // already safe on disk and the visitor has done nothing wrong.
            Log::error('lead.notify failed: '.$e->getMessage(), [
                'recipients' => $recipients,
                'lead_at'    => $lead['at'] ?? null,
            ]);
        }

        // Sent in its own try: the team being notified and the enquirer being
        // acknowledged are independent, and one failing must not suppress the other.
        try {
            Mail::to($lead['email'])->send(new LeadAcknowledged($lead));
        } catch (Throwable $e) {
            Log::error('lead.acknowledge failed: '.$e->getMessage(), [
                'lead_at' => $lead['at'] ?? null,
            ]);
        }
    }
}
