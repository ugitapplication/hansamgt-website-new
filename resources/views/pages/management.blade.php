@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <h2 class="page-h1 page-h1--center">Management</h2>
        {{-- Managed under Content → Team. The built-in list below is the
             fallback so the page still renders before the table is seeded. --}}
        @php
            $members = ($members ?? []);

            if (empty($members)) {
                $members = [
                    [
                        'id' => 'chairman-message', 'src' => asset('images/pages/team/c-1.jpg'), 'alt' => 'Salina Ali, Chairperson',
                        'role' => 'Chairperson', 'name' => 'Salina Ali', 'modalName' => 'Ms. Salina Ali',
                        'teaser' => 'Ms. Salina Ali, a renowned business personality and Chairperson of HANSA Management Ltd. and Unique Group has been the key stakeholder behind the success of Unique Group over the past decades.',
                        'bio' => 'Ms. Salina Ali, a renowned business personality and Chairperson of HANSA Management Ltd. and Unique Group has been the key stakeholder behind the success of Unique Group over the past decades. Under her strong leadership and entrepreneurial spirit, the group become one of the leading companies in the country. Ms. Ali accomplished her Bachelor’s (honors) & Master’s degrees in Sociology from the University of Dhaka. She was born in an aristocratic Muslim family in Bangladesh. Apart from active involvement in the business management she is involved in many social and philanthropic activities. Ms. Ali has travelled extensively across the globe in pursuit of business.',
                    ],
                    [
                        'id' => 'md-message', 'src' => asset('images/pages/team/m-1.jpg'), 'alt' => 'Mohd. Noor Ali, Managing Director',
                        'role' => 'Managing Director', 'name' => 'Mohd. Noor Ali', 'modalName' => 'Mohd. Noor Ali',
                        'teaser' => 'Mr. Mohd Noor Ali is the Managing Director of HANSA Management Ltd. and the founder of Unique Group. He is one of the best-known entrepreneurs in Bangladesh and well recognized among the business community for his responsibility',
                        'bio' => 'Mr. Mohd Noor Ali is the Managing Director of HANSA Management Ltd. and the founder of Unique Group. He is one of the best-known entrepreneurs in Bangladesh and well recognized among the business community for his responsibility towards the company’s stable strategy. He aggressively broadened the company’s range of strategic business units, and wherever Unique Group has achieved a dominant position he vigorously contributed towards its growth. Mr. Ali has also been involved in diverse business entities and his most remarkable achievement is he is a self-made person. Today, Mr. Ali is the Managing Director of more than twenty different business concerns. Mr. Ali is a former President of BAIRA, Chairman of Eastern Bank Ltd, as well as Shareholder Directorship of different financial institutions and a number of business bodies. Being a freedom fighter and a philanthropist Mr. Ali’s contribution towards development of the country is truly significant.',
                    ],
                    [
                        'id' => 'ceo-message', 'src' => asset('images/pages/team/Sadhaq.jpeg'), 'alt' => 'Abu Sadhaq Alam, Chief Executive Officer',
                        'role' => 'Chief Executive Officer', 'name' => 'Mr. Abu Sadhaq Alam', 'modalName' => 'Mr. Abu Sadhaq Alam',
                        'teaser' => 'Mr. Abu Sadhaq Alam serves as the Chief Executive Officer of HANSA Management Limited',
                        'bio' => 'Mr. Abu Sadhaq Alam serves as the Chief Executive Officer of HANSA Management Limited, With a diversified career spanning 29 years, including 25 years at Standard Chartered Bank and 4 years in The City Bank, Sadhaq brings a wealth of expertise to enhance our service standards and drive long-term sustainable growth. Having worked across diverse cultural backgrounds in countries such as UAE, Indonesia, Singapore, Lebanon, Sri Lanka, Mauritius, Nepal, Vietnam and India, He has sharpened his skills in global business operations. He holds a Master\'s degree from Dhaka University and B. Com (Hons) from Aligarh Muslim University, India. Sadhaq\'s leadership and dedication will elevate HANSA Management to the new heights, ensuring service excellence along the way.',
                    ],
                    [
                        'id' => 'coo-message', 'src' => asset('images/pages/team/sahed.jpg'), 'alt' => 'Mohammad Shahedul Haque, Chief Operating Officer',
                        'role' => 'Chief Operating Officer', 'name' => 'Mohammad Shahedul Haque', 'modalName' => 'Mohammad Shahedul Haque',
                        'teaser' => 'Mohammad Shahedul Haque is the Chief Operating Officer of HANSA Management Limited.',
                        'bio' => 'More than 21 years of facility management experience providing outstanding management and operational leadership. Shahed was born and raised in the ancient city of Dhaka. He obtained his MBA in Marketing. He has extensive expertise in facility management, property management, hospitality, sales and customer service. Shahed has worked with well-known companies like Shanta Holdings Ltd., Rangs Properties Ltd., Asset Developments & Holdings Ltd., etc. Talented strategic and financial planner who is able to develop solutions to organizational challenges while maintaining financial control and achieving business goals. He is a member of the Rotary International, Lions Clubs International and Secretary of the Rotary Club of Dhaka Paragon (2020-2021). Shahed’s hobbies include music, travel, social media and helping those who are helpless.',
                    ],
                ];
            }
        @endphp
        <div class="team-grid">
            @foreach ($members as $member)
                <article class="team-card">
                    <div class="vc-hoverbox-wrapper vc-hoverbox-shape--square vc-hoverbox-align--center vc-hoverbox-direction--default vc-hoverbox-width--100" tabindex="0">
                        <div class="vc-hoverbox team-hoverbox">
                            <div class="vc-hoverbox-inner">
                                <div class="vc-hoverbox-block vc-hoverbox-front" style="background-image: url('{{ $member['src'] }}');" role="img" aria-label="{{ $member['alt'] }}"></div>
                                <div class="vc-hoverbox-block vc-hoverbox-back" style="background-color: #f2f2f2;">
                                    <div class="vc-hoverbox-block-inner vc-hoverbox-back-inner">
                                        <h2 class="team-back__role">{{ $member['role'] }}</h2>
                                        <p class="team-back__text">{{ $member['teaser'] }} <button type="button" class="team-back__more" data-dialog-open="{{ $member['id'] }}">read more</button></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="team-caption">
                        <h5>{{ $member['role'] }}</h5>
                        <p class="the_date">{{ $member['name'] }}</p>
                    </div>
                </article>
                <dialog id="{{ $member['id'] }}" class="team-dialog" aria-labelledby="{{ $member['id'] }}-title">
                    <div class="team-dialog__panel">
                    <div class="team-dialog__head">
                        <h2 id="{{ $member['id'] }}-title">{{ $member['modalName'] }}</h2>
                        <button type="button" class="team-dialog__close" data-dialog-close aria-label="Close">×</button>
                    </div>
                    <div class="team-dialog__body">
                        <img src="{{ $member['src'] }}" alt="{{ $member['alt'] }}"
                             width="400" height="400" loading="lazy" decoding="async">
                        <h3>{{ $member['role'] }}</h3>
                        <p>{{ $member['bio'] }}</p>
                    </div>
                    </div>
                </dialog>
            @endforeach
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-dialog-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var dlg = document.getElementById(btn.getAttribute('data-dialog-open'));
        if (dlg && typeof dlg.showModal === 'function') dlg.showModal();
    });
});
document.querySelectorAll('[data-dialog-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var dlg = btn.closest('dialog');
        if (dlg) dlg.close();
    });
});
document.querySelectorAll('dialog.team-dialog').forEach(function (dlg) {
    dlg.addEventListener('click', function (event) {
        if (event.target === dlg) dlg.close();
    });
});
</script>
@endpush
