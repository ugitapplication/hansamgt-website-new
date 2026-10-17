<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Resources\Concerns\Pages\CreateConcern;
use App\Filament\Resources\Concerns\Pages\EditConcern;
use App\Filament\Resources\Concerns\Pages\ListConcerns;
use App\Models\Concern;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ConcernResource extends Resource
{
    protected static ?string $model = Concern::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static string | \UnitEnum | null $navigationGroup = 'Homepage Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $pluralModelLabel = 'Business Concerns';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Concern / associate')
                ->description('Logos appear in the "Business Concerns & Associates" carousel, in sort order.')
                ->columns(2)
                ->components([
                    FileUpload::make('image')
                        ->label('Logo')
                        ->image()
                        ->disk('public')
                        ->directory('concerns')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->imagePreviewHeight('250')
                        ->helperText('JPG, PNG or WebP, max 2 MB.')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('alt')
                        ->label('Alt text')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Describes the logo for screen readers and SEO.'),
                    TextInput::make('sort_order')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive logos are hidden from the public homepage.')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('Logo')
                    ->square()
                    ->size(80),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('alt')
                    ->label('Alt text')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('sort_order')
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index' => ListConcerns::route('/'),
            'create' => CreateConcern::route('/create'),
            'edit' => EditConcern::route('/{record}/edit'),
        ];
    }
}
