<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ThemeFontSettings extends Component
{
    // Font Family Properties
    public string $fontSans = '';

    public string $fontOutfit = '';

    // Font Size Properties (px values - user input)
    public ?int $fontSizeH1Px = 48;  // 3rem

    public ?int $fontSizeH2Px = 36;  // 2.25rem

    public ?int $fontSizeH3Px = 24;  // 1.5rem

    public ?int $fontSizeH4Px = 18;  // 1.125rem

    public ?int $fontSizeH5Px = 16;  // 1rem

    public ?int $fontSizeH6Px = 14;  // 0.875rem

    public ?int $fontSizePPx = 16;   // 1rem

    // Computed properties for rem values (read-only, for display)
    public function getFontSizeH1RemProperty(): float
    {
        $px = $this->fontSizeH1Px ?? 48;

        return round($px / 16, 4);
    }

    public function getFontSizeH2RemProperty(): float
    {
        $px = $this->fontSizeH2Px ?? 36;

        return round($px / 16, 4);
    }

    public function getFontSizeH3RemProperty(): float
    {
        $px = $this->fontSizeH3Px ?? 24;

        return round($px / 16, 4);
    }

    public function getFontSizeH4RemProperty(): float
    {
        $px = $this->fontSizeH4Px ?? 18;

        return round($px / 16, 4);
    }

    public function getFontSizeH5RemProperty(): float
    {
        $px = $this->fontSizeH5Px ?? 16;

        return round($px / 16, 4);
    }

    public function getFontSizeH6RemProperty(): float
    {
        $px = $this->fontSizeH6Px ?? 14;

        return round($px / 16, 4);
    }

    public function getFontSizePRemProperty(): float
    {
        $px = $this->fontSizePPx ?? 16;

        return round($px / 16, 4);
    }

    public function mount(): void
    {
        // Load current settings
        $this->fontSans = $this->getFontSetting('theme_font_sans', 'Inter');
        $this->fontOutfit = $this->getFontSetting('theme_font_outfit', 'Outfit');

        // Load font sizes and convert rem to px for display
        $this->fontSizeH1Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h1', '3rem'));
        $this->fontSizeH2Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h2', '2.25rem'));
        $this->fontSizeH3Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h3', '1.5rem'));
        $this->fontSizeH4Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h4', '1.125rem'));
        $this->fontSizeH5Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h5', '1rem'));
        $this->fontSizeH6Px = $this->remToPx($this->getFontSizeSetting('theme_font_size_h6', '0.875rem'));
        $this->fontSizePPx = $this->remToPx($this->getFontSizeSetting('theme_font_size_p', '1rem'));
    }

    public function updated($propertyName): void
    {
        // No auto-save - changes are saved only when "Save Theme Settings" button is clicked
        // Only validate font size values when they change
        if (str_starts_with($propertyName, 'fontSize')) {
            // Ensure numeric values are set, use defaults if null or empty
            $this->ensureFontSizeValues();
        }
    }

    /**
     * Ensure all font size values are set to valid numbers
     */
    private function ensureFontSizeValues(): void
    {
        $defaults = [
            'fontSizeH1Px' => 48,  // 3rem
            'fontSizeH2Px' => 36,  // 2.25rem
            'fontSizeH3Px' => 24,  // 1.5rem
            'fontSizeH4Px' => 18,  // 1.125rem
            'fontSizeH5Px' => 16,  // 1rem
            'fontSizeH6Px' => 14,  // 0.875rem
            'fontSizePPx' => 16,   // 1rem
        ];

        foreach ($defaults as $property => $default) {
            // Check if value is null, empty string, or empty after trimming
            $value = $this->$property;
            if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
                $this->$property = $default;
            } elseif (! is_numeric($value)) {
                $this->$property = $default;
            } elseif ((int) $value < 1) {
                $this->$property = 1;
            } elseif ((int) $value > 3200) {
                $this->$property = 3200;
            } else {
                $this->$property = (int) $value;
            }
        }
    }

    /**
     * Public method to save font settings (can be called from JavaScript or automatically)
     */
    public function save(): void
    {
        // Ensure all font size values are valid before saving
        $this->ensureFontSizeValues();

        // Save font families
        $this->updateSetting('theme_font_sans', $this->fontSans);
        $this->updateSetting('theme_font_outfit', $this->fontOutfit);

        // Save font sizes (convert px to rem)
        $this->updateSetting('theme_font_size_h1', $this->pxToRem($this->fontSizeH1Px ?? 48));
        $this->updateSetting('theme_font_size_h2', $this->pxToRem($this->fontSizeH2Px ?? 36));
        $this->updateSetting('theme_font_size_h3', $this->pxToRem($this->fontSizeH3Px ?? 24));
        $this->updateSetting('theme_font_size_h4', $this->pxToRem($this->fontSizeH4Px ?? 18));
        $this->updateSetting('theme_font_size_h5', $this->pxToRem($this->fontSizeH5Px ?? 16));
        $this->updateSetting('theme_font_size_h6', $this->pxToRem($this->fontSizeH6Px ?? 14));
        $this->updateSetting('theme_font_size_p', $this->pxToRem($this->fontSizePPx ?? 16));

        // Clear cache
        $this->clearCaches();

        // Don't dispatch success event here - it will be shown after form submission
    }

    /**
     * Convert px to rem (1rem = 16px)
     */
    private function pxToRem(?int $px): string
    {
        $px = $px ?? 16; // Default to 16px (1rem) if null

        return round($px / 16, 4).'rem';
    }

    /**
     * Convert rem to px (1rem = 16px)
     */
    private function remToPx(string $remValue): int
    {
        $rem = (float) str_replace('rem', '', $remValue);

        return (int) round($rem * 16);
    }

    /**
     * Get font setting value
     */
    private function getFontSetting(string $key, string $default): string
    {
        $value = Setting::getValue($key, $default);

        // Remove quotes and sans-serif suffix if present
        return str_replace(['"', ', sans-serif'], '', $value);
    }

    /**
     * Get font size setting value
     */
    private function getFontSizeSetting(string $key, string $default): string
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Update a setting
     */
    private function updateSetting(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $key === 'theme_font_sans' || $key === 'theme_font_outfit' ? 'text' : (str_starts_with($key, 'theme_font_size_') ? 'text' : 'color'),
                'group' => 'theme',
                'display_name' => $this->getDisplayName($key),
                'description' => $this->getDescription($key),
                'order' => $this->getOrder($key),
            ]
        );
    }

    /**
     * Get display name based on key
     */
    private function getDisplayName(string $key): string
    {
        $names = [
            'theme_font_sans' => 'Sans-serif Font',
            'theme_font_outfit' => 'Outfit Font',
            'theme_font_size_h1' => 'H1 Font Size',
            'theme_font_size_h2' => 'H2 Font Size',
            'theme_font_size_h3' => 'H3 Font Size',
            'theme_font_size_h4' => 'H4 Font Size',
            'theme_font_size_h5' => 'H5 Font Size',
            'theme_font_size_h6' => 'H6 Font Size',
            'theme_font_size_p' => 'Paragraph Font Size',
        ];

        return $names[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Get description based on key
     */
    private function getDescription(string $key): ?string
    {
        $descriptions = [
            'theme_font_sans' => 'The primary sans-serif font family (e.g., Inter)',
            'theme_font_outfit' => 'The secondary font family for headings (e.g., Outfit)',
            'theme_font_size_h1' => 'Font size for H1 headings',
            'theme_font_size_h2' => 'Font size for H2 headings',
            'theme_font_size_h3' => 'Font size for H3 headings',
            'theme_font_size_h4' => 'Font size for H4 headings',
            'theme_font_size_h5' => 'Font size for H5 headings',
            'theme_font_size_h6' => 'Font size for H6 headings',
            'theme_font_size_p' => 'Font size for paragraphs',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Get order based on key
     */
    private function getOrder(string $key): int
    {
        $orders = [
            'theme_font_sans' => 4,
            'theme_font_outfit' => 5,
            'theme_font_size_h1' => 6,
            'theme_font_size_h2' => 7,
            'theme_font_size_h3' => 8,
            'theme_font_size_h4' => 9,
            'theme_font_size_h5' => 10,
            'theme_font_size_h6' => 11,
            'theme_font_size_p' => 12,
        ];

        return $orders[$key] ?? 0;
    }

    /**
     * Clear relevant caches
     */
    private function clearCaches(): void
    {
        Setting::forgetAggregateCache();
        $settingKeys = [
            'theme_font_sans',
            'theme_font_outfit',
            'theme_font_size_h1',
            'theme_font_size_h2',
            'theme_font_size_h3',
            'theme_font_size_h4',
            'theme_font_size_h5',
            'theme_font_size_h6',
            'theme_font_size_p',
        ];

        foreach ($settingKeys as $key) {
            Cache::forget("settings.{$key}");
        }
    }

    public function render()
    {
        // Get Google Fonts list for dropdown
        $googleFonts = $this->getGoogleFonts();

        return view('livewire.admin.theme-font-settings', [
            'googleFonts' => $googleFonts,
        ]);
    }

    /**
     * Get Google Fonts list (same as ThemeSettingController)
     */
    private function getGoogleFonts(): array
    {
        return [
            'Inter' => 'Inter',
            'Outfit' => 'Outfit',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Raleway' => 'Raleway',
            'Source Sans Pro' => 'Source Sans Pro',
            'Ubuntu' => 'Ubuntu',
            'Nunito' => 'Nunito',
            'Oswald' => 'Oswald',
            'Merriweather' => 'Merriweather',
            'Playfair Display' => 'Playfair Display',
            'Lora' => 'Lora',
            'PT Sans' => 'PT Sans',
            'PT Serif' => 'PT Serif',
            'Crimson Text' => 'Crimson Text',
            'Fira Sans' => 'Fira Sans',
            'Libre Baskerville' => 'Libre Baskerville',
        ];
    }
}
