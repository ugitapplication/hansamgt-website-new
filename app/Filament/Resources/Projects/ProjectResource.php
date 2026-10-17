<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Models\Project;
use App\Support\ImageUrl;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string | \UnitEnum | null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Projects';

    protected static ?string $pluralModelLabel = 'Projects';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Project')
                ->description('Shown on the Projects page under its Ongoing / Upcoming filter, in sort order.')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('location')
                        ->label('Location')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Select::make('category')
                        ->label('Category')
                        ->options(Project::CATEGORIES)
                        ->default('ongoing')
                        ->required()
                        ->native(false)
                        ->helperText('Drives the Ongoing / Upcoming filter buttons.'),
                    TextInput::make('sort')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    FileUpload::make('image')
                        ->label('Photo')
                        ->image()
                        ->disk('public')
                        ->directory('projects')
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
                        ->helperText('JPG, PNG or WebP, max 3 MB. Landscape (~700x390) works best.')
                        ->columnSpanFull(),
                    TextInput::make('image_alt')
                        ->label('Photo alt text')
                        ->maxLength(255)
                        ->helperText('Describes the building for screen readers and SEO.')
                        ->columnSpanFull(),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive projects are hidden from the public site.')
                        ->default(true),
                ]),
            Section::make('Homepage card')
                ->description('The “Service Projects” band on the homepage. Only featured projects appear there, and the wording can differ from the Projects page.')
                ->columns(2)
                ->components([
                    Toggle::make('is_featured')
                        ->label('Show on the homepage')
                        ->helperText('The band shows the featured projects in sort order.')
                        ->default(false),
                    TextInput::make('homepage_title')
                        ->label('Homepage name')
                        ->maxLength(255)
                        ->helperText('Leave empty to use the project name above.'),
                    TextInput::make('homepage_location')
                        ->label('Homepage location')
                        ->maxLength(255)
                        ->helperText('Leave empty to use the location above.'),
                    TextInput::make('homepage_alt')
                        ->label('Homepage photo alt text')
                        ->maxLength(255),
                    TextInput::make('homepage_sort')
                        ->label('Homepage order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->helperText('Order inside the homepage band (1 = first). Independent of the Projects page order.'),
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
                TextColumn::make('title')
                    ->label('Name')
                    ->searchable()
                    ->limit(45)
                    ->wrap(),
                TextColumn::make('category_label')
                    ->label('Category')
                    ->badge(),
                TextColumn::make('location')
                    ->limit(35)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_featured')
                    ->label('Homepage'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
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
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
