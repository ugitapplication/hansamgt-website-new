{{-- HANSA in Numbers (legacy: Counter.php) — managed under Homepage Management →
     Homepage Sections. The list below is the fallback so the band still renders
     before the table is seeded. The count-up animation derives its
     target/prefix/suffix from the number itself, so an editor only ever types
     the figure as it should read. --}}
@php
    $statistics = ($statistics ?? []);

    if (empty($statistics)) {
        $yearsOperation = max(1, (int) date('Y') - 2015);

        $statistics = [
            ['value' => 'No 1', 'label' => 'Integrated Hospitality & Facility Management Company'],
            ['value' => '1000+', 'label' => 'Employees'],
            ['value' => $yearsOperation.'+', 'label' => 'Years in operation'],
            ['value' => '120+', 'label' => 'Happy Customers'],
            ['value' => '24/7', 'label' => 'One-Stop Service'],
        ];
    }
@endphp
<section id="ServiceExcellence" class="home-band"
         style="background-image: url('{{ asset('images/home/bg/counter.jpg') }}');"
         aria-labelledby="numbers-heading">
    <div class="home-band__shade" aria-hidden="true"></div>
    <div class="home-band__inner">
        <h2 id="numbers-heading" class="home-heading home-heading--light"><mark>HANSA</mark><br>in Numbers</h2>
        <dl class="mt-8 grid grid-cols-2 gap-6 text-white md:grid-cols-3 lg:grid-cols-5">
            @foreach ($statistics as $statistic)
                @php
                    $display = (string) ($statistic['value'] ?? '');
                    $parts = [];

                    // Split "No 1" / "1000+" / "24/7" into prefix, number and
                    // suffix so the count-up can animate the figure alone.
                    if ($display !== '' && preg_match('/^(.*?)(\d+)(.*)$/s', $display, $parts) === 1) {
                        $countup = ' data-countup data-target="'.e($parts[2]).'"';

                        if ($parts[1] !== '') {
                            $countup .= ' data-prefix="'.e($parts[1]).'"';
                        }

                        if ($parts[3] !== '') {
                            $countup .= ' data-suffix="'.e($parts[3]).'"';
                        }
                    } else {
                        $countup = '';
                    }
                @endphp
                <div class="flex flex-col">
                    <dt class="order-2 mt-1 block text-sm">{{ $statistic['label'] }}</dt>
                    <dd class="order-1 text-4xl font-bold md:text-5xl"{!! $countup !!}>{{ $display }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
