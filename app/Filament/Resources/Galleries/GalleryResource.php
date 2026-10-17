<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\CreateGalleryImage;
use App\Filament\Resources\Galleries\Pages\EditGalleryImage;
use App\Filament\Resources\Galleries\Pages\ListGalleryImages;
use App\Models\GalleryImage;
use App\Support\ImageUrl;
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

class GalleryResource extends Resource
{
    protected static ?string $model = GalleryImage::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | \UnitEnum | null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Gallery';

    protected static ?string $modelLabel = 'photo';

    protected static ?string $pluralModelLabel = 'Gallery';

    protected static ?string $recordTitleAttribute = 'caption';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Photo')
                ->description('Shown in the Site Management gallery grid, in sort order. The caption is also the image alt text.')
                ->columns(2)
                ->components([
                    FileUpload::make('image')
                        ->label('Photo')
                        ->image()
                        ->disk('public')
                        ->directory('gallery')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->imagePreviewHeight('200')
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(fn (string $file): ?array => [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => null,
                            'url' => ImageUrl::resolve($file),
                        ])
                        ->helperText('JPG, PNG or WebP, max 3 MB. Landscape works best.')
                        ->columnSpanFull(),
                    TextInput::make('caption')
                        ->label('Caption')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Shown under the photo and used as its alt text.')
                        ->columnSpanFull(),
                    TextInput::make('sort')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive photos are hidden from the public site.')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('Photo')
                    ->size(60),
                TextColumn::make('caption')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
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
            'index' => ListGalleryImages::route('/'),
            'create' => CreateGalleryImage::route('/create'),
            'edit' => EditGalleryImage::route('/{record}/edit'),
        ];
    }
}
