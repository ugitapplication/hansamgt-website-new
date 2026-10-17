{{-- Cookie / analytics consent banner.
     Rendered hidden and only un-hidden by resources/js/consent.js when there is
     no remembered decision. With JavaScript unavailable the banner stays hidden
     and no analytics script is ever requested, so the default is still "no
     consent, no processing". --}}
@if (\App\Support\Consent::analyticsEnabled())
    <div class="consent"
         id="consent-banner"
         role="region"
         aria-labelledby="consent-title"
         data-consent-key="{{ config('analytics.consent_storage_key') }}"
         data-consent-version="{{ config('analytics.consent_version') }}"
         hidden>
        <div class="consent__inner">
            <div class="consent__text">
                <h2 class="consent__title" id="consent-title" tabindex="-1">Cookies and analytics</h2>
                <p>
                    We would like to use Google Analytics to understand how this website is used.
                    It sets cookies and sends usage data to Google. Nothing is loaded until you
                    accept, and you can change your mind at any time.
                </p>
            </div>
            <div class="consent__actions">
                <button type="button" class="consent__btn consent__btn--accept" data-consent-accept>
                    Accept analytics
                </button>
                <button type="button" class="consent__btn consent__btn--decline" data-consent-decline>
                    Decline
                </button>
                <a class="consent__link" href="{{ url('privacy-policy') }}#cookies">
                    Read about cookies
                </a>
            </div>
        </div>
    </div>
@endif
