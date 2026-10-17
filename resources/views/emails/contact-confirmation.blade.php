<p>Dear {{ $submission->name }},</p>
<p>We have received your message and would like to thank you for writing to us.@if (\App\Support\Settings::phoneDisplay()) If your inquiry is urgent, please call us at {{ \App\Support\Settings::phoneDisplay() }} to talk to one of our staff members.@endif Otherwise, we will reply by email as soon as possible.</p>
<p>Best regards,<br>{{ \App\Support\Settings::get('legal_name') }}@if (\App\Support\Settings::phoneDisplay())<br>{{ \App\Support\Settings::phoneDisplay() }}@endif<br>{{ \App\Support\Settings::get('email') }}</p>
