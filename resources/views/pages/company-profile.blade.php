@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <h1 class="page-h1">Company Profile — HANSA Management Ltd.</h1>
                <div class="page-body">
                    <p>HANSA MANAGEMENT LTD. is a leading integrated Hospitality and Facility Management company founded by UNIQUE GROUP, which is one of the largest Bangladeshi conglomerates pioneered the first private owned Five-Star hotel “THE WESTIN DHAKA’’. Today, the span of UG operations has been diversified into more than twenty different business areas. These include Hospitality, Health Care, Educational Institution, Training &amp; Development, Human Resources Development &amp; Export, Real Estate, Construction &amp; Infrastructure Development, Telecommunication, Stock Brokerage House, Bank, Insurance, Financial Institution, Ready Mix Concrete, Ceramic Industry, Private Economic Zone, Agro, Energy &amp; Power, International Trade, Aviation, Media &amp; Publications and last but not the least the Facility Management Services.</p>
                    <p>HANSA Management has accumulated an extensive experience of offering our expertise to blue chip corporates, Government, SMEs and private residence complexes. Incorporated in 2015, we become one of the fastest growing companies in our domain.</p>
                    <p>With a dynamic and professional management team and a culture of innovation, learning, quality and deep service orientation; we could become the priority of choices for organizations who need to focus on their core business and outsource other non-core operations.</p>
                    <p>From specialized Security, Cleaning and Hygiene services, white-glove Housekeeping, MEP (Mechanical, Electrical &amp; Plumbing) Pest Control, Landscaping to Façade Cleaning; We have a bouquet of services that meet organizations’ asset and facilities management requirements.</p>
                    <p>We can meet the demand of the time that different corporates, business entrepreneurs, housing cities desire the comforts and services.</p>
                </div>
            </div>
            @include('pages.partials._sidebar-about', ['active' => 'company-profile'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
