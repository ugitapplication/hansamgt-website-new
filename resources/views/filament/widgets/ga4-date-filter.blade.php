<x-filament-widgets::widget>
    <x-filament::section>
        <form wire:submit.prevent="submit">
            {{ $this->form }}
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
