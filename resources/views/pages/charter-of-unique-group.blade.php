@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <h1 class="page-h1">Charter of Unique Group</h1>
                <div class="page-body">
                    <p>Unique Group, a business conglomerate in Bangladesh, began its operation in 1982. It started its journey by exporting human resources and then pioneered skill development. It has spearheaded luxury hotel industry in Bangladesh.</p>
                    <p>Over the years, the Group has diversified its business into many sectors including real estate and facilities management, tours and travels, education, financial institutions, ceramics, media, telecommunications, and agro based business. Today the Group is contributing to the national economy immensely.</p>
                    <p>We value our customers under all and any circumstances. We deliver quality products on time, ensuring secure and good return on investment with excellent service. The direction of our future is being set by our customers.</p>
                    <p>The purpose of Unique Group is to act boldly by pioneering new business directions and opportunities. We exist to provide world-class experience to our distinguished customers.</p>
                </div>
                <h2 class="page-h2 page-h2--dash">We Value</h2>
                <div class="company_history">
                    <ul>
                        <li>
                            <div class="year">Integrity</div>
                            <div class="sep" aria-hidden="true"></div>
                            <div class="company_history_text"><h3>We stand to honor our word</h3></div>
                        </li>
                        <li>
                            <div class="year">Innovation</div>
                            <div class="sep" aria-hidden="true"></div>
                            <div class="company_history_text"><h3>We are creative and we take on new challenges</h3></div>
                        </li>
                        <li>
                            <div class="year">Quality</div>
                            <div class="sep" aria-hidden="true"></div>
                            <div class="company_history_text"><h3>We are uncompromising on delivering customer satisfaction</h3></div>
                        </li>
                        <li>
                            <div class="year">Synergy</div>
                            <div class="sep" aria-hidden="true"></div>
                            <div class="company_history_text"><h3>We take ownership and work collectively</h3></div>
                        </li>
                        <li>
                            <div class="year">Excellence</div>
                            <div class="sep" aria-hidden="true"></div>
                            <div class="company_history_text"><h3>We pursue the journey of breaking barriers in producing extraordinary products and services</h3></div>
                        </li>
                        <li>
                            <div class="year">Leadership</div>
                            <div class="sep" aria-hidden="true"></div>
                            <div class="company_history_text"><h3>We take continuous initiatives for developing individuals and teams to their full potential</h3></div>
                        </li>
                    </ul>
                </div>
            </div>
            @include('pages.partials._sidebar-about', ['active' => 'charter-of-unique-group'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
