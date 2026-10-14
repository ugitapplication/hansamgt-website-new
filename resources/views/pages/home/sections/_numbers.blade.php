{{-- HANSA in Numbers (legacy: Counter.php; countUp.js animation intentionally not ported) --}}
@php $yearsOperation = max(1, (int) date('Y') - 2015); @endphp
<section id="ServiceExcellence" class="home-band"
         style="background-image: url('{{ asset('images/home/bg/counter.jpg') }}');"
         aria-labelledby="numbers-heading">
    <div class="home-band__shade" aria-hidden="true"></div>
    <div class="home-band__inner">
        <h2 id="numbers-heading" class="home-heading home-heading--light"><mark>HANSA</mark><br>in Numbers</h2>
        <dl class="mt-8 grid grid-cols-2 gap-6 text-white md:grid-cols-3 lg:grid-cols-5">
            <div class="flex flex-col">
                <dt class="order-2 mt-1 block text-sm">Integrated Hospitality &amp; Facility Management Company</dt>
                <dd class="order-1 text-4xl font-bold md:text-5xl" data-countup data-target="1" data-prefix="No ">No 1</dd>
            </div>
            <div class="flex flex-col">
                <dt class="order-2 mt-1 block text-sm">Employees</dt>
                <dd class="order-1 text-4xl font-bold md:text-5xl" data-countup data-target="1000" data-suffix="+">1000+</dd>
            </div>
            <div class="flex flex-col">
                <dt class="order-2 mt-1 block text-sm">Years in operation</dt>
                <dd class="order-1 text-4xl font-bold md:text-5xl" data-countup data-target="{{ $yearsOperation }}" data-suffix="+">{{ $yearsOperation }}+</dd>
            </div>
            <div class="flex flex-col">
                <dt class="order-2 mt-1 block text-sm">Happy Customers</dt>
                <dd class="order-1 text-4xl font-bold md:text-5xl" data-countup data-target="120" data-suffix="+">120+</dd>
            </div>
            <div class="flex flex-col">
                <dt class="order-2 mt-1 block text-sm">One-Stop Service</dt>
                <dd class="order-1 text-4xl font-bold md:text-5xl" data-countup data-target="24" data-suffix="/7">24/7</dd>
            </div>
        </dl>
    </div>
</section>
