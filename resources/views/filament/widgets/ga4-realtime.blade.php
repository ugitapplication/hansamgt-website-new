<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="text-sm font-medium text-gray-500">Current Active Users (Last 30 Mins)</div>
            <div class="text-3xl font-bold text-primary-600">
                {{ $this->getRealtimeData()['active_users'] ?? '0' }}
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
