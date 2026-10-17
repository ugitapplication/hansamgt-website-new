<?php

namespace App\Filament\Resources\ContactSubmissions;

use App\Filament\Resources\ContactSubmissions\Pages\ListContactSubmissions;
use App\Filament\Resources\ContactSubmissions\Pages\ViewContactSubmission;
use App\Models\ContactSubmission;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Enquiry')
                ->columns(2)
                ->components([
                    TextEntry::make('name'),
                    TextEntry::make('created_at')
                        ->label('Received')
                        ->dateTime(),
                    TextEntry::make('email')
                        ->copyable(),
                    TextEntry::make('phone')
                        ->copyable(),
                    TextEntry::make('message')
                        ->columnSpanFull(),
                    TextEntry::make('read_at')
                        ->label('Read at')
                        ->dateTime()
                        ->placeholder('Unread'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('message')
                    ->limit(50),
                TextColumn::make('read_at')
                    ->label('Read')
                    ->dateTime()
                    ->placeholder('Unread')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
                Action::make('markRead')
                    ->label('Mark as read')
                    ->icon('heroicon-o-check')
                    ->visible(fn (ContactSubmission $record): bool => $record->read_at === null)
                    ->action(fn (ContactSubmission $record): bool => $record->update(['read_at' => now()])),
                Action::make('markUnread')
                    ->label('Mark as unread')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->visible(fn (ContactSubmission $record): bool => $record->read_at !== null)
                    ->action(fn (ContactSubmission $record): bool => $record->update(['read_at' => null])),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactSubmissions::route('/'),
            'view' => ViewContactSubmission::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
