<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    protected static string | \UnitEnum | null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Insights';

    protected static ?string $modelLabel = 'article';

    protected static ?string $pluralModelLabel = 'Insights';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Article')
                ->description('The article body — this is what readers see on the public Insights page.')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state));
                            }
                        })
                        ->helperText('Also used as the page heading and, unless overridden, the meta title.')
                        ->columnSpanFull(),
                    TextInput::make('slug')
                        ->label('URL slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('The public URL: /insights/your-slug — lowercase letters, numbers and dashes.')
                        ->columnSpanFull(),
                    Textarea::make('excerpt')
                        ->label('Excerpt')
                        ->rows(3)
                        ->maxLength(300)
                        ->helperText('Short summary shown on the Insights index and used as the fallback meta description.')
                        ->columnSpanFull(),
                    RichEditor::make('body')
                        ->label('Body')
                        ->required()
                        ->helperText('Use headings, lists and links to structure the article.')
                        ->columnSpanFull(),
                ]),
            Section::make('Cover image')
                ->description('Optional. Shown on the Insights index, the article page and social previews.')
                ->columns(2)
                ->components([
                    FileUpload::make('cover_image')
                        ->label('Cover image')
                        ->image()
                        ->disk('public')
                        ->directory('posts')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->imagePreviewHeight('250')
                        ->helperText('JPG, PNG or WebP, max 3 MB. Landscape images (~1600x900) work best.')
                        ->columnSpanFull(),
                    TextInput::make('cover_image_alt')
                        ->label('Cover image alt text')
                        ->maxLength(255)
                        ->helperText('Describes the image for screen readers and SEO.')
                        ->columnSpanFull(),
                ]),
            Section::make('Publishing')
                ->columns(2)
                ->components([
                    Toggle::make('is_published')
                        ->label('Published')
                        ->helperText('Unpublished articles are hidden from the public site.')
                        ->default(false),
                    DateTimePicker::make('published_at')
                        ->label('Publish date & time')
                        ->seconds(false)
                        ->native(false)
                        ->helperText('Leave empty to use the creation date. A future date keeps the article hidden until then.'),
                ]),
            Section::make('SEO')
                ->description('Optional overrides. Leave empty to use the title and excerpt automatically.')
                ->columns(2)
                ->components([
                    TextInput::make('meta_title')
                        ->label('Meta title')
                        ->maxLength(70)
                        ->helperText('Up to ~60 characters. Falls back to the article title.')
                        ->columnSpanFull(),
                    Textarea::make('meta_description')
                        ->label('Meta description')
                        ->rows(2)
                        ->maxLength(180)
                        ->helperText('Up to ~160 characters. Falls back to the excerpt.')
                        ->columnSpanFull(),
                    FileUpload::make('og_image')
                        ->label('Social share image')
                        ->image()
                        ->disk('public')
                        ->directory('posts/og')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->helperText('Used for social/Open Graph previews. Falls back to the cover image.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('Cover')
                    ->square()
                    ->size(70),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_published')
                    ->label('Published'),
                TextColumn::make('published_at')
                    ->label('Publish date')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
