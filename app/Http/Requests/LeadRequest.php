<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Content;
use Illuminate\Foundation\Http\FormRequest;

final class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:120'],
            // rfc, NOT rfc+dns. A dns check performs a live MX lookup on every submit:
            // it adds latency, and when the resolver is slow or unavailable on shared
            // hosting it REJECTS a genuine enquiry. On this site losing a lead is the
            // worst outcome available, so a questionable address is accepted and a human
            // judges it. Format typos are still caught.
            'email'    => ['required', 'email:rfc', 'max:190'],
            'company'  => ['nullable', 'string', 'max:160'],
            'phone'    => ['nullable', 'string', 'max:40'],
            // Interest must be a slug that exists. An arbitrary string here would be
            // written straight into the lead record and read back on the dashboard.
            'interest' => ['nullable', 'string', 'in:'.implode(',', array_keys(Content::allServices()))],
            'message'  => ['nullable', 'string', 'max:4000'],
            'intent'   => ['required', 'string', 'in:contact,quote,consult,author'],
            'page'     => ['nullable', 'string', 'max:190'],
            // Honeypot. Must be absent or empty; bots fill every field they find.
            'vc_hp'    => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Please tell us your name.',
            'email.required' => 'We need an email address to reply to.',
            'email.email'    => 'That does not look like an email address — please check it.',
            'vc_hp.prohibited' => 'That submission looked automated.',
        ];
    }
}
