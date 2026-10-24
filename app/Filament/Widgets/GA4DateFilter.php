<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;

class GA4DateFilter extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static ?string $pollingInterval = null;

    protected string $view = 'filament.widgets.ga4-date-filter';

    public ?string $range = null;

    public function mount(): void
    {
        $this->form->fill([
            'range' => Session::get('ga4_date_range', '30d'),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('range')
                    ->label('Analytics Period')
                    ->options([
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        '7d' => 'Last 7 Days',
                        '30d' => 'Last 30 Days',
                        'this_month' => 'This Month',
                        'last_month' => 'Last Month',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        Session::put('ga4_date_range', $state);
                        $this->dispatch('refresh-ga4-stats');
                    }),
            ]);
    }

    protected function getHeading(): ?string
    {
        return 'GA4 Date Filter';
    }
}
