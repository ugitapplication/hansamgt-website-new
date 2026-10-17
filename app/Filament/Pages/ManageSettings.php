<?php

namespace App\Filament\Pages;

use App\Support\Settings;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Site Settings — the values that were previously duplicated across half a
 * dozen Blade files (logo, phone, email, address, social links) plus the
 * analytics and structured-data facts.
 *
 * What is deliberately *not* here: mail credentials. Storing them in the
 * database would leave them in plaintext and readable by every admin. The
 * transport and the sender address are shown read-only, and the only mail
 * action available is a test send.
 */
class ManageSettings extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?string $slug = 'settings';

    protected ?string $subheading = 'Contact details, branding, social profiles, analytics and the facts published in structured data.';

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    /**
     * Image settings handled by the uploader. When one of these has no stored
     * value the bundled image in config/site.php is used, so the field is left
     * empty rather than pre-filled with a path Filament cannot preview.
     *
     * @var list<string>
     */
    protected const IMAGE_KEYS = ['logo', 'footer_logo', 'favicon', 'og_image'];

    public function mount(): void
    {
        $this->form->fill($this->currentValues());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Site settings')->tabs([

                Tab::make('Contact')->schema([
                    Section::make('Website enquiries')
                        ->description($this->mailStatusDescription())
                        ->columns(2)
                        ->schema([
                            TextInput::make('enquiry_recipient')
                                ->label('Send enquiries to')
                                ->email()
                                ->required()
                                ->maxLength(190)
                                ->helperText('The address that receives contact-form submissions.'),
                            TextInput::make('email')
                                ->label('Public email address')
                                ->email()
                                ->required()
                                ->maxLength(190)
                                ->helperText('Shown in the footer, the contact page and structured data.'),
                        ]),
                    Section::make('Phone')
                        ->columns(2)
                        ->schema([
                            TextInput::make('phone_primary')
                                ->label('Primary phone')
                                ->tel()
                                ->required()
                                ->maxLength(30)
                                ->helperText('Include the country code, e.g. +8801708800870.'),
                            TextInput::make('phone_emergency')
                                ->label('Emergency phone')
                                ->tel()
                                ->maxLength(30)
                                ->helperText('Leave empty to hide.'),
                            TextInput::make('whatsapp')
                                ->label('WhatsApp number')
                                ->tel()
                                ->maxLength(30)
                                ->helperText('International format, e.g. +8801708800870. Leave empty to hide click-to-chat.'),
                            TextInput::make('fax')
                                ->label('Fax')
                                ->maxLength(50),
                        ]),
                    Section::make('Address')
                        ->columns(2)
                        ->schema([
                            Textarea::make('postal_address')
                                ->label('Postal address (displayed)')
                                ->rows(2)
                                ->required()
                                ->columnSpanFull(),
                            TextInput::make('street_address')
                                ->label('Street address')
                                ->helperText('Used in structured data.'),
                            TextInput::make('address_locality')
                                ->label('City'),
                            TextInput::make('postal_code')
                                ->label('Postal code'),
                            TextInput::make('address_country')
                                ->label('Country code')
                                ->helperText('Two letters, e.g. BD.'),
                        ]),
                ]),

                Tab::make('Branding')->schema([
                    Section::make('Images')
                        ->description('Leave a field empty to keep the bundled HANSA image.')
                        ->columns(2)
                        ->schema([
                            $this->imageUpload('logo', 'Header logo'),
                            $this->imageUpload('footer_logo', 'Footer logo'),
                            $this->imageUpload('favicon', 'Favicon / app icon'),
                            $this->imageUpload('og_image', 'Default social share image'),
                        ]),
                    Section::make('Footer')
                        ->schema([
                            Textarea::make('footer_about')
                                ->label('Footer introduction')
                                ->rows(3)
                                ->maxLength(400)
                                ->columnSpanFull(),
                        ]),
                ]),

                Tab::make('Social profiles')->schema([
                    Section::make('Profiles')
                        ->description('Only the profiles with a URL are published. Clear a field to remove that icon from the footer and the contact page — a placeholder link can never be shown by accident.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('facebook')->label('Facebook')->url()->maxLength(255),
                            TextInput::make('linkedin')->label('LinkedIn')->url()->maxLength(255),
                            TextInput::make('youtube')->label('YouTube')->url()->maxLength(255),
                            TextInput::make('twitter')->label('X (Twitter)')->url()->maxLength(255),
                            TextInput::make('instagram')->label('Instagram')->url()->maxLength(255),
                        ]),
                ]),

                Tab::make('Analytics & legal')->schema([
                    Section::make('Google Analytics 4')
                        ->description('The Google tag is only requested after a visitor accepts it. Setting GA4_ENABLED=false in .env overrides this and removes the banner entirely.')
                        ->columns(2)
                        ->schema([
                            Toggle::make('ga4_enabled')
                                ->label('Analytics enabled')
                                ->default(true),
                            TextInput::make('ga4_id')
                                ->label('Measurement ID')
                                ->maxLength(30)
                                ->helperText('The GA4 measurement ID, e.g. G-XXXXXXXXXX.'),
                            TextInput::make('consent_version')
                                ->label('Consent version')
                                ->numeric()
                                ->integer()
                                ->required()
                                ->helperText('Increase this whenever the cookies or the purposes change, so returning visitors are asked again.'),
                        ]),
                    Section::make('Legal')->schema([
                        DatePicker::make('privacy_updated')
                            ->label('Privacy policy last updated')
                            ->native(false)
                            ->helperText('Shown on the privacy policy page.'),
                    ]),
                ]),

                Tab::make('SEO & structured data')->schema([
                    Section::make('Organisation')
                        ->description('Published as schema.org Organization / LocalBusiness data and used in social previews.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('legal_name')
                                ->label('Legal name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('site_url')
                                ->label('Website URL')
                                ->url()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('parent_name')
                                ->label('Parent company')
                                ->maxLength(255),
                            TextInput::make('parent_url')
                                ->label('Parent company URL')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('founding_year')
                                ->label('Founded (year)')
                                ->numeric()
                                ->integer()
                                ->minValue(1900)
                                ->maxValue(2100),
                            Textarea::make('description')
                                ->label('Short description')
                                ->rows(2)
                                ->maxLength(300)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Local business')
                        ->description('Leave latitude, longitude and opening hours empty until the real values are known — publishing invented values produces misleading search results.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('latitude')
                                ->label('Latitude')
                                ->maxLength(20),
                            TextInput::make('longitude')
                                ->label('Longitude')
                                ->maxLength(20),
                            TextInput::make('opening_hours')
                                ->label('Opening hours')
                                ->maxLength(120)
                                ->helperText('For example: Mo-Fr 09:00-18:00.'),
                        ]),
                ]),

            ]),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make($this->getFormActions())
                        ->alignment(Alignment::Start)
                        ->key('form-actions'),
                ]),
        ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendTestEmail')
                ->label('Send test email')
                ->icon('heroicon-o-paper-airplane')
                ->requiresConfirmation()
                ->modalHeading('Send a test email')
                ->modalDescription('Sends a short test message to the saved enquiry recipient address. Save this page first if you have just changed it.')
                ->action(function (): void {
                    $this->sendTestEmail();
                }),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Settings::setMany($state);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    /**
     * Prove that outgoing mail works without exposing any credentials.
     */
    public function sendTestEmail(): void
    {
        $recipient = Settings::get('enquiry_recipient');

        if (blank($recipient)) {
            Notification::make()
                ->title('No enquiry recipient is set')
                ->body('Save an address in the Contact tab first.')
                ->danger()
                ->send();

            return;
        }

        try {
            Mail::raw(
                'This is a test message from the HANSA Management website admin panel. '.
                'If you are reading it, contact-form enquiries are being delivered.',
                function ($message) use ($recipient): void {
                    $message->to($recipient)->subject('HANSA website — test email');
                },
            );
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('The test email could not be sent')
                ->body(Str::limit($exception->getMessage(), 300))
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Test email sent to '.$recipient)
            ->success()
            ->send();
    }

    /**
     * The values the form is filled with: the stored value, otherwise the
     * default from config/site.php.
     *
     * @return array<string, mixed>
     */
    protected function currentValues(): array
    {
        $defaults = config('site.defaults', []);
        $stored = Settings::all();
        $values = [];

        foreach (array_keys($defaults) as $key) {
            if (in_array($key, self::IMAGE_KEYS, true)) {
                // Only ever a real upload. An empty field means "use the
                // bundled image", which the public site resolves itself.
                $values[$key] = $stored[$key] ?? null;

                continue;
            }

            $value = $stored[$key] ?? null;

            $values[$key] = ($value === null || $value === '') ? $defaults[$key] : $value;
        }

        $values['ga4_enabled'] = filter_var($values['ga4_enabled'] ?? false, FILTER_VALIDATE_BOOL);

        return $values;
    }

    protected function imageUpload(string $key, string $label): FileUpload
    {
        return FileUpload::make($key)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('branding')
            ->visibility('public')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize(2048)
            ->imagePreviewHeight('80')
            ->helperText('JPG, PNG or WebP, max 2 MB.');
    }

    /**
     * Mail configuration is read-only by design. Only the transport and the
     * sender address are shown — never a hostname, username or password.
     */
    protected function mailStatusDescription(): string
    {
        $transport = (string) config('mail.default');
        $from = (string) config('mail.from.address');

        return 'Enquiries are emailed using the "'.$transport.'" transport, from '.$from.'. '
            .'Those settings live in the server configuration (.env) and are deliberately not editable here: '
            .'a database row would hold credentials in plaintext, readable by every admin and stolen with any leak. '
            .'Use “Send test email” above to check delivery.';
    }
}
