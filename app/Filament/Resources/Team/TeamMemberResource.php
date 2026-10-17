<?php

namespace App\Filament\Resources\Team;

use App\Filament\Resources\Team\Pages\CreateTeamMember;
use App\Filament\Resources\Team\Pages\EditTeamMember;
use App\Filament\Resources\Team\Pages\ListTeamMembers;
use App\Models\TeamMember;
use App\Support\ImageUrl;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
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

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static ?string $slug = 'team-members';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Team';

    protected static ?string $modelLabel = 'team member';

    protected static ?string $pluralModelLabel = 'Team';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Member')
                ->description('Appears on the Management page as a flip card with a “read more” dialog.')
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state));
                            }
                        }),
                    TextInput::make('role')
                        ->label('Role / title')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->label('Reference')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->helperText('Internal anchor for the dialog. Lowercase letters, numbers and dashes.')
                        ->columnSpanFull(),
                    TextInput::make('modal_name')
                        ->label('Name in the dialog heading')
                        ->maxLength(255)
                        ->helperText('Leave empty to use the name above.'),
                    TextInput::make('sort')
                        ->label('Sort order')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    Textarea::make('teaser')
                        ->label('Card text')
                        ->required()
                        ->rows(3)
                        ->helperText('Shown on the back of the flip card, before the “read more” link.')
                        ->columnSpanFull(),
                    Textarea::make('bio')
                        ->label('Full biography')
                        ->required()
                        ->rows(6)
                        ->helperText('Shown in the dialog when a visitor clicks “read more”.')
                        ->columnSpanFull(),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive members are hidden from the public site.')
                        ->default(true),
                ]),
            Section::make('Portrait')
                ->columns(2)
                ->components([
                    FileUpload::make('image')
                        ->label('Photo')
                        ->image()
                        ->disk('public')
                        ->directory('team')
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
                        ->helperText('Square images work best. JPG, PNG or WebP, max 3 MB.')
                        ->columnSpanFull(),
                    TextInput::make('image_alt')
                        ->label('Photo alt text')
                        ->maxLength(255)
                        ->helperText('Leave empty to describe the photo from the name and role.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview_url')
                    ->label('Photo')
                    ->square()
                    ->size(60),
                TextColumn::make('name')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('role')
                    ->limit(35)
                    ->wrap(),
                TextColumn::make('sort')
                    ->label('Order')
                    ->sortable(),
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
            'index' => ListTeamMembers::route('/'),
            'create' => CreateTeamMember::route('/create'),
            'edit' => EditTeamMember::route('/{record}/edit'),
        ];
    }
}
