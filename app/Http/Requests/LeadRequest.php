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
            'email'    => ['required', 'email:rfc,dns', 'max:190'],
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
            'email.email'    => 'That address does not look deliverable — please check it.',
            'vc_hp.prohibited' => 'That submission looked automated.',
        ];
    }
}
