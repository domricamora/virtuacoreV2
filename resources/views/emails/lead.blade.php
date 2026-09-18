New enquiry from the website.

Name:      {{ $lead['name'] }}
Email:     {{ $lead['email'] }}
@if (!empty($lead['phone']))
Phone:     {{ $lead['phone'] }}
@endif
@if (!empty($lead['company']))
Company:   {{ $lead['company'] }}
@endif
Interest:  {{ $lead['interest'] ?? 'not specified' }}
Intent:    {{ $lead['intent'] ?? 'contact' }}
Page:      {{ $lead['page'] ?? '/' }}
Received:  {{ $lead['at'] ?? now()->toIso8601String() }}

@if (!empty($lead['message']))
Message
-------
{{ $lead['message'] }}
@else
(No message was written.)
@endif

--
Reply directly to this email to reach {{ $lead['name'] }}.
A copy of this enquiry is also in storage/logs/leads.log.
