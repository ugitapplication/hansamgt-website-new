<?php

namespace App\Filament\Resources\Offices;

use App\Filament\Resources\Offices\Pages\CreateOffice;
use App\Filament\Resources\Offices\Pages\EditOffice;
use App\Filament\Resources\Offices\Pages\ListOffices;
use App\Models\Office;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Offices';

    protected static ?string $pluralModelLabel = 'Offices';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Office')
                ->description('Shown on the contact page and in the “Our Offices” section of the homepage, in sort order.')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Textarea::make('address')
                        ->label('Address')
                        ->required()
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('phone')
                        ->label('Mobile')
                        ->tel()
                        ->maxLength(30)
                        ->helperText('Leave empty when this office has no separate mobile number.'),
                    TextInput::make('landline')
                        ->label('Landline')
                        ->maxLength(50),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(190)
                        ->helperText('Leave empty to inherit the main address shown in the footer.'),
                    TextInput::make('sort')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive offices are hidden from the public site.')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('address')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('phone')
                    ->label('Mobile')
                    ->placeholder('—'),
                TextColumn::make('landline')
                    ->label('Landline')
                    ->placeholder('—'),
                TextColumn::make('email')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->actions([
                EditAction::make(),
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
            'index' => ListOffices::route('/'),
            'create' => CreateOffice::route('/create'),
            'edit' => EditOffice::route('/{record}/edit'),
        ];
    }
}
