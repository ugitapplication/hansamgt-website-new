<?php

namespace App\Filament\Resources\HeroSlides;

use App\Filament\Resources\HeroSlides\Pages\CreateHeroSlide;
use App\Filament\Resources\HeroSlides\Pages\EditHeroSlide;
use App\Filament\Resources\HeroSlides\Pages\ListHeroSlides;
use App\Models\HeroSlide;
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

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | \UnitEnum | null $navigationGroup = 'Homepage Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $pluralModelLabel = 'Hero Slider';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Slide content')
                ->description('Images appear in the homepage hero rotator, in sort order.')
                ->columns(2)
                ->components([
                    FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->disk('public')
                        ->directory('hero-slides')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->imagePreviewHeight('250')
                        ->helperText('JPG, PNG or WebP, max 2 MB. Wide landscape images work best.')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('alt')
                        ->label('Alt text')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Describes the image for screen readers and SEO.')
                        ->columnSpanFull(),
                    TextInput::make('label')
                        ->label('Admin label')
                        ->maxLength(255)
                        ->placeholder('e.g. Housekeeping slide')
                        ->helperText('Shown only in the admin panel.'),
                    TextInput::make('sort_order')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive slides are hidden from the public homepage.')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('Preview')
                    ->square()
                    ->size(80),
                TextColumn::make('label')
                    ->label('Label')
                    ->placeholder('—')
                    ->searchable()
                    ->limit(40),
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
            'index' => ListHeroSlides::route('/'),
            'create' => CreateHeroSlide::route('/create'),
            'edit' => EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
