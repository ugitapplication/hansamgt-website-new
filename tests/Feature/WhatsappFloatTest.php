<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Support\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The floating click-to-chat button.
 *
 * Two independent conditions gate it and both are covered here: the switch in
 * Settings → Site Settings, and a number that can actually be dialled. The
 * stored value is never published as it stands — wa.me accepts digits only.
 */
class WhatsappFloatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The class's statics outlive the application, so a value read in one test
     * would be served to the next one from memory.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Settings::flush();
    }

    private function store(string $key, ?string $value): void
    {
        Setting::query()->create(['key' => $key, 'value' => $value]);

        Settings::flush();
    }

    private function setWhatsapp(string $number, bool $enabled = true): void
    {
        $this->store('whatsapp', $number);
        $this->store('whatsapp_enabled', $enabled ? '1' : '0');
    }

    public function test_no_button_is_rendered_while_no_number_is_stored(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $this->assertStringNotContainsString('wa.me', (string) $response->getContent());
    }

    public function test_the_switch_hides_the_button_but_keeps_the_number(): void
    {
        $this->setWhatsapp('+8801708800870', enabled: false);

        $response = $this->get('/');

        $response->assertOk();
        $this->assertStringNotContainsString('wa.me', (string) $response->getContent());

        // Turning the button off is not the same as clearing the number, so it
        // can be switched back on without retyping it.
        $this->assertSame('+8801708800870', Settings::get('whatsapp'));
    }

    public function test_the_button_links_to_the_stored_number(): void
    {
        $this->setWhatsapp('+8801708800870');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('class="whatsapp-float"', false);
        $response->assertSee('https://wa.me/8801708800870', false);
    }

    public function test_the_link_opens_in_a_new_tab_without_handing_over_the_page(): void
    {
        $this->setWhatsapp('+8801708800870');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);
        $response->assertSee('aria-label="Chat with HANSA Management Ltd. on WhatsApp"', false);
    }

    /**
     * The button lives in the layout, so it is on every public page rather than
     * the homepage alone.
     *
     * @return array<string, array{string}>
     */
    public static function publicPages(): array
    {
        return [
            'home' => ['/'],
            'contact' => ['/contact'],
            'management' => ['/management'],
            'gallery' => ['/gallery'],
            'insights' => ['/insights'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_the_button_is_on_every_public_page(string $path): void
    {
        $this->setWhatsapp('+8801708800870');

        $response = $this->get($path);

        $response->assertOk();
        $response->assertSee('https://wa.me/8801708800870', false);
    }

    /**
     * wa.me wants the full international number in digits, so whatever the panel
     * holds has to be converted — and a value that cannot be a number must not
     * become a link at all.
     *
     * @return array<string, array{string, string|null}>
     */
    public static function numbers(): array
    {
        return [
            'international with plus' => ['+8801708800870', 'https://wa.me/8801708800870'],
            'spaces and dashes' => ['+880 1708-800870', 'https://wa.me/8801708800870'],
            'parentheses' => ['+880 (17) 088-00870', 'https://wa.me/8801708800870'],
            'no prefix at all' => ['8801708800870', 'https://wa.me/8801708800870'],
            'international 00 prefix' => ['008801708800870', 'https://wa.me/8801708800870'],
            'empty' => ['', null],
            'placeholder text' => ['call us', null],
            'too short to dial' => ['01708', null],
        ];
    }

    #[DataProvider('numbers')]
    public function test_the_number_is_normalised_for_the_wa_me_url(string $stored, ?string $expected): void
    {
        $this->store('whatsapp', $stored);

        $this->assertSame($expected, Settings::whatsappHref());
    }
}
