<?php

namespace App\Filament\Resources\HomepageSections;

use App\Filament\Resources\HomepageSections\Pages\CreateHomepageSectionItem;
use App\Filament\Resources\HomepageSections\Pages\EditHomepageSectionItem;
use App\Filament\Resources\HomepageSections\Pages\ListHomepageSectionItems;
use App\Models\HomepageSectionItem;
use App\Support\ImageUrl;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HomepageSectionItemResource extends Resource
{
    protected static ?string $model = HomepageSectionItem::class;

    protected static ?string $slug = 'homepage-section-items';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Homepage Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Homepage Sections';

    protected static ?string $modelLabel = 'homepage item';

    protected static ?string $pluralModelLabel = 'Homepage Sections';

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * @return list<string>
     */
    private static function cardSections(): array
    {
        return ['commitments', 'glance'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Item')
                ->description('One card, statistic or bullet point. Which fields matter depends on the section you pick.')
                ->columns(2)
                ->components([
                    Select::make('section')
                        ->label('Section')
                        ->options(HomepageSectionItem::SECTIONS)
                        ->required()
                        ->native(false)
                        ->live()
                        ->helperText('The homepage block this item belongs to.')
                        ->columnSpanFull(),
                    TextInput::make('title')
                        ->label(fn (Get $get): string => $get('section') === 'numbers' ? 'Label under the number' : 'Heading')
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => in_array($get('section'), [...self::cardSections(), 'numbers'], true))
                        ->columnSpanFull(),
                    TextInput::make('value')
                        ->label('Number')
                        ->maxLength(50)
                        ->visible(fn (Get $get): bool => $get('section') === 'numbers')
                        ->helperText('Exactly as shown, e.g. No 1, 1000+, 120+ or 24/7.')
                        ->required(fn (Get $get): bool => $get('section') === 'numbers'),
                    TextInput::make('value_from_year')
                        ->label('…or count from a year')
                        ->numeric()
                        ->integer()
                        ->minValue(1900)
                        ->maxValue(2100)
                        ->visible(fn (Get $get): bool => $get('section') === 'numbers')
                        ->helperText('Leave empty for a fixed number. Set it to 2015 and the figure becomes “years since 2015” and stays correct every year.'),
                    Textarea::make('body')
                        ->label(fn (Get $get): string => $get('section') === 'why' ? 'Bullet point' : 'Text')
                        ->rows(3)
                        ->visible(fn (Get $get): bool => in_array($get('section'), [...self::cardSections(), 'why'], true))
                        ->columnSpanFull(),
                    Select::make('group')
                        ->label('Column')
                        ->options(['1' => 'Left column', '2' => 'Right column'])
                        ->default('1')
                        ->native(false)
                        ->visible(fn (Get $get): bool => $get('section') === 'why')
                        ->helperText('“Why to choose HANSA” runs as two columns of bullet points.'),
                    Select::make('icon')
                        ->label('Icon')
                        ->options(HomepageSectionItem::ICONS)
                        ->native(false)
                        ->visible(fn (Get $get): bool => $get('section') === 'commitments')
                        ->helperText('Drawn on the card. The icons themselves live in the page template.'),
                    FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->disk('public')
                        ->directory('homepage')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->imagePreviewHeight('180')
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(fn (string $file): ?array => [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => null,
                            'url' => ImageUrl::resolve($file),
                        ])
                        ->visible(fn (Get $get): bool => in_array($get('section'), self::cardSections(), true))
                        ->helperText('JPG, PNG or WebP, max 3 MB. Landscape (~600x400) works best.')
                        ->columnSpanFull(),
                    TextInput::make('image_alt')
                        ->label('Image alt text')
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => in_array($get('section'), self::cardSections(), true))
                        ->helperText('Leave empty when the image only decorates the card.')
                        ->columnSpanFull(),
                    TextInput::make('sort')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required()
                        ->helperText('Lower numbers appear first inside the section.'),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive items are hidden from the public site.')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('Image')
                    ->size(50)
                    ->placeholder('—'),
                TextColumn::make('section')
                    ->label('Section')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HomepageSectionItem::SECTIONS[$state] ?? $state),
                TextColumn::make('title')
                    ->label('Heading')
                    ->limit(40)
                    ->wrap()
                    ->placeholder('—'),
                TextColumn::make('display_value')
                    ->label('Number')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('sort')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('sort')
            ->filters([
                SelectFilter::make('section')
                    ->label('Section')
                    ->options(HomepageSectionItem::SECTIONS),
            ])
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
            'index' => ListHomepageSectionItems::route('/'),
            'create' => CreateHomepageSectionItem::route('/create'),
            'edit' => EditHomepageSectionItem::route('/{record}/edit'),
        ];
    }
}
