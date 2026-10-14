@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <h1 class="page-h1 page-h1--center">Site Management Gallery</h1>
        @php
            $photos = [
                ['img' => 'team.jpg', 'caption' => 'Top Management'],
                ['img' => 'IMG_9572.JPG', 'caption' => 'Training Session'],
                ['img' => 'IMG_3555.JPG', 'caption' => 'Training Session'],
                ['img' => 'IMG_9849.JPG', 'caption' => 'Team Work'],
                ['img' => 'IMG_9320.JPG', 'caption' => 'Hospitality'],
                ['img' => 'IMG_9712.JPG', 'caption' => 'Meetup With Management'],
                ['img' => 'IMG_9402.JPG', 'caption' => 'Temperature Measurement'],
                ['img' => 'IMG_9440.JPG', 'caption' => 'Security First'],
                ['img' => 'IMG_9446.JPG', 'caption' => 'Hospitality'],
                ['img' => 'IMG_9455.JPG', 'caption' => 'Customer Service'],
                ['img' => 'IMG_9477.JPG', 'caption' => 'Customer Satisfaction'],
                ['img' => 'IMG_9498.JPG', 'caption' => 'Customer Reception'],
            ];
        @endphp
        <div class="gal-grid">
            @foreach ($photos as $photo)
                <figure class="gal-item">
                    <button type="button" data-gal-open="{{ asset('images/pages/gallery/' . $photo['img']) }}" data-gal-cap="{{ $photo['caption'] }}" aria-haspopup="dialog" aria-label="View larger: {{ $photo['caption'] }}">
                        <img src="{{ asset('images/pages/gallery/' . $photo['img']) }}" alt="{{ $photo['caption'] }}"
                             width="350" height="250" loading="lazy" decoding="async">
                    </button>
                    <figcaption>{{ $photo['caption'] }}</figcaption>
                </figure>
            @endforeach
        </div>
        <dialog id="gal-dialog" class="team-dialog" aria-label="Gallery image viewer">
            <div class="team-dialog__panel">
            <div class="team-dialog__head">
                <h2 id="gal-dialog-cap"></h2>
                <button type="button" class="team-dialog__close" data-dialog-close aria-label="Close">×</button>
            </div>
            <div class="team-dialog__body">
                <img id="gal-dialog-img" src="" alt="">
            </div>
            </div>
        </dialog>
    </div>
    @include('pages.home.sections._slogan')
@endsection

@push('scripts')
<script>
(function () {
    var dlg = document.getElementById('gal-dialog');
    var img = document.getElementById('gal-dialog-img');
    var cap = document.getElementById('gal-dialog-cap');
    document.querySelectorAll('[data-gal-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            img.src = btn.getAttribute('data-gal-open');
            img.alt = btn.getAttribute('data-gal-cap');
            cap.textContent = btn.getAttribute('data-gal-cap');
            if (dlg && typeof dlg.showModal === 'function') dlg.showModal();
        });
    });
    dlg.querySelector('[data-dialog-close]').addEventListener('click', function () { dlg.close(); });
    dlg.addEventListener('click', function (event) { if (event.target === dlg) dlg.close(); });
})();
</script>
@endpush
