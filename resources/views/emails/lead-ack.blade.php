Hello {{ $lead['name'] }},

Thanks for getting in touch with {{ config('site.name') }}. Your enquiry has reached us
and someone will reply within one business day.

This is an automatic confirmation, not the reply itself.

What you sent us
----------------
@if (!empty($lead['interest']))
Interest:  {{ $lead['interest'] }}
@endif
@if (!empty($lead['company']))
Company:   {{ $lead['company'] }}
@endif
@if (!empty($lead['message']))

{{ $lead['message'] }}
@endif

If any of that is wrong, just reply to this email and tell us.

--
{{ config('site.name') }}
{{ config('site.address.street') }}, {{ config('site.address.locality') }}, {{ config('site.address.region') }} {{ config('site.address.postal') }}
{{ config('site.phone.display') }} · {{ config('site.email') }}
{{ config('site.hours.days') }}, {{ config('site.hours.open') }} to {{ config('site.hours.close') }} {{ config('site.hours.tz') }}
