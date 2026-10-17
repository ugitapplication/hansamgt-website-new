<p>You have received a new website enquiry:</p>
<ul>
    <li><strong>Name:</strong> {{ $submission->name }}</li>
    <li><strong>Email:</strong> {{ $submission->email }}</li>
    <li><strong>Phone:</strong> {{ $submission->phone }}</li>
</ul>
<p><strong>Message:</strong></p>
<p>{{ $submission->message }}</p>
