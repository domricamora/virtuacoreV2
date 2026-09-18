<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/**
 * Lead capture. Every form on the site posts here.
 *
 * Leads are written to a dedicated log channel as JSON lines. That is deliberately the
 * durable path rather than a nicety: the source build emails nothing, so enquiries have
 * been accumulating unread. Until SMTP is configured, the log IS the inbox, and it is
 * written before any notification is attempted so a mail failure cannot lose the lead.
 */
final class LeadController extends Controller
{
    public function store(LeadRequest $request): RedirectResponse|JsonResponse
    {
        $lead = $request->safe()->except('vc_hp');

        $lead['ip_hash'] = hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));
        $lead['at']      = now()->toIso8601String();

        Log::channel('leads')->info('lead', $lead);

        // TODO(owner): send the notification mail once SMTP credentials exist. The lead is
        // already durable at this point, so adding mail cannot introduce a loss path.

        $message = 'Thank you. We will reply within one business day.';

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'message' => $message])
            : back()->with('lead_status', $message);
    }
}
