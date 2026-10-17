<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Support\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle contact form submissions from the homepage and contact page.
     *
     * The submission is always stored in the database first, so no enquiry
     * is lost even if outgoing mail is misconfigured. Emails are
     * best-effort: failures are logged, never shown to the visitor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:190'],
                'phone' => ['required', 'string', 'max:30'],
                'message' => ['required', 'string', 'max:5000'],
            ],
            [
                'name.required' => 'Please enter your full name.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Please enter your phone number.',
                'message.required' => 'Please enter your message.',
            ]
        );

        $submission = ContactSubmission::query()->create($validated);

        // The recipient is a setting (Settings → Site Settings), so enquiries can
        // be routed without a code change. The mail transport and credentials
        // stay in .env.
        $recipient = Settings::get('enquiry_recipient') ?: (string) config('mail.from.address');

        try {
            Mail::send('emails.contact-admin', ['submission' => $submission], function ($mail) use ($submission, $recipient) {
                $mail->to($recipient)
                    ->replyTo($submission->email, $submission->name)
                    ->subject('Website enquiry from '.$submission->name);
            });

            Mail::send('emails.contact-confirmation', ['submission' => $submission], function ($mail) use ($submission) {
                $mail->to($submission->email, $submission->name)
                    ->subject('Thanks for contacting HANSA Management Ltd.');
            });
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()
            ->back()
            ->with('contact_success', 'Your query has been successfully submitted. We will get back to you shortly.');
    }
}
